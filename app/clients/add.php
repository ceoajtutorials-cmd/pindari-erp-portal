<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/auth.php';

require_role('admin');
$pageTitle = 'Add Client';
$currentPageFile = 'clients/index.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $companyName = trim($_POST['company_name'] ?? '');
    $contactPerson = trim($_POST['contact_person'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $contractStart = $_POST['contract_start'] ?? null;
    $contractEnd = $_POST['contract_end'] ?? null;
    $status = $_POST['status'] ?? 'active';

    if (empty($companyName)) {
        set_flash('error', 'Company name is required.');
    } else {
        try {
            $stmt = db()->prepare("INSERT INTO clients (company_name, contact_person, email, phone, address, contract_start, contract_end, status) VALUES (?,?,?,?,?,?,?,?)");
            $stmt->execute([$companyName, $contactPerson, $email, $phone, $address, $contractStart, $contractEnd, $status]);
            log_activity($currentUser['id'], "Added client: $companyName");
            set_flash('success', "Client $companyName added successfully.");
            redirect(APP_URL . '/app/clients/index.php');
        } catch (PDOException $e) {
            set_flash('error', 'Error saving client.');
        }
    }
}

require_once __DIR__ . '/../../includes/portal_header.php';
?>

<div class="panel" style="max-width:700px;">
    <div class="panel-header">
        <h3>Add New Client</h3>
        <a href="<?= APP_URL ?>/app/clients/index.php" class="btn btn-outline btn-sm">&larr; Back</a>
    </div>
    <div class="panel-body">
        <form method="POST" action="<?= APP_URL ?>/app/clients/add.php">
            <div class="form-group">
                <label>Company Name *</label>
                <input type="text" name="company_name" required value="<?= e($_POST['company_name'] ?? '') ?>">
            </div>
            <div class="form-row">
                <div class="form-group"><label>Contact Person</label><input type="text" name="contact_person" value="<?= e($_POST['contact_person'] ?? '') ?>"></div>
                <div class="form-group"><label>Status</label>
                    <select name="status">
                        <option value="active" selected>Active</option>
                        <option value="pending">Pending</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Email</label><input type="email" name="email" value="<?= e($_POST['email'] ?? '') ?>"></div>
                <div class="form-group"><label>Phone</label><input type="tel" name="phone" value="<?= e($_POST['phone'] ?? '') ?>"></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Contract Start</label><input type="date" name="contract_start" value="<?= e($_POST['contract_start'] ?? '') ?>"></div>
                <div class="form-group"><label>Contract End</label><input type="date" name="contract_end" value="<?= e($_POST['contract_end'] ?? '') ?>"></div>
            </div>
            <div class="form-group"><label>Address</label><textarea name="address" rows="2"><?= e($_POST['address'] ?? '') ?></textarea></div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Add Client</button>
                <a href="<?= APP_URL ?>/app/clients/index.php" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/portal_footer.php'; ?>
