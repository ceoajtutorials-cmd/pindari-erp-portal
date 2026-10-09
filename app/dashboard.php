<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/auth.php';

$pageTitle = 'Dashboard';
$currentPageFile = 'dashboard.php';
$role = $currentUser['role'];
$clientId = $currentUser['client_id'];

// === Role-specific data ===
if ($role === 'admin') {
    // Admin: see everything
    $totalEmployees = db()->query("SELECT COUNT(*) FROM employees")->fetchColumn();
    $activeEmployees = db()->query("SELECT COUNT(*) FROM employees WHERE status = 'active'")->fetchColumn();
    $totalClients = db()->query("SELECT COUNT(*) FROM clients WHERE status = 'active'")->fetchColumn();
    $pendingPayroll = db()->query("SELECT COUNT(*) FROM payroll WHERE status = 'pending'")->fetchColumn();
    $totalSalary = db()->query("SELECT COALESCE(SUM(net_pay),0) FROM payroll WHERE status = 'paid'")->fetchColumn();
    $pendingCompliance = db()->query("SELECT COUNT(*) FROM compliance WHERE verification_status = 'pending'")->fetchColumn();
    $recentEmployees = db()->query("SELECT e.*, c.company_name FROM employees e LEFT JOIN clients c ON e.client_id = c.id ORDER BY e.created_at DESC LIMIT 6")->fetchAll();
    $recentMessages = db()->query("SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT 5")->fetchAll();

    // Department distribution
    $deptData = db()->query("SELECT department, COUNT(*) as cnt FROM employees GROUP BY department ORDER BY cnt DESC")->fetchAll();

    // Client-wise workforce
    $clientData = db()->query("SELECT c.company_name, COUNT(e.id) as emp_count FROM clients c LEFT JOIN employees e ON c.id = e.client_id GROUP BY c.id ORDER BY emp_count DESC")->fetchAll();

} elseif ($role === 'hr') {
    $totalEmployees = db()->query("SELECT COUNT(*) FROM employees")->fetchColumn();
    $activeEmployees = db()->query("SELECT COUNT(*) FROM employees WHERE status = 'active'")->fetchColumn();
    $totalClients = db()->query("SELECT COUNT(*) FROM clients WHERE status = 'active'")->fetchColumn();
    $pendingPayroll = db()->query("SELECT COUNT(*) FROM payroll WHERE status = 'pending'")->fetchColumn();
    $pendingCompliance = db()->query("SELECT COUNT(*) FROM compliance WHERE verification_status = 'pending'")->fetchColumn();
    $recentEmployees = db()->query("SELECT e.*, c.company_name FROM employees e LEFT JOIN clients c ON e.client_id = c.id ORDER BY e.created_at DESC LIMIT 6")->fetchAll();

    $deptData = db()->query("SELECT department, COUNT(*) as cnt FROM employees GROUP BY department ORDER BY cnt DESC")->fetchAll();
    $clientData = db()->query("SELECT c.company_name, COUNT(e.id) as emp_count FROM clients c LEFT JOIN employees e ON c.id = e.client_id GROUP BY c.id ORDER BY emp_count DESC")->fetchAll();

} elseif ($role === 'client') {
    // Client: see only their own data
    $totalEmployees = db()->prepare("SELECT COUNT(*) FROM employees WHERE client_id = ?");
    $totalEmployees->execute([$clientId]);
    $totalEmployees = $totalEmployees->fetchColumn();

    $activeEmployees = db()->prepare("SELECT COUNT(*) FROM employees WHERE client_id = ? AND status = 'active'");
    $activeEmployees->execute([$clientId]);
    $activeEmployees = $activeEmployees->fetchColumn();

    $onLeaveEmployees = db()->prepare("SELECT COUNT(*) FROM employees WHERE client_id = ? AND status = 'on_leave'");
    $onLeaveEmployees->execute([$clientId]);
    $onLeaveEmployees = $onLeaveEmployees->fetchColumn();

    $pendingPayroll = db()->prepare("SELECT COUNT(*) FROM payroll p JOIN employees e ON p.employee_id = e.id WHERE e.client_id = ? AND p.status = 'pending'");
    $pendingPayroll->execute([$clientId]);
    $pendingPayroll = $pendingPayroll->fetchColumn();

    $totalSalary = db()->prepare("SELECT COALESCE(SUM(p.net_pay),0) FROM payroll p JOIN employees e ON p.employee_id = e.id WHERE e.client_id = ? AND p.status = 'paid'");
    $totalSalary->execute([$clientId]);
    $totalSalary = $totalSalary->fetchColumn();

    $recentEmployees = db()->prepare("SELECT e.*, c.company_name FROM employees e LEFT JOIN clients c ON e.client_id = c.id WHERE e.client_id = ? ORDER BY e.created_at DESC LIMIT 6");
    $recentEmployees->execute([$clientId]);
    $recentEmployees = $recentEmployees->fetchAll();

    $clientInfo = db()->prepare("SELECT * FROM clients WHERE id = ?");
    $clientInfo->execute([$clientId]);
    $clientInfo = $clientInfo->fetch();

    $deptData = db()->prepare("SELECT department, COUNT(*) as cnt FROM employees WHERE client_id = ? GROUP BY department ORDER BY cnt DESC");
    $deptData->execute([$clientId]);
    $deptData = $deptData->fetchAll();

} else {
    // Employee: see their own data
    $myEmp = db()->prepare("SELECT * FROM employees WHERE email = ?");
    $myEmp->execute([$currentUser['email']]);
    $myEmployee = $myEmp->fetch();

    $myPayroll = [];
    $myCompliance = null;
    if ($myEmployee) {
        $stmt = db()->prepare("SELECT * FROM payroll WHERE employee_id = ? ORDER BY year DESC, id DESC LIMIT 6");
        $stmt->execute([$myEmployee['id']]);
        $myPayroll = $stmt->fetchAll();

        $stmt = db()->prepare("SELECT * FROM compliance WHERE employee_id = ?");
        $stmt->execute([$myEmployee['id']]);
        $myCompliance = $stmt->fetch();
    }
}

