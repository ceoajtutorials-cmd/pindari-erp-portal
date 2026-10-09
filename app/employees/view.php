<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/auth.php';

require_role('admin', 'hr', 'client');
$pageTitle = 'Employee Details';
$currentPageFile = 'employees/index.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) { redirect(APP_URL . '/app/employees/index.php'); }

$stmt = db()->prepare("SELECT e.*, c.company_name FROM employees e LEFT JOIN clients c ON e.client_id = c.id WHERE e.id = ?");
$stmt->execute([$id]);
$emp = $stmt->fetch();

if (!$emp) { set_flash('error', 'Employee not found.'); redirect(APP_URL . '/app/employees/index.php'); }

// Clients can only see their own employees
if ($currentUser['role'] === 'client' && $emp['client_id'] != $currentUser['client_id']) {
    set_flash('error', 'You do not have access to this employee.');
    redirect(APP_URL . '/app/employees/index.php');
}

// Get payroll and compliance
$payroll = db()->prepare("SELECT * FROM payroll WHERE employee_id = ? ORDER BY year DESC, id DESC");
$payroll->execute([$id]);
$payroll = $payroll->fetchAll();

$compliance = db()->prepare("SELECT * FROM compliance WHERE employee_id = ?");
$compliance->execute([$id]);
$compliance = $compliance->fetch();

$placements = db()->prepare("SELECT p.*, c.company_name FROM placements p LEFT JOIN clients c ON p.client_id = c.id WHERE p.employee_id = ?");
$placements->execute([$id]);
$placements = $placements->fetchAll();

require_once __DIR__ . '/../../includes/portal_header.php';
?>

<div class="panel" style="margin-bottom:24px;">
    <div class="panel-header">
        <h3>Employee: <?= e($emp['emp_code']) ?></h3>
        <div class="panel-actions">
            <?php if (in_array($currentUser['role'], ['admin', 'hr'])): ?>
            <a href="<?= APP_URL ?>/app/employees/edit.php?id=<?= $emp['id'] ?>" class="btn btn-outline btn-sm">&#9998; Edit</a>
            <?php endif; ?>
            <a href="<?= APP_URL ?>/app/employees/index.php" class="btn btn-outline btn-sm">&larr; Back</a>
        </div>
    </div>
    <div class="panel-body" style="display:flex;align-items:center;gap:24px;flex-wrap:wrap;">
        <div class="user-avatar" style="width:72px;height:72px;font-size:28px;background:var(--blue);">
            <?= strtoupper(substr($emp['first_name'], 0, 1) . substr($emp['last_name'], 0, 1)) ?>
        </div>
        <div style="flex:1;min-width:200px;">
            <h2 style="font-size:24px;font-weight:800;color:var(--gray-800);"><?= e($emp['first_name'] . ' ' . $emp['last_name']) ?></h2>
            <p style="color:var(--gray-500);font-size:15px;"><?= e($emp['designation']) ?> &middot; <?= e($emp['department']) ?></p>
            <p style="color:var(--gray-500);font-size:14px;margin-top:4px;"><?= e($emp['company_name'] ?? 'Unassigned') ?></p>
        </div>
        <div style="text-align:right;">
            <?php $cls = ['active'=>'badge-success','on_leave'=>'badge-warning','terminated'=>'badge-danger','inactive'=>'badge-gray'][$emp['status']] ?? 'badge-gray'; ?>
            <span class="badge <?= $cls ?>" style="font-size:14px;padding:8px 16px;"><span class="badge-dot"></span><?= ucfirst(str_replace('_', ' ', $emp['status'])) ?></span>
            <div style="font-size:20px;font-weight:700;color:var(--blue);margin-top:8px;"><?= format_inr($emp['salary']) ?>/mo</div>
        </div>
    </div>
</div>

