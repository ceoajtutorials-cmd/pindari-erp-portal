<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/auth.php';

$pageTitle = 'My Payslips';
$currentPageFile = 'payroll/payslips.php';

// Employee sees their own payslips
$stmt = db()->prepare("SELECT id FROM employees WHERE email = ?");
$stmt->execute([$currentUser['email']]);
$emp = $stmt->fetch();

$payrolls = [];
if ($emp) {
    $stmt = db()->prepare("SELECT * FROM payroll WHERE employee_id = ? ORDER BY year DESC, id DESC");
    $stmt->execute([$emp['id']]);
    $payrolls = $stmt->fetchAll();
}

require_once __DIR__ . '/../../includes/portal_header.php';
?>

<div class="panel">
    <div class="panel-header"><h3>My Payslips</h3></div>
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead><tr><th>Month</th><th>Year</th><th>Basic</th><th>HRA</th><th>Allowances</th><th>Deductions</th><th>Net Pay</th><th>Status</th></tr></thead>
            <tbody>
                <?php foreach ($payrolls as $p): ?>
                <tr>
                    <td><?= e($p['month']) ?></td><td><?= e($p['year']) ?></td>
                    <td><?= format_inr($p['basic_salary']) ?></td>
                    <td><?= format_inr($p['hra']) ?></td>
                    <td><?= format_inr($p['allowances']) ?></td>
                    <td><?= format_inr($p['deductions'] + $p['pf'] + $p['esi']) ?></td>
                    <td><strong><?= format_inr($p['net_pay']) ?></strong></td>
                    <td><?php $cls = ['paid'=>'badge-success','processed'=>'badge-info','pending'=>'badge-warning'][$p['status']] ?? 'badge-gray'; ?>
                        <span class="badge <?= $cls ?>"><?= ucfirst(e($p['status'])) ?></span></td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($payrolls)): ?>
                <tr><td colspan="8" style="text-align:center;padding:40px;color:var(--gray-500);">No payslips available yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/portal_footer.php'; ?>
