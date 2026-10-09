<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/auth.php';

require_role('admin', 'hr');
$pageTitle = 'Add Placement';
$currentPageFile = 'placements/index.php';

$employees = db()->query("SELECT id, emp_code, first_name, last_name FROM employees ORDER BY first_name")->fetchAll();
$clients = db()->query("SELECT id, company_name FROM clients ORDER BY company_name")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $empId = (int)($_POST['employee_id'] ?? 0);
    $clId = (int)($_POST['client_id'] ?? 0);
    $position = trim($_POST['position'] ?? '');
    $startDate = $_POST['start_date'] ?? date('Y-m-d');
    $endDate = $_POST['end_date'] ?? null;
    $status = $_POST['status'] ?? 'active';

    if ($empId <= 0 || $clId <= 0) {
        set_flash('error', 'Employee and client are required.');
    } else {
        try {
            $stmt = db()->prepare("INSERT INTO placements (employee_id, client_id, position, start_date, end_date, status) VALUES (?,?,?,?,?,?)");
            $stmt->execute([$empId, $clId, $position, $startDate, $endDate, $status]);
            log_activity($currentUser['id'], "Added placement for employee ID $empId to client ID $clId");
            set_flash('success', "Placement added successfully.");
            redirect(APP_URL . '/app/placements/index.php');
        } catch (PDOException $e) {
            set_flash('error', 'Error saving placement.');
        }
    }
}

require_once __DIR__ . '/../../includes/portal_header.php';
?>

<div class="panel" style="max-width:700px;">
    <div class="panel-header"><h3>Add Placement</h3><a href="<?= APP_URL ?>/app/placements/index.php" class="btn btn-outline btn-sm">&larr; Back</a></div>
    <div class="panel-body">
        <form method="POST" action="<?= APP_URL ?>/app/placements/add.php">
            <div class="form-group"><label>Employee *</label>
                <select name="employee_id" required>
                    <option value="">Select Employee</option>
                    <?php foreach ($employees as $e): ?>
                    <option value="<?= $e['id'] ?>"><?= e($e['emp_code'] . ' - ' . $e['first_name'] . ' ' . $e['last_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group"><label>Client *</label>
                <select name="client_id" required>
                    <option value="">Select Client</option>
                    <?php foreach ($clients as $c): ?>
                    <option value="<?= $c['id'] ?>"><?= e($c['company_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group"><label>Position</label><input type="text" name="position" placeholder="Assembly Worker" value="<?= e($_POST['position'] ?? '') ?>"></div>
            <div class="form-row">
                <div class="form-group"><label>Start Date</label><input type="date" name="start_date" value="<?= e($_POST['start_date'] ?? date('Y-m-d')) ?>"></div>
                <div class="form-group"><label>End Date</label><input type="date" name="end_date" value="<?= e($_POST['end_date'] ?? '') ?>"></div>
            </div>
            <div class="form-group"><label>Status</label>
                <select name="status">
                    <option value="active" selected>Active</option>
                    <option value="completed">Completed</option>
                    <option value="terminated">Terminated</option>
                </select>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Add Placement</button>
                <a href="<?= APP_URL ?>/app/placements/index.php" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/portal_footer.php'; ?>