require_once __DIR__ . '/../includes/portal_header.php';
?>

<!-- ==================== ADMIN DASHBOARD ==================== -->
<?php if ($role === 'admin'): ?>
<div class="stat-grid">
    <div class="stat-card-erp">
        <div class="stat-icon-box stat-icon-blue">&#128100;</div>
        <div class="stat-info">
            <div class="stat-val"><?= number_format($totalEmployees) ?></div>
            <div class="stat-lbl">Total Manpower Hired</div>
            <div class="stat-trend up">&#8593; <?= $activeEmployees ?> active</div>
        </div>
    </div>
    <div class="stat-card-erp">
        <div class="stat-icon-box stat-icon-green">&#127970;</div>
        <div class="stat-info">
            <div class="stat-val"><?= number_format($totalClients) ?></div>
            <div class="stat-lbl">Active Clients</div>
            <div class="stat-trend up">All contracts current</div>
        </div>
    </div>
    <div class="stat-card-erp">
        <div class="stat-icon-box stat-icon-amber">&#128176;</div>
        <div class="stat-info">
            <div class="stat-val"><?= $pendingPayroll ?></div>
            <div class="stat-lbl">Pending Payroll</div>
            <div class="stat-trend down">Needs processing</div>
        </div>
    </div>
    <div class="stat-card-erp">
        <div class="stat-icon-box stat-icon-red">&#128220;</div>
        <div class="stat-info">
            <div class="stat-val"><?= $pendingCompliance ?></div>
            <div class="stat-lbl">Compliance Pending</div>
            <div class="stat-trend down">Awaiting verification</div>
        </div>
    </div>
</div>

