<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/auth.php';

require_role('admin', 'hr');
$pageTitle = 'Edit Compliance';
$currentPageFile = 'compliance/index.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) redirect(APP_URL . '/app/compliance/index.php');

$stmt = db()->prepare("SELECT comp.*, e.emp_code, e.first_name, e.last_name FROM compliance comp JOIN employees e ON comp.employee_id = e.id WHERE comp.id = ?");
$stmt->execute([$id]);
$rec = $stmt->fetch();
if (!$rec) { set_flash('error', 'Compliance record not found.'); redirect(APP_URL . '/app/compliance/index.php'); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pfNumber = trim($_POST['pf_number'] ?? '');
    $esiNumber = trim($_POST['esi_number'] ?? '');
    $panNumber = trim($_POST['pan_number'] ?? '');
    $aadhaar = trim($_POST['aadhaar'] ?? '');
    $bankAccount = trim($_POST['bank_account'] ?? '');
    $ifscCode = trim($_POST['ifsc_code'] ?? '');
    $contractType = $_POST['contract_type'] ?? 'contract';
    $verification = $_POST['verification_status'] ?? 'pending';

    $stmt = db()->prepare("UPDATE compliance SET pf_number=?, esi_number=?, pan_number=?, aadhaar=?, bank_account=?, ifsc_code=?, contract_type=?, verification_status=? WHERE id=?");
    $stmt->execute([$pfNumber, $esiNumber, $panNumber, $aadhaar, $bankAccount, $ifscCode, $contractType, $verification, $id]);
    log_activity($currentUser['id'], "Updated compliance ID $id");
    set_flash('success', "Compliance record updated.");
    redirect(APP_URL . '/app/compliance/index.php');
}

require_once __DIR__ . '/../../includes/portal_header.php';
?>

<div class="panel" style="max-width:700px;">
    <div class="panel-header"><h3>Edit Compliance: <?= e($rec['emp_code']) ?> - <?= e($rec['first_name'] . ' ' . $rec['last_name']) ?></h3><a href="<?= APP_URL ?>/app/compliance/index.php" class="btn btn-outline btn-sm">&larr; Back</a></div>
    <div class="panel-body">
        <form method="POST" action="<?= APP_URL ?>/app/compliance/edit.php?id=<?= $id ?>">
            <div class="form-row">
                <div class="form-group"><label>PF Number</label><input type="text" name="pf_number" value="<?= e($rec['pf_number']) ?>"></div>
                <div class="form-group"><label>ESI Number</label><input type="text" name="esi_number" value="<?= e($rec['esi_number']) ?>"></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>PAN Number</label><input type="text" name="pan_number" value="<?= e($rec['pan_number']) ?>"></div>
                <div class="form-group"><label>Aadhaar</label><input type="text" name="aadhaar" value="<?= e($rec['aadhaar']) ?>"></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Bank Account</label><input type="text" name="bank_account" value="<?= e($rec['bank_account']) ?>"></div>
                <div class="form-group"><label>IFSC Code</label><input type="text" name="ifsc_code" value="<?= e($rec['ifsc_code']) ?>"></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Contract Type</label>
                    <select name="contract_type">
                        <option value="contract" <?= $rec['contract_type']==='contract'?'selected':'' ?>>Contract</option>
                        <option value="permanent" <?= $rec['contract_type']==='permanent'?'selected':'' ?>>Permanent</option>
                        <option value="temporary" <?= $rec['contract_type']==='temporary'?'selected':'' ?>>Temporary</option>
                    </select>
                </div>
                <div class="form-group"><label>Verification Status</label>
                    <select name="verification_status">
                        <option value="pending" <?= $rec['verification_status']==='pending'?'selected':'' ?>>Pending</option>
                        <option value="verified" <?= $rec['verification_status']==='verified'?'selected':'' ?>>Verified</option>
                        <option value="rejected" <?= $rec['verification_status']==='rejected'?'selected':'' ?>>Rejected</option>
                    </select>
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Update Record</button>
                <a href="<?= APP_URL ?>/app/compliance/index.php" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/portal_footer.php'; ?>
