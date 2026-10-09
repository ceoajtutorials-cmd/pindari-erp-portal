<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/auth.php';

require_role('admin', 'hr');
$pageTitle = 'Edit Employee';
$currentPageFile = 'employees/index.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) { set_flash('error', 'Invalid employee ID.'); redirect(APP_URL . '/app/employees/index.php'); }

$stmt = db()->prepare("SELECT * FROM employees WHERE id = ?");
$stmt->execute([$id]);
$emp = $stmt->fetch();
if (!$emp) { set_flash('error', 'Employee not found.'); redirect(APP_URL . '/app/employees/index.php'); }

$clients = db()->query("SELECT id, company_name FROM clients ORDER BY company_name")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $empCode = trim($_POST['emp_code'] ?? '');
    $firstName = trim($_POST['first_name'] ?? '');
    $lastName = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $clientId = (int)($_POST['client_id'] ?? 0);
    $department = trim($_POST['department'] ?? '');
    $designation = trim($_POST['designation'] ?? '');
    $shift = trim($_POST['shift'] ?? 'General');
    $salary = (float)($_POST['salary'] ?? 0);
    $hireDate = $_POST['hire_date'] ?? date('Y-m-d');
    $status = $_POST['status'] ?? 'active';
    $address = trim($_POST['address'] ?? '');

    $errors = [];
    if (empty($empCode)) $errors[] = 'Employee code is required';
    if (empty($firstName)) $errors[] = 'First name is required';
    if (empty($lastName)) $errors[] = 'Last name is required';

    if (empty($errors)) {
        try {
            $stmt = db()->prepare("UPDATE employees SET emp_code=?, first_name=?, last_name=?, email=?, phone=?, client_id=?, department=?, designation=?, shift=?, salary=?, hire_date=?, status=?, address=? WHERE id=?");
            $stmt->execute([$empCode, $firstName, $lastName, $email, $phone, $clientId, $department, $designation, $shift, $salary, $hireDate, $status, $address, $id]);
            log_activity($currentUser['id'], "Updated employee: $empCode");
            set_flash('success', "Employee $empCode updated successfully.");
            redirect(APP_URL . '/app/employees/index.php');
        } catch (PDOException $e) {
            $errors[] = $e->getCode() == 23000 ? 'Employee code already exists.' : 'Error updating employee.';
        }
    }
    if (!empty($errors)) set_flash('error', implode('<br>', $errors));
}

require_once __DIR__ . '/../../includes/portal_header.php';
?>

<div class="panel" style="max-width:700px;">
    <div class="panel-header">
        <h3>Edit Employee: <?= e($emp['emp_code']) ?></h3>
        <a href="<?= APP_URL ?>/app/employees/index.php" class="btn btn-outline btn-sm">&larr; Back</a>
    </div>
    <div class="panel-body">
        <form method="POST" action="<?= APP_URL ?>/app/employees/edit.php?id=<?= $id ?>">
            <div class="form-row">
                <div class="form-group">
                    <label>Employee Code *</label>
                    <input type="text" name="emp_code" required value="<?= e($emp['emp_code']) ?>">
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status">
                        <option value="active" <?= $emp['status']==='active'?'selected':'' ?>>Active</option>
                        <option value="on_leave" <?= $emp['status']==='on_leave'?'selected':'' ?>>On Leave</option>
                        <option value="inactive" <?= $emp['status']==='inactive'?'selected':'' ?>>Inactive</option>
                        <option value="terminated" <?= $emp['status']==='terminated'?'selected':'' ?>>Terminated</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>First Name *</label>
                    <input type="text" name="first_name" required value="<?= e($emp['first_name']) ?>">
                </div>
                <div class="form-group">
                    <label>Last Name *</label>
                    <input type="text" name="last_name" required value="<?= e($emp['last_name']) ?>">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Email</label><input type="email" name="email" value="<?= e($emp['email']) ?>"></div>
                <div class="form-group"><label>Phone</label><input type="tel" name="phone" value="<?= e($emp['phone']) ?>"></div>
            </div>
            <div class="form-group">
                <label>Client *</label>
                <select name="client_id" required>
                    <option value="">Select Client</option>
                    <?php foreach ($clients as $c): ?>
                    <option value="<?= $c['id'] ?>" <?= $emp['client_id']==$c['id']?'selected':'' ?>><?= e($c['company_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Department</label><input type="text" name="department" value="<?= e($emp['department']) ?>"></div>
                <div class="form-group"><label>Designation</label><input type="text" name="designation" value="<?= e($emp['designation']) ?>"></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Shift</label><input type="text" name="shift" value="<?= e($emp['shift']) ?>"></div>
                <div class="form-group"><label>Monthly Salary</label><input type="number" name="salary" step="0.01" min="0" value="<?= e($emp['salary']) ?>"></div>
            </div>
            <div class="form-group"><label>Hire Date</label><input type="date" name="hire_date" value="<?= e($emp['hire_date']) ?>"></div>
            <div class="form-group"><label>Address</label><textarea name="address" rows="2"><?= e($emp['address']) ?></textarea></div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Update Employee</button>
                <a href="<?= APP_URL ?>/app/employees/index.php" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/portal_footer.php'; ?>