<div class="grid-2">
    <!-- Client-wise Workforce Distribution -->
    <div class="panel">
        <div class="panel-header">
            <h3>Client-wise Workforce</h3>
        </div>
        <div class="chart-container">
            <div class="bar-chart">
                <?php
                $maxEmp = max(array_column($clientData, 'emp_count') ?: [1]);
                foreach ($clientData as $cd):
                    $heightPct = $cd['emp_count'] > 0 ? ($cd['emp_count'] / $maxEmp) * 180 : 0;
                    $shortName = explode(' ', $cd['company_name'])[0];
                ?>
                    <div class="bar-item">
                        <div class="bar-value"><?= $cd['emp_count'] ?></div>
                        <div class="bar" data-height="<?= $heightPct ?>"></div>
                        <div class="bar-label"><?= e($shortName) ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Department Distribution -->
    <div class="panel">
        <div class="panel-header">
            <h3>Department Distribution</h3>
        </div>
        <div class="panel-body">
            <?php
            $colors = ['#0a2a5e', '#1a4a8e', '#d71921', '#f59e0b', '#2fa84f', '#0dcaf0'];
            $totalDept = array_sum(array_column($deptData, 'cnt'));
            foreach ($deptData as $i => $dept):
                $pct = $totalDept > 0 ? round(($dept['cnt'] / $totalDept) * 100) : 0;
                $color = $colors[$i % count($colors)];
            ?>
                <div style="margin-bottom:16px;">
                    <div style="display:flex;justify-content:space-between;margin-bottom:6px;">
                        <span style="font-size:14px;font-weight:500;color:var(--gray-700);"><?= e($dept['department']) ?></span>
                        <span style="font-size:13px;color:var(--gray-500);"><?= $dept['cnt'] ?> (<?= $pct ?>%)</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" data-width="<?= $pct ?>" style="background:<?= $color ?>"></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Recent Employees + Recent Messages -->
<div class="grid-2">
    <div class="panel">
        <div class="panel-header">
            <h3>Recently Added Employees</h3>
            <a href="<?= APP_URL ?>/app/employees/index.php" class="btn btn-outline btn-sm">View All</a>
        </div>
        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Client</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentEmployees as $emp): ?>
                    <tr>
                        <td><strong><?= e($emp['emp_code']) ?></strong></td>
                        <td><?= e($emp['first_name'] . ' ' . $emp['last_name']) ?></td>
                        <td><?= e($emp['company_name'] ?? '-') ?></td>
                        <td>
                            <?php
                            $badgeClass = ['active' => 'badge-success', 'on_leave' => 'badge-warning', 'terminated' => 'badge-danger', 'inactive' => 'badge-gray'];
                            $cls = $badgeClass[$emp['status']] ?? 'badge-gray';
                            ?>
                            <span class="badge <?= $cls ?>"><span class="badge-dot"></span><?= ucfirst(str_replace('_', ' ', $emp['status'])) ?></span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="panel">
        <div class="panel-header">
            <h3>Recent Contact Messages</h3>
        </div>
        <div class="panel-body">
            <?php if (empty($recentMessages)): ?>
                <div class="empty-state">
                    <div class="empty-icon">&#9993;</div>
                    <p>No new messages</p>
                </div>
            <?php else: ?>
                <?php foreach ($recentMessages as $msg): ?>
                    <div style="padding:14px 0;border-bottom:1px solid var(--gray-100);">
                        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px;">
                            <strong style="font-size:14px;color:var(--gray-800);"><?= e($msg['name']) ?></strong>
                            <?php if (!$msg['is_read']): ?>
                                <span class="badge badge-info" style="font-size:10px;">NEW</span>
                            <?php endif; ?>
                        </div>
                        <div style="font-size:13px;color:var(--gray-500);margin-bottom:4px;"><?= e($msg['company'] ?? 'No company') ?> &middot; <?= e($msg['email']) ?></div>
                        <div style="font-size:13px;color:var(--gray-600);"><?= e(substr($msg['message'], 0, 80)) ?><?= strlen($msg['message']) > 80 ? '...' : '' ?></div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- ==================== HR DASHBOARD ==================== -->
<?php elseif ($role === 'hr'): ?>
<div class="stat-grid">
    <div class="stat-card-erp">
        <div class="stat-icon-box stat-icon-blue">&#128100;</div>
        <div class="stat-info">
            <div class="stat-val"><?= number_format($totalEmployees) ?></div>
            <div class="stat-lbl">Total Employees</div>
            <div class="stat-trend up">&#8593; <?= $activeEmployees ?> active</div>
        </div>
    </div>
    <div class="stat-card-erp">
        <div class="stat-icon-box stat-icon-amber">&#128176;</div>
        <div class="stat-info">
            <div class="stat-val"><?= $pendingPayroll ?></div>
            <div class="stat-lbl">Pending Payroll</div>
            <div class="stat-trend down">Needs processing</div>
        </div>
    </div>
    <div class="stat-card-erp">
        <div class="stat-icon-box stat-icon-red">&#128220;</div>
        <div class="stat-info">
            <div class="stat-val"><?= $pendingCompliance ?></div>
            <div class="stat-lbl">Compliance Pending</div>
            <div class="stat-trend down">Awaiting verification</div>
        </div>
    </div>
    <div class="stat-card-erp">
        <div class="stat-icon-box stat-icon-green">&#127970;</div>
        <div class="stat-info">
            <div class="stat-val"><?= number_format($totalClients) ?></div>
            <div class="stat-lbl">Active Clients</div>
            <div class="stat-trend up">All on track</div>
        </div>
    </div>
