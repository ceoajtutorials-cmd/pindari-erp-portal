<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/auth.php';

require_role('admin', 'hr', 'client');
$pageTitle = 'Payroll';
$currentPageFile = 'payroll/index.php';

$clientId = $currentUser['client_id'];
$statusFilter = trim($_GET['status'] ?? '');
$monthFilter = trim($_GET['month'] ?? '');

$where = [];
$params = [];

if ($currentUser['role'] === 'client') {
    $where[] = "e.client_id = ?";
    $params[] = $clientId;
}
if ($statusFilter) { $where[] = "p.status = ?"; $params[] = $statusFilter; }
if ($monthFilter) { $where[] = "p.month = ?"; $params[] = $monthFilter; }

$whereSql = $where ? "WHERE " . implode(" AND ", $where) : "";
$sql = "SELECT p.*, e.emp_code, e.first_name, e.last_name, e.client_id, c.company_name 
        FROM payroll p 
        JOIN employees e ON p.employee_id = e.id 
        LEFT JOIN clients c ON e.client_id = c.id 
        $whereSql ORDER BY p.year DESC, p.id DESC";
$stmt = db()->prepare($sql);
$stmt->execute($params);
$payrolls = $stmt->fetchAll();

// Summary
$totalNet = array_sum(array_map(fn($p) => (float)$p['net_pay'], $payrolls));
$paidCount = count(array_filter($payrolls, fn($p) => $p['status'] === 'paid'));
$pendingCount = count(array_filter($payrolls, fn($p) => $p['status'] === 'pending'));
$processedCount = count(array_filter($payrolls, fn($p) => $p['status'] === 'processed'));

$canManage = in_array($currentUser['role'], ['admin', 'hr']);

require_once __DIR__ . '/../../includes/portal_header.php';
?>

<div class="stat-grid">
    <div class="stat-card-erp"><div class="stat-icon-box stat-icon-blue">&#128176;</div><div class="stat-info"><div class="stat-val"><?= count($payrolls) ?></div><div class="stat-lbl">Total Records</div></div></div>
    <div class="stat-card-erp"><div class="stat-icon-box stat-icon-green">&#9989;</div><div class="stat-info"><div class="stat-val"><?= $paidCount ?></div><div class="stat-lbl">Paid</div></div></div>
    <div class="stat-card-erp"><div class="stat-icon-box stat-icon-amber">&#9203;</div><div class="stat-info"><div class="stat-val"><?= $pendingCount ?></div><div class="stat-lbl">Pending</div></div></div>
    <div class="stat-card-erp"><div class="stat-icon-box stat-icon-red">&#128181;</div><div class="stat-info"><div class="stat-val"><?= format_inr($totalNet) ?></div><div class="stat-lbl">Total Net Pay</div></div></div>
</div>

<div class="panel">
    <div class="panel-header">
        <h3>Payroll Records</h3>
        <?php if ($canManage): ?>
        <a href="<?= APP_URL ?>/app/payroll/add.php" class="btn btn-primary btn-sm">+ Add Payroll</a>
        <?php endif; ?>
    </div>
    <div style="padding:20px 24px;border-bottom:1px solid var(--gray-200);">
        <form method="GET" class="filter-bar">
            <select name="status">
                <option value="">All Status</option>
                <option value="pending" <?= $statusFilter==='pending'?'selected':'' ?>>Pending</option>
                <option value="processed" <?= $statusFilter==='processed'?'selected':'' ?>>Processed</option>
                <option value="paid" <?= $statusFilter==='paid'?'selected':'' ?>>Paid</option>
            </select>
            <select name="month">
                <option value="">All Months</option>
                <?php $months = ['January','February','March','April','May','June','July','August','September','October','November','December']; ?>
                <?php foreach ($months as $m): ?>
                <option value="<?= $m ?>" <?= $monthFilter===$m?'selected':'' ?>><?= $m ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-outline btn-sm">Filter</button>
            <?php if ($statusFilter || $monthFilter): ?>
                <a href="<?= APP_URL ?>/app/payroll/index.php" class="btn btn-outline btn-sm">Clear</a>
            <?php endif; ?>
        </form>
    </div>
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Code</th><th>Name</th>
                    <?php if ($currentUser['role'] !== 'client'): ?><th class="hide-mobile">Client</th><?php endif; ?>
                    <th>Month</th><th>Year</th><th>Net Pay</th><th>Status</th>
                    <?php if ($canManage): ?><th class="text-center">Actions</th><?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($payrolls as $p): ?>
                <tr>
                    <td><strong><?= e($p['emp_code']) ?></strong></td>
                    <td><?= e($p['first_name'] . ' ' . $p['last_name']) ?></td>
                    <?php if ($currentUser['role'] !== 'client'): ?><td class="hide-mobile"><?= e($p['company_name'] ?? '-') ?></td><?php endif; ?>
                    <td><?= e($p['month']) ?></td>
                    <td><?= e($p['year']) ?></td>
                    <td><strong><?= format_inr($p['net_pay']) ?></strong></td>
                    <td><?php $cls = ['paid'=>'badge-success','processed'=>'badge-info','pending'=>'badge-warning'][$p['status']] ?? 'badge-gray'; ?>
                        <span class="badge <?= $cls ?>"><?= ucfirst(e($p['status'])) ?></span></td>
                    <?php if ($canManage): ?>
                    <td class="text-center">
                        <a href="<?= APP_URL ?>/app/payroll/edit.php?id=<?= $p['id'] ?>" class="btn-icon" title="Edit">&#9998;</a>
                        <?php if ($p['status'] !== 'paid'): ?>
                        <a href="<?= APP_URL ?>/app/payroll/process.php?id=<?= $p['id'] ?>" class="btn-icon" title="Mark Paid" data-confirm="Mark this payroll as paid?">&#128176;</a>
                        <?php endif; ?>
                    </td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($payrolls)): ?>
                <tr><td colspan="8" style="text-align:center;padding:40px;color:var(--gray-500);">No payroll records found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/portal_footer.php'; ?>
