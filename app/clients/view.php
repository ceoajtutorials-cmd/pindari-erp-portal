<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/auth.php';

require_role('admin');
$pageTitle = 'Client Details';
$currentPageFile = 'clients/index.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) redirect(APP_URL . '/app/clients/index.php');

$stmt = db()->prepare("SELECT * FROM clients WHERE id = ?");
$stmt->execute([$id]);
$client = $stmt->fetch();
if (!$client) { set_flash('error', 'Client not found.'); redirect(APP_URL . '/app/clients/index.php'); }

$employees = db()->prepare("SELECT * FROM employees WHERE client_id = ? ORDER BY first_name");
$employees->execute([$id]);
$employees = $employees->fetchAll();

$payrollSummary = db()->prepare("SELECT COUNT(*) as cnt, COALESCE(SUM(p.net_pay),0) as total FROM payroll p JOIN employees e ON p.employee_id = e.id WHERE e.client_id = ?");
$payrollSummary->execute([$id]);
$payrollSummary = $payrollSummary->fetch();

require_once __DIR__ . '/../../includes/portal_header.php';
?>

<div class="panel" style="margin-bottom:24px;">
    <div class="panel-header">
        <h3><?= e($client['company_name']) ?></h3>
        <div class="panel-actions">
            <a href="<?= APP_URL ?>/app/clients/edit.php?id=<?= $id ?>" class="btn btn-outline btn-sm">&#9998; Edit</a>
            <a href="<?= APP_URL ?>/app/clients/index.php" class="btn btn-outline btn-sm">&larr; Back</a>
        </div>
    </div>
    <div class="panel-body" style="display:flex;align-items:center;gap:24px;flex-wrap:wrap;">
        <div class="stat-icon-box stat-icon-blue" style="width:64px;height:64px;font-size:28px;"><?= strtoupper(substr($client['company_name'], 0, 1)) ?></div>
        <div style="flex:1;min-width:200px;">
            <h2 style="font-size:22px;font-weight:800;color:var(--gray-800);"><?= e($client['company_name']) ?></h2>
            <p style="color:var(--gray-500);font-size:15px;"><?= e($client['address'] ?: '') ?></p>
            <?php $cls = ['active'=>'badge-success','inactive'=>'badge-gray','pending'=>'badge-warning'][$client['status']] ?? 'badge-gray'; ?>
            <span class="badge <?= $cls ?>" style="margin-top:8px;"><span class="badge-dot"></span><?= ucfirst(e($client['status'])) ?></span>
        </div>
    </div>
</div>

<div class="stat-grid">
    <div class="stat-card-erp">
        <div class="stat-icon-box stat-icon-blue">&#128100;</div>
        <div class="stat-info"><div class="stat-val"><?= count($employees) ?></div><div class="stat-lbl">Total Workers</div></div>
    </div>
    <div class="stat-card-erp">
        <div class="stat-icon-box stat-icon-green">&#9989;</div>
        <div class="stat-info"><div class="stat-val"><?= count(array_filter($employees, fn($e) => $e['status'] === 'active')) ?></div><div class="stat-lbl">Active</div></div>
    </div>
    <div class="stat-card-erp">
        <div class="stat-icon-box stat-icon-amber">&#128176;</div>
        <div class="stat-info"><div class="stat-val"><?= format_inr($payrollSummary['total']) ?></div><div class="stat-lbl">Total Payroll Paid</div></div>
    </div>
    <div class="stat-card-erp">
        <div class="stat-icon-box stat-icon-red">&#128197;</div>
        <div class="stat-info"><div class="stat-val"><?= format_date($client['contract_end']) ?></div><div class="stat-lbl">Contract Ends</div></div>
    </div>
</div>

<div class="panel">
    <div class="panel-header"><h3>Contact Information</h3></div>
    <div class="panel-body">
        <table class="data-table" style="border:none;">
            <tbody>
                <tr><td style="font-weight:600;width:30%;">Contact Person</td><td><?= e($client['contact_person'] ?: '-') ?></td></tr>
                <tr><td style="font-weight:600;">Email</td><td><?= e($client['email'] ?: '-') ?></td></tr>
                <tr><td style="font-weight:600;">Phone</td><td><?= e($client['phone'] ?: '-') ?></td></tr>
                <tr><td style="font-weight:600;">Address</td><td><?= e($client['address'] ?: '-') ?></td></tr>
                <tr><td style="font-weight:600;">Contract Start</td><td><?= format_date($client['contract_start']) ?></td></tr>
                <tr><td style="font-weight:600;">Contract End</td><td><?= format_date($client['contract_end']) ?></td></tr>
            </tbody>
        </table>
    </div>
</div>

<div class="panel">
    <div class="panel-header"><h3>Deployed Employees</h3></div>
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead><tr><th>Code</th><th>Name</th><th>Department</th><th>Designation</th><th>Salary</th><th>Status</th></tr></thead>
            <tbody>
                <?php foreach ($employees as $emp): ?>
                <tr>
                    <td><strong><?= e($emp['emp_code']) ?></strong></td>
                    <td><?= e($emp['first_name'] . ' ' . $emp['last_name']) ?></td>
                    <td><?= e($emp['department']) ?></td>
                    <td><?= e($emp['designation']) ?></td>
                    <td><?= format_inr($emp['salary']) ?></td>
                    <td><?php $cls = ['active'=>'badge-success','on_leave'=>'badge-warning','terminated'=>'badge-danger','inactive'=>'badge-gray'][$emp['status']] ?? 'badge-gray'; ?>
                        <span class="badge <?= $cls ?>"><?= ucfirst(str_replace('_', ' ', $emp['status'])) ?></span></td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($employees)): ?>
                <tr><td colspan="6" style="text-align:center;padding:32px;color:var(--gray-500);">No employees deployed.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/portal_footer.php'; ?>