</div>

<div class="grid-2">
    <div class="panel">
        <div class="panel-header">
            <h3>Recently Added Employees</h3>
            <a href="<?= APP_URL ?>/app/employees/index.php" class="btn btn-outline btn-sm">View All</a>
        </div>
        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead>
                    <tr><th>Code</th><th>Name</th><th>Department</th><th>Designation</th><th>Status</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($recentEmployees as $emp): ?>
                    <tr>
                        <td><strong><?= e($emp['emp_code']) ?></strong></td>
                        <td><?= e($emp['first_name'] . ' ' . $emp['last_name']) ?></td>
                        <td><?= e($emp['department']) ?></td>
                        <td><?= e($emp['designation']) ?></td>
                        <td>
                            <?php $cls = ['active'=>'badge-success','on_leave'=>'badge-warning','terminated'=>'badge-danger','inactive'=>'badge-gray'][$emp['status']] ?? 'badge-gray'; ?>
                            <span class="badge <?= $cls ?>"><span class="badge-dot"></span><?= ucfirst(str_replace('_', ' ', $emp['status'])) ?></span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="panel">
        <div class="panel-header"><h3>Department Distribution</h3></div>
        <div class="panel-body">
            <?php
            $colors = ['#0a2a5e', '#1a4a8e', '#d71921', '#f59e0b', '#2fa84f'];
            $totalDept = array_sum(array_column($deptData, 'cnt'));
            foreach ($deptData as $i => $dept):
                $pct = $totalDept > 0 ? round(($dept['cnt'] / $totalDept) * 100) : 0;
                $color = $colors[$i % count($colors)];
            ?>
                <div style="margin-bottom:16px;">
                    <div style="display:flex;justify-content:space-between;margin-bottom:6px;">
                        <span style="font-size:14px;font-weight:500;"><?= e($dept['department']) ?></span>
                        <span style="font-size:13px;color:var(--gray-500);"><?= $dept['cnt'] ?> (<?= $pct ?>%)</span>
                    </div>
                    <div class="progress-bar"><div class="progress-fill" data-width="<?= $pct ?>" style="background:<?= $color ?>"></div></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- ==================== CLIENT DASHBOARD (Tata Motors) ==================== -->
<?php elseif ($role === 'client'): ?>
<div class="stat-grid">
    <div class="stat-card-erp">
        <div class="stat-icon-box stat-icon-blue">&#128100;</div>
        <div class="stat-info">
            <div class="stat-val"><?= number_format($totalEmployees) ?></div>
            <div class="stat-lbl">Total Manpower Deployed</div>
            <div class="stat-trend up">&#8593; <?= $activeEmployees ?> active</div>
        </div>
    </div>
    <div class="stat-card-erp">
        <div class="stat-icon-box stat-icon-green">&#9989;</div>
        <div class="stat-info">
            <div class="stat-val"><?= $activeEmployees ?></div>
            <div class="stat-lbl">Active Workers</div>
            <div class="stat-trend up">Currently deployed</div>
        </div>
    </div>
    <div class="stat-card-erp">
        <div class="stat-icon-box stat-icon-amber">&#128197;</div>
        <div class="stat-info">
            <div class="stat-val"><?= $onLeaveEmployees ?? 0 ?></div>
            <div class="stat-lbl">On Leave</div>
            <div class="stat-trend down">Temporarily absent</div>
        </div>
    </div>
    <div class="stat-card-erp">
        <div class="stat-icon-box stat-icon-red">&#128176;</div>
        <div class="stat-info">
            <div class="stat-val"><?= $pendingPayroll ?></div>
            <div class="stat-lbl">Pending Payroll</div>
            <div class="stat-trend down">Awaiting processing</div>
        </div>
    </div>
</div>

