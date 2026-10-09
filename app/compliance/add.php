<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/auth.php';

require_role('admin', 'hr');
$pageTitle = 'Add Compliance';
$currentPageFile = 'compliance/index.php';

$employees = db()->query("SELECT e.id, e.emp_code, e.first_name, e.last_name FROM employees e WHERE e.id NOT IN (SELECT employee_id FROM compliance) ORDER BY e.first_name")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $empId = (int)($_POST['employee_id'] ?? 0);
    $pfNumber = trim($_POST['pf_number'] ?? '');
    $esiNumber = trim($_POST['esi_number'] ?? '');
    $panNumber = trim($_POST['pan_number'] ?? '');
    $aadhaar = trim($_POST['aadhaar'] ?? '');
    $bankAccount = trim($_POST['bank_account'] ?? '');
    $ifscCode = trim($_POST['ifsc_code'] ?? '');
    $contractType = $_POST['contract_type'] ?? 'contract';
    $verification = $_POST['verification_status'] ?? 'pending';

    if ($empId <= 0) {
        set_flash('error', 'Employee is required.');
    } else {
        try {
            $stmt = db()->prepare("INSERT INTO compliance (employee_id, pf_number, esi_number, pan_number, aadhaar, bank_account, ifsc_code, contract_type, verification_status) VALUES (?,?,?,?,?,?,?,?,?)");
            $stmt->execute([$empId, $pfNumber, $esiNumber, $panNumber, $aadhaar, $bankAccount, $ifscCode, $contractType, $verification]);
            log_activity($currentUser['id'], "Added compliance for employee ID $empId");
            set_flash('success', "Compliance record added successfully.");
            redirect(APP_URL . '/app/compliance/index.php');
        } catch (PDOException $e) {
            set_flash('error', 'Error saving compliance record.');
        }
    }
}

require_once __DIR__ . '/../../includes/portal_header.php';
?>

<div class="panel" style="max-width:700px;">
    <div class="panel-header"><h3>Add Compliance Record</h3><a href="<?= APP_URL ?>/app/compliance/index.php" class="btn btn-outline btn-sm">&larr; Back</a></div>
    <div class="panel-body">
        <form method="POST" action="<?= APP_URL ?>/app/compliance/add.php">
            <div class="form-group"><label>Employee *</label>
                <select name="employee_id" required>
                    <option value="">Select Employee</option>
                    <?php foreach ($employees as $e): ?>
                    <option value="<?= $e['id'] ?>"><?= e($e['emp_code'] . ' - ' . $e['first_name'] . ' ' . $e['last_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-row">
                <div class="form-group"><label>PF Number</label><input type="text" name="pf_number" placeholder="PUNE/12345/001" value="<?= e($_POST['pf_number'] ?? '') ?>"></div>
                <div class="form-group"><label>ESI Number</label><input type="text" name="esi_number" placeholder="ESI/001/2024" value="<?= e($_POST['esi_number'] ?? '') ?>"></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>PAN Number</label><input type="text" name="pan_number" placeholder="ABCPA1234E" value="<?= e($_POST['pan_number'] ?? '') ?>"></div>
                <div class="form-group"><label>Aadhaar (masked)</label><input type="text" name="aadhaar" placeholder="XXXX-XXXX-1234" value="<?= e($_POST['aadhaar'] ?? '') ?>"></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Bank Account</label><input type="text" name="bank_account" value="<?= e($_POST['bank_account'] ?? '') ?>"></div>
                <div class="form-group"><label>IFSC Code</label><input type="text" name="ifsc_code" placeholder="SBIN0001234" value="<?= e($_POST['ifsc_code'] ?? '') ?>"></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Contract Type</label>
                    <select name="contract_type">
                        <option value="contract">Contract</option>
                        <option value="permanent">Permanent</option>
                        <option value="temporary">Temporary</option>
                    </select>
                </div>
                <div class="form-group"><label>Verification Status</label>
                    <select name="verification_status">
                        <option value="pending">Pending</option>
                        <option value="verified">Verified</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Add Record</button>
                <a href="<?= APP_URL ?>/app/compliance/index.php" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/portal_footer.php'; ?>
