<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/auth.php';

require_role('admin');
$pageTitle = 'Reports & Analytics';
$currentPageFile = 'reports/index.php';

// Workforce summary
$totalEmps = db()->query("SELECT COUNT(*) FROM employees")->fetchColumn();
$activeEmps = db()->query("SELECT COUNT(*) FROM employees WHERE status='active'")->fetchColumn();
$onLeave = db()->query("SELECT COUNT(*) FROM employees WHERE status='on_leave'")->fetchColumn();
$terminated = db()->query("SELECT COUNT(*) FROM employees WHERE status='terminated'")->fetchColumn();

// Client summary
$totalClients = db()->query("SELECT COUNT(*) FROM clients WHERE status='active'")->fetchColumn();

// Payroll summary
$totalPaid = db()->query("SELECT COALESCE(SUM(net_pay),0) FROM payroll WHERE status='paid'")->fetchColumn();
$totalPending = db()->query("SELECT COALESCE(SUM(net_pay),0) FROM payroll WHERE status='pending'")->fetchColumn();
$totalProcessed = db()->query("SELECT COALESCE(SUM(net_pay),0) FROM payroll WHERE status='processed'")->fetchColumn();

// Client-wise breakdown
$clientStats = db()->query("
    SELECT c.company_name, 
           COUNT(e.id) as emp_count,
           COALESCE(SUM(e.salary),0) as monthly_cost,
           COALESCE((SELECT SUM(p.net_pay) FROM payroll p JOIN employees e2 ON p.employee_id = e2.id WHERE e2.client_id = c.id AND p.status='paid'),0) as total_paid
    FROM clients c 
    LEFT JOIN employees e ON c.id = e.client_id 
    GROUP BY c.id 
    ORDER BY emp_count DESC
")->fetchAll();

// Department breakdown
$deptStats = db()->query("SELECT department, COUNT(*) as cnt, COALESCE(SUM(salary),0) as cost FROM employees GROUP BY department ORDER BY cnt DESC")->fetchAll();

// Compliance summary
$compStats = db()->query("SELECT verification_status, COUNT(*) as cnt FROM compliance GROUP BY verification_status")->fetchAll();
$compSummary = ['verified' => 0, 'pending' => 0, 'rejected' => 0];
foreach ($compStats as $cs) { $compSummary[$cs['verification_status']] = $cs['cnt']; }

// Recent activity
$activities = db()->query("SELECT al.*, u.name FROM activity_log al LEFT JOIN users u ON al.user_id = u.id ORDER BY al.created_at DESC LIMIT 10")->fetchAll();

// Contact messages
$unreadMsgs = db()->query("SELECT COUNT(*) FROM contact_messages WHERE is_read = 0")->fetchColumn();
$recentMsgs = db()->query("SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT 5")->fetchAll();

require_once __DIR__ . '/../../includes/portal_header.php';
?>

<div class="stat-grid">
    <div class="stat-card-erp"><div class="stat-icon-box stat-icon-blue">&#128100;</div><div class="stat-info"><div class="stat-val"><?= $totalEmps ?></div><div class="stat-lbl">Total Employees</div></div></div>
    <div class="stat-card-erp"><div class="stat-icon-box stat-icon-green">&#127970;</div><div class="stat-info"><div class="stat-val"><?= $totalClients ?></div><div class="stat-lbl">Active Clients</div></div></div>
    <div class="stat-card-erp"><div class="stat-icon-box stat-icon-amber">&#128176;</div><div class="stat-info"><div class="stat-val"><?= format_inr($totalPaid) ?></div><div class="stat-lbl">Total Paid Out</div></div></div>
    <div class="stat-card-erp"><div class="stat-icon-box stat-icon-red">&#128220;</div><div class="stat-info"><div class="stat-val"><?= $compSummary['pending'] ?></div><div class="stat-lbl">Compliance Pending</div></div></div>
</div>

<div class="grid-2">
    <!-- Employee Status Breakdown -->
    <div class="panel">
        <div class="panel-header"><h3>Employee Status Breakdown</h3></div>
        <div class="panel-body">
            <?php
            $statusData = [
                ['label' => 'Active', 'count' => $activeEmps, 'color' => '#2fa84f'],
                ['label' => 'On Leave', 'count' => $onLeave, 'color' => '#f59e0b'],
                ['label' => 'Terminated', 'count' => $terminated, 'color' => '#dc3545'],
            ];
            foreach ($statusData as $sd):
                $pct = $totalEmps > 0 ? round(($sd['count'] / $totalEmps) * 100) : 0;
            ?>
                <div style="margin-bottom:16px;">
                    <div style="display:flex;justify-content:space-between;margin-bottom:6px;">
                        <span style="font-size:14px;font-weight:500;"><?= e($sd['label']) ?></span>
                        <span style="font-size:13px;color:var(--gray-500);"><?= $sd['count'] ?> (<?= $pct ?>%)</span>
                    </div>
                    <div class="progress-bar"><div class="progress-fill" data-width="<?= $pct ?>" style="background:<?= $sd['color'] ?>"></div></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Payroll Status -->
    <div class="panel">
        <div class="panel-header"><h3>Payroll Summary</h3></div>
        <div class="panel-body">
            <table class="data-table" style="border:none;">
                <tbody>
                    <tr><td style="font-weight:600;">Total Paid</td><td class="text-right"><strong style="color:var(--success);"><?= format_inr($totalPaid) ?></strong></td></tr>
                    <tr><td style="font-weight:600;">Processed (Unpaid)</td><td class="text-right"><strong style="color:var(--info);"><?= format_inr($totalProcessed) ?></strong></td></tr>
                    <tr><td style="font-weight:600;">Pending</td><td class="text-right"><strong style="color:var(--warning);"><?= format_inr($totalPending) ?></strong></td></tr>
                    <tr><td style="font-weight:600;">Total Liability</td><td class="text-right"><strong style="color:var(--blue);"><?= format_inr($totalPending + $totalProcessed) ?></strong></td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Client-wise Report -->
<div class="panel">
    <div class="panel-header"><h3>Client-wise Workforce & Cost Report</h3></div>
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead><tr><th>Client</th><th>Employees</th><th>Monthly Salary Cost</th><th>Total Paid (All Time)</th><th>Avg Salary</th></tr></thead>
            <tbody>
                <?php foreach ($clientStats as $cs): ?>
                <tr>
                    <td><strong><?= e($cs['company_name']) ?></strong></td>
                    <td><?= $cs['emp_count'] ?></td>
                    <td><?= format_inr($cs['monthly_cost']) ?></td>
                    <td><?= format_inr($cs['total_paid']) ?></td>
                    <td><?= $cs['emp_count'] > 0 ? format_inr($cs['monthly_cost'] / $cs['emp_count']) : '-' ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="grid-2">
    <!-- Department Cost -->
    <div class="panel">
        <div class="panel-header"><h3>Department-wise Cost</h3></div>
        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead><tr><th>Department</th><th>Headcount</th><th>Monthly Cost</th></tr></thead>
                <tbody>
                    <?php foreach ($deptStats as $ds): ?>
                    <tr>
                        <td><?= e($ds['department']) ?></td>
                        <td><?= $ds['cnt'] ?></td>
                        <td><?= format_inr($ds['cost']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Compliance Summary -->
    <div class="panel">
        <div class="panel-header"><h3>Compliance Verification Summary</h3></div>
        <div class="panel-body">
            <div style="display:flex;gap:24px;flex-wrap:wrap;">
                <div style="text-align:center;flex:1;min-width:100px;">
                    <div style="font-size:32px;font-weight:800;color:var(--success);"><?= $compSummary['verified'] ?></div>
                    <div style="font-size:13px;color:var(--gray-500);">Verified</div>
                </div>
                <div style="text-align:center;flex:1;min-width:100px;">
                    <div style="font-size:32px;font-weight:800;color:var(--warning);"><?= $compSummary['pending'] ?></div>
                    <div style="font-size:13px;color:var(--gray-500);">Pending</div>
                </div>
                <div style="text-align:center;flex:1;min-width:100px;">
                    <div style="font-size:32px;font-weight:800;color:var(--danger);"><?= $compSummary['rejected'] ?></div>
                    <div style="font-size:13px;color:var(--gray-500);">Rejected</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="grid-2">
    <!-- Recent Activity -->
    <div class="panel">
        <div class="panel-header"><h3>Recent Activity Log</h3></div>
        <div class="panel-body">
            <?php foreach ($activities as $a): ?>
                <div style="padding:10px 0;border-bottom:1px solid var(--gray-100);">
                    <div style="font-size:14px;color:var(--gray-700);"><?= e($a['action']) ?></div>
                    <div style="font-size:12px;color:var(--gray-500);"><?= e($a['name'] ?? 'System') ?> &middot; <?= format_date($a['created_at']) ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Unread Messages -->
    <div class="panel">
        <div class="panel-header"><h3>Contact Messages (<?= $unreadMsgs ?> unread)</h3></div>
        <div class="panel-body">
            <?php foreach ($recentMsgs as $msg): ?>
                <div style="padding:10px 0;border-bottom:1px solid var(--gray-100);">
                    <div style="display:flex;justify-content:space-between;">
                        <strong style="font-size:14px;"><?= e($msg['name']) ?></strong>
                        <?php if (!$msg['is_read']): ?><span class="badge badge-info" style="font-size:10px;">NEW</span><?php endif; ?>
                    </div>
                    <div style="font-size:12px;color:var(--gray-500);"><?= e($msg['company'] ?? '') ?> &middot; <?= e($msg['email']) ?></div>
                    <div style="font-size:13px;color:var(--gray-600);margin-top:4px;"><?= e(substr($msg['message'], 0, 60)) ?><?= strlen($msg['message']) > 60 ? '...' : '' ?></div>
                </div>
            <?php endforeach; ?>
            <?php if (empty($recentMsgs)): ?>
                <div class="empty-state"><p>No messages</p></div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/portal_footer.php'; ?>