<!-- Client info banner -->
<?php if (isset($clientInfo) && $clientInfo): ?>
<div class="panel" style="background:linear-gradient(135deg,var(--blue),var(--blue-light));border:none;color:white;">
    <div class="panel-body" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:16px;">
        <div>
            <h3 style="color:white;font-size:22px;margin-bottom:4px;"><?= e($clientInfo['company_name']) ?></h3>
            <p style="opacity:0.8;font-size:14px;"><?= e($clientInfo['address']) ?></p>
        </div>
        <div style="display:flex;gap:32px;flex-wrap:wrap;">
            <div>
                <div style="font-size:12px;opacity:0.7;">Contract Start</div>
                <div style="font-size:16px;font-weight:600;"><?= format_date($clientInfo['contract_start']) ?></div>
            </div>
            <div>
                <div style="font-size:12px;opacity:0.7;">Contract End</div>
                <div style="font-size:16px;font-weight:600;"><?= format_date($clientInfo['contract_end']) ?></div>
            </div>
            <div>
                <div style="font-size:12px;opacity:0.7;">Contact</div>
                <div style="font-size:16px;font-weight:600;"><?= e($clientInfo['contact_person']) ?></div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="grid-2">
    <div class="panel">
        <div class="panel-header">
            <h3>Deployed Workforce</h3>
            <a href="<?= APP_URL ?>/app/employees/index.php" class="btn btn-outline btn-sm">View All</a>
        </div>
        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead><tr><th>Code</th><th>Name</th><th>Department</th><th>Designation</th><th>Status</th></tr></thead>
                <tbody>
                    <?php foreach ($recentEmployees as $emp): ?>
                    <tr>
                        <td><strong><?= e($emp['emp_code']) ?></strong></td>
                        <td><?= e($emp['first_name'] . ' ' . $emp['last_name']) ?></td>
                        <td><?= e($emp['department']) ?></td>
                        <td><?= e($emp['designation']) ?></td>
                        <td>
                            <?php $cls = ['active'=>'badge-success','on_leave'=>'badge-warning','terminated'=>'badge-danger','inactive'=>'badge-gray'][$emp['status']] ?? 'badge-gray'; ?>
                            <span class="badge <?= $cls ?>"><span class="badge-dot"></span><?= ucfirst(str_replace('_', ' ', $emp['status'])) ?></span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="panel">
        <div class="panel-header"><h3>Department Breakdown</h3></div>
        <div class="panel-body">
            <?php
            $colors = ['#0a2a5e', '#1a4a8e', '#d71921', '#f59e0b', '#2fa84f'];
            $totalDept = array_sum(array_column($deptData, 'cnt'));
            foreach ($deptData as $i => $dept):
                $pct = $totalDept > 0 ? round(($dept['cnt'] / $totalDept) * 100) : 0;
                $color = $colors[$i % count($colors)];
            ?>
                <div style="margin-bottom:16px;">
                    <div style="display:flex;justify-content:space-between;margin-bottom:6px;">
                        <span style="font-size:14px;font-weight:500;"><?= e($dept['department']) ?></span>
                        <span style="font-size:13px;color:var(--gray-500);"><?= $dept['cnt'] ?> workers (<?= $pct ?>%)</span>
                    </div>
                    <div class="progress-bar"><div class="progress-fill" data-width="<?= $pct ?>" style="background:<?= $color ?>"></div></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- ==================== EMPLOYEE DASHBOARD ==================== -->
<?php elseif ($role === 'employee'): ?>
<?php if ($myEmployee): ?>
<div class="stat-grid" style="grid-template-columns:repeat(3,1fr);">
    <div class="stat-card-erp">
        <div class="stat-icon-box stat-icon-blue">&#128100;</div>
        <div class="stat-info">
            <div class="stat-val"><?= e($myEmployee['emp_code']) ?></div>
            <div class="stat-lbl">Employee Code</div>
        </div>
    </div>
    <div class="stat-card-erp">
        <div class="stat-icon-box stat-icon-green">&#9989;</div>
        <div class="stat-info">
            <div class="stat-val" style="font-size:20px;"><?= e(ucfirst(str_replace('_', ' ', $myEmployee['status']))) ?></div>
            <div class="stat-lbl">Employment Status</div>
        </div>
    </div>
    <div class="stat-card-erp">
        <div class="stat-icon-box stat-icon-amber">&#128176;</div>
        <div class="stat-info">
            <div class="stat-val"><?= format_inr($myEmployee['salary']) ?></div>
            <div class="stat-lbl">Monthly Salary</div>
        </div>
    </div>
