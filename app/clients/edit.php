<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/auth.php';

require_role('admin');
$pageTitle = 'Edit Client';
$currentPageFile = 'clients/index.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) redirect(APP_URL . '/app/clients/index.php');

$stmt = db()->prepare("SELECT * FROM clients WHERE id = ?");
$stmt->execute([$id]);
$client = $stmt->fetch();
if (!$client) { set_flash('error', 'Client not found.'); redirect(APP_URL . '/app/clients/index.php'); }

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
        $stmt = db()->prepare("UPDATE clients SET company_name=?, contact_person=?, email=?, phone=?, address=?, contract_start=?, contract_end=?, status=? WHERE id=?");
        $stmt->execute([$companyName, $contactPerson, $email, $phone, $address, $contractStart, $contractEnd, $status, $id]);
        log_activity($currentUser['id'], "Updated client: $companyName");
        set_flash('success', "Client updated successfully.");
        redirect(APP_URL . '/app/clients/index.php');
    }
}

require_once __DIR__ . '/../../includes/portal_header.php';
?>

<div class="panel" style="max-width:700px;">
    <div class="panel-header">
        <h3>Edit Client: <?= e($client['company_name']) ?></h3>
        <a href="<?= APP_URL ?>/app/clients/index.php" class="btn btn-outline btn-sm">&larr; Back</a>
    </div>
    <div class="panel-body">
        <form method="POST" action="<?= APP_URL ?>/app/clients/edit.php?id=<?= $id ?>">
            <div class="form-group"><label>Company Name *</label><input type="text" name="company_name" required value="<?= e($client['company_name']) ?>"></div>
            <div class="form-row">
                <div class="form-group"><label>Contact Person</label><input type="text" name="contact_person" value="<?= e($client['contact_person']) ?>"></div>
                <div class="form-group"><label>Status</label>
                    <select name="status">
                        <option value="active" <?= $client['status']==='active'?'selected':'' ?>>Active</option>
                        <option value="pending" <?= $client['status']==='pending'?'selected':'' ?>>Pending</option>
                        <option value="inactive" <?= $client['status']==='inactive'?'selected':'' ?>>Inactive</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Email</label><input type="email" name="email" value="<?= e($client['email']) ?>"></div>
                <div class="form-group"><label>Phone</label><input type="tel" name="phone" value="<?= e($client['phone']) ?>"></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Contract Start</label><input type="date" name="contract_start" value="<?= e($client['contract_start']) ?>"></div>
                <div class="form-group"><label>Contract End</label><input type="date" name="contract_end" value="<?= e($client['contract_end']) ?>"></div>
            </div>
            <div class="form-group"><label>Address</label><textarea name="address" rows="2"><?= e($client['address']) ?></textarea></div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Update Client</button>
                <a href="<?= APP_URL ?>/app/clients/index.php" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/portal_footer.php'; ?>