<div class="grid-2">
    <div class="panel">
        <div class="panel-header"><h3>Personal & Employment Info</h3></div>
        <div class="panel-body">
            <table class="data-table" style="border:none;">
                <tbody>
                    <tr><td style="font-weight:600;width:40%;">Employee Code</td><td><?= e($emp['emp_code']) ?></td></tr>
                    <tr><td style="font-weight:600;">Email</td><td><?= e($emp['email'] ?: '-') ?></td></tr>
                    <tr><td style="font-weight:600;">Phone</td><td><?= e($emp['phone'] ?: '-') ?></td></tr>
                    <tr><td style="font-weight:600;">Department</td><td><?= e($emp['department']) ?></td></tr>
                    <tr><td style="font-weight:600;">Designation</td><td><?= e($emp['designation']) ?></td></tr>
                    <tr><td style="font-weight:600;">Shift</td><td><?= e($emp['shift']) ?></td></tr>
                    <tr><td style="font-weight:600;">Hire Date</td><td><?= format_date($emp['hire_date']) ?></td></tr>
                    <tr><td style="font-weight:600;">Address</td><td><?= e($emp['address'] ?: '-') ?></td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="panel">
        <div class="panel-header"><h3>Compliance Details</h3></div>
        <div class="panel-body">
            <?php if ($compliance): ?>
            <table class="data-table" style="border:none;">
                <tbody>
                    <tr><td style="font-weight:600;">PF Number</td><td><?= e($compliance['pf_number'] ?: '-') ?></td></tr>
                    <tr><td style="font-weight:600;">ESI Number</td><td><?= e($compliance['esi_number'] ?: '-') ?></td></tr>
                    <tr><td style="font-weight:600;">PAN</td><td><?= e($compliance['pan_number'] ?: '-') ?></td></tr>
                    <tr><td style="font-weight:600;">Bank Account</td><td><?= e($compliance['bank_account'] ?: '-') ?></td></tr>
                    <tr><td style="font-weight:600;">IFSC</td><td><?= e($compliance['ifsc_code'] ?: '-') ?></td></tr>
                    <tr><td style="font-weight:600;">Contract Type</td><td><span class="badge badge-info"><?= ucfirst(e($compliance['contract_type'])) ?></span></td></tr>
                    <tr><td style="font-weight:600;">Verification</td><td>
                        <?php $vClass = ['verified'=>'badge-success','pending'=>'badge-warning','rejected'=>'badge-danger'][$compliance['verification_status']] ?? 'badge-gray'; ?>
                        <span class="badge <?= $vClass ?>"><?= ucfirst(e($compliance['verification_status'])) ?></span>
                    </td></tr>
                </tbody>
            </table>
            <?php else: ?>
                <div class="empty-state"><div class="empty-icon">&#128220;</div><p>No compliance records yet.</p></div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="panel">
    <div class="panel-header"><h3>Payroll History</h3></div>
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead><tr><th>Month</th><th>Year</th><th>Basic</th><th>HRA</th><th>Allowances</th><th>Deductions</th><th>Net Pay</th><th>Status</th></tr></thead>
            <tbody>
                <?php foreach ($payroll as $p): ?>
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
                <?php if (empty($payroll)): ?>
                <tr><td colspan="8" style="text-align:center;padding:32px;color:var(--gray-500);">No payroll records.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="panel">
    <div class="panel-header"><h3>Placement History</h3></div>
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead><tr><th>Client</th><th>Position</th><th>Start Date</th><th>End Date</th><th>Status</th></tr></thead>
            <tbody>
                <?php foreach ($placements as $pl): ?>
                <tr>
                    <td><?= e($pl['company_name']) ?></td>
                    <td><?= e($pl['position']) ?></td>
                    <td><?= format_date($pl['start_date']) ?></td>
                    <td><?= format_date($pl['end_date']) ?></td>
                    <td><?php $cls = ['active'=>'badge-success','completed'=>'badge-info','terminated'=>'badge-danger'][$pl['status']] ?? 'badge-gray'; ?>
                        <span class="badge <?= $cls ?>"><?= ucfirst(e($pl['status'])) ?></span></td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($placements)): ?>
                <tr><td colspan="5" style="text-align:center;padding:32px;color:var(--gray-500);">No placement records.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/portal_footer.php'; ?>