</div>

<div class="grid-2">
    <!-- My Profile Summary -->
    <div class="panel">
        <div class="panel-header"><h3>My Employment Details</h3></div>
        <div class="panel-body">
            <table class="data-table" style="border:none;">
                <tbody>
                    <tr><td style="font-weight:600;width:40%;">Full Name</td><td><?= e($myEmployee['first_name'] . ' ' . $myEmployee['last_name']) ?></td></tr>
                    <tr><td style="font-weight:600;">Department</td><td><?= e($myEmployee['department']) ?></td></tr>
                    <tr><td style="font-weight:600;">Designation</td><td><?= e($myEmployee['designation']) ?></td></tr>
                    <tr><td style="font-weight:600;">Shift</td><td><?= e($myEmployee['shift']) ?></td></tr>
                    <tr><td style="font-weight:600;">Hire Date</td><td><?= format_date($myEmployee['hire_date']) ?></td></tr>
                    <tr><td style="font-weight:600;">Phone</td><td><?= e($myEmployee['phone']) ?></td></tr>
                    <tr><td style="font-weight:600;">Address</td><td><?= e($myEmployee['address']) ?></td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Compliance Status -->
    <div class="panel">
        <div class="panel-header"><h3>Compliance Status</h3></div>
        <div class="panel-body">
            <?php if ($myCompliance): ?>
            <table class="data-table" style="border:none;">
                <tbody>
                    <tr><td style="font-weight:600;">PF Number</td><td><?= e($myCompliance['pf_number'] ?? '-') ?></td></tr>
                    <tr><td style="font-weight:600;">ESI Number</td><td><?= e($myCompliance['esi_number'] ?? '-') ?></td></tr>
                    <tr><td style="font-weight:600;">PAN Number</td><td><?= e($myCompliance['pan_number'] ?? '-') ?></td></tr>
                    <tr><td style="font-weight:600;">Bank Account</td><td><?= e($myCompliance['bank_account'] ?? '-') ?></td></tr>
                    <tr><td style="font-weight:600;">IFSC Code</td><td><?= e($myCompliance['ifsc_code'] ?? '-') ?></td></tr>
                    <tr><td style="font-weight:600;">Contract Type</td><td><span class="badge badge-info"><?= ucfirst(e($myCompliance['contract_type'])) ?></span></td></tr>
                    <tr><td style="font-weight:600;">Verification</td><td>
                        <?php
                        $vClass = ['verified'=>'badge-success','pending'=>'badge-warning','rejected'=>'badge-danger'][$myCompliance['verification_status']] ?? 'badge-gray';
                        ?>
                        <span class="badge <?= $vClass ?>"><?= ucfirst(e($myCompliance['verification_status'])) ?></span>
                    </td></tr>
                </tbody>
            </table>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-icon">&#128220;</div>
                    <p>Compliance records not yet set up.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Recent Payslips -->
<div class="panel">
    <div class="panel-header">
        <h3>Recent Payslips</h3>
        <a href="<?= APP_URL ?>/app/payroll/payslips.php" class="btn btn-outline btn-sm">View All</a>
    </div>
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead><tr><th>Month</th><th>Year</th><th>Net Pay</th><th>Status</th></tr></thead>
            <tbody>
                <?php foreach ($myPayroll as $p): ?>
                <tr>
                    <td><?= e($p['month']) ?></td>
                    <td><?= e($p['year']) ?></td>
                    <td><strong><?= format_inr($p['net_pay']) ?></strong></td>
                    <td>
                        <?php $cls = ['paid'=>'badge-success','processed'=>'badge-info','pending'=>'badge-warning'][$p['status']] ?? 'badge-gray'; ?>
                        <span class="badge <?= $cls ?>"><?= ucfirst(e($p['status'])) ?></span>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($myPayroll)): ?>
                <tr><td colspan="4" style="text-align:center;padding:32px;color:var(--gray-500);">No payslips available yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php else: ?>
<div class="empty-state" style="margin-top:60px;">
    <div class="empty-icon">&#128100;</div>
    <h4>No Employee Record Found</h4>
    <p>Your employee profile has not been linked yet. Please contact HR.</p>
</div>
<?php endif; ?>

<?php endif; ?>

<?php require_once __DIR__ . '/../includes/portal_footer.php'; ?>
