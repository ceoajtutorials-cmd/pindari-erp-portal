<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/auth.php';

$pageTitle = 'My Profile';
$currentPageFile = 'employees/profile.php';

// Employee sees their own record based on email
$stmt = db()->prepare("SELECT e.*, c.company_name FROM employees e LEFT JOIN clients c ON e.client_id = c.id WHERE e.email = ?");
$stmt->execute([$currentUser['email']]);
$emp = $stmt->fetch();

if (!$emp) {
    // Fallback: link by user client_id
    $stmt = db()->prepare("SELECT e.*, c.company_name FROM employees e LEFT JOIN clients c ON e.client_id = c.id WHERE e.client_id = ? ORDER BY e.id LIMIT 1");
    $stmt->execute([$currentUser['client_id'] ?? 0]);
    $emp = $stmt->fetch();
}

require_once __DIR__ . '/../../includes/portal_header.php';
?>

<?php if ($emp): ?>
<div class="panel" style="margin-bottom:24px;">
    <div class="panel-body" style="display:flex;align-items:center;gap:24px;flex-wrap:wrap;">
        <div class="user-avatar" style="width:72px;height:72px;font-size:28px;background:var(--blue);">
            <?= strtoupper(substr($emp['first_name'], 0, 1) . substr($emp['last_name'], 0, 1)) ?>
        </div>
        <div style="flex:1;min-width:200px;">
            <h2 style="font-size:24px;font-weight:800;color:var(--gray-800);"><?= e($emp['first_name'] . ' ' . $emp['last_name']) ?></h2>
            <p style="color:var(--gray-500);font-size:15px;"><?= e($emp['designation']) ?> &middot; <?= e($emp['department']) ?></p>
            <p style="color:var(--gray-500);font-size:14px;margin-top:4px;"><?= e($emp['company_name'] ?? '') ?></p>
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
        <div class="panel-header"><h3>Employment Details</h3></div>
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
            <?php
            $comp = db()->prepare("SELECT * FROM compliance WHERE employee_id = ?");
            $comp->execute([$emp['id']]);
            $comp = $comp->fetch();
            ?>
            <?php if ($comp): ?>
            <table class="data-table" style="border:none;">
                <tbody>
                    <tr><td style="font-weight:600;">PF Number</td><td><?= e($comp['pf_number'] ?: '-') ?></td></tr>
                    <tr><td style="font-weight:600;">ESI Number</td><td><?= e($comp['esi_number'] ?: '-') ?></td></tr>
                    <tr><td style="font-weight:600;">PAN</td><td><?= e($comp['pan_number'] ?: '-') ?></td></tr>
                    <tr><td style="font-weight:600;">Bank Account</td><td><?= e($comp['bank_account'] ?: '-') ?></td></tr>
                    <tr><td style="font-weight:600;">IFSC</td><td><?= e($comp['ifsc_code'] ?: '-') ?></td></tr>
                    <tr><td style="font-weight:600;">Contract Type</td><td><span class="badge badge-info"><?= ucfirst(e($comp['contract_type'])) ?></span></td></tr>
                    <tr><td style="font-weight:600;">Verification</td><td>
                        <?php $vClass = ['verified'=>'badge-success','pending'=>'badge-warning','rejected'=>'badge-danger'][$comp['verification_status']] ?? 'badge-gray'; ?>
                        <span class="badge <?= $vClass ?>"><?= ucfirst(e($comp['verification_status'])) ?></span>
                    </td></tr>
                </tbody>
            </table>
            <?php else: ?>
                <div class="empty-state"><div class="empty-icon">&#128220;</div><p>No compliance records yet.</p></div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php else: ?>
<div class="empty-state" style="margin-top:60px;">
    <div class="empty-icon">&#128100;</div>
    <h4>No Employee Record Found</h4>
    <p>Your employee profile has not been linked yet. Please contact HR.</p>
</div>
<?php endif; ?>

<?php require_once __DIR__ . '/../../includes/portal_footer.php'; ?>
