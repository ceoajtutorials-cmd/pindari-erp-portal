<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/auth.php';

require_role('admin', 'hr');
$pageTitle = 'Add Payroll';
$currentPageFile = 'payroll/index.php';

$employees = db()->query("SELECT e.id, e.emp_code, e.first_name, e.last_name, e.salary FROM employees e WHERE e.status != 'terminated' ORDER BY e.first_name")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $empId = (int)($_POST['employee_id'] ?? 0);
    $month = trim($_POST['month'] ?? '');
    $year = (int)($_POST['year'] ?? date('Y'));
    $basic = (float)($_POST['basic_salary'] ?? 0);
    $hra = (float)($_POST['hra'] ?? 0);
    $allowances = (float)($_POST['allowances'] ?? 0);
    $deductions = (float)($_POST['deductions'] ?? 0);
    $pf = (float)($_POST['pf'] ?? 0);
    $esi = (float)($_POST['esi'] ?? 0);
    $status = $_POST['status'] ?? 'pending';
    $netPay = $basic + $hra + $allowances - $deductions - $pf - $esi;

    if ($empId <= 0 || empty($month)) {
        set_flash('error', 'Employee and month are required.');
    } else {
        try {
            $stmt = db()->prepare("INSERT INTO payroll (employee_id, month, year, basic_salary, hra, allowances, deductions, pf, esi, net_pay, status, processed_at) VALUES (?,?,?,?,?,?,?,?,?,?,?,NOW())");
            $stmt->execute([$empId, $month, $year, $basic, $hra, $allowances, $deductions, $pf, $esi, $netPay, $status]);
            log_activity($currentUser['id'], "Added payroll for employee ID $empId - $month $year");
            set_flash('success', "Payroll added successfully. Net pay: " . format_inr($netPay));
            redirect(APP_URL . '/app/payroll/index.php');
        } catch (PDOException $e) {
            set_flash('error', 'Error saving payroll.');
        }
    }
}

require_once __DIR__ . '/../../includes/portal_header.php';
?>

<div class="panel" style="max-width:700px;">
    <div class="panel-header">
        <h3>Add Payroll Record</h3>
        <a href="<?= APP_URL ?>/app/payroll/index.php" class="btn btn-outline btn-sm">&larr; Back</a>
    </div>
    <div class="panel-body">
        <form method="POST" action="<?= APP_URL ?>/app/payroll/add.php" id="payrollForm">
            <div class="form-group">
                <label>Employee *</label>
                <select name="employee_id" required id="empSelect">
                    <option value="">Select Employee</option>
                    <?php foreach ($employees as $e): ?>
                    <option value="<?= $e['id'] ?>" data-salary="<?= $e['salary'] ?>"><?= e($e['emp_code'] . ' - ' . $e['first_name'] . ' ' . $e['last_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Month *</label>
                    <select name="month" required>
                        <?php $months = ['January','February','March','April','May','June','July','August','September','October','November','December']; ?>
                        <?php foreach ($months as $m): ?>
                        <option value="<?= $m ?>" <?= $m === date('F') ? 'selected' : '' ?>><?= $m ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group"><label>Year *</label><input type="number" name="year" required value="<?= date('Y') ?>"></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Basic Salary</label><input type="number" name="basic_salary" id="basic" step="0.01" min="0" value="0" oninput="calcNet()"></div>
                <div class="form-group"><label>HRA</label><input type="number" name="hra" id="hra" step="0.01" min="0" value="0" oninput="calcNet()"></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Allowances</label><input type="number" name="allowances" id="allowances" step="0.01" min="0" value="0" oninput="calcNet()"></div>
                <div class="form-group"><label>Other Deductions</label><input type="number" name="deductions" id="deductions" step="0.01" min="0" value="0" oninput="calcNet()"></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>PF</label><input type="number" name="pf" id="pf" step="0.01" min="0" value="0" oninput="calcNet()"></div>
                <div class="form-group"><label>ESI</label><input type="number" name="esi" id="esi" step="0.01" min="0" value="0" oninput="calcNet()"></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Status</label>
                    <select name="status">
                        <option value="pending">Pending</option>
                        <option value="processed">Processed</option>
                        <option value="paid">Paid</option>
                    </select>
                </div>
                <div class="form-group"><label>Net Pay (auto)</label><input type="text" id="netPay" readonly style="font-weight:700;color:var(--blue);font-size:18px;"></div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Add Payroll</button>
                <a href="<?= APP_URL ?>/app/payroll/index.php" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
function calcNet() {
    var basic = parseFloat(document.getElementById('basic').value) || 0;
    var hra = parseFloat(document.getElementById('hra').value) || 0;
    var allowances = parseFloat(document.getElementById('allowances').value) || 0;
    var deductions = parseFloat(document.getElementById('deductions').value) || 0;
    var pf = parseFloat(document.getElementById('pf').value) || 0;
    var esi = parseFloat(document.getElementById('esi').value) || 0;
    var net = basic + hra + allowances - deductions - pf - esi;
    document.getElementById('netPay').value = '₹' + net.toFixed(2);
}
document.getElementById('empSelect').addEventListener('change', function() {
    var salary = this.options[this.selectedIndex].getAttribute('data-salary');
    if (salary && salary > 0) {
        document.getElementById('basic').value = (salary * 0.6).toFixed(2);
        document.getElementById('hra').value = (salary * 0.2).toFixed(2);
        document.getElementById('allowances').value = (salary * 0.2).toFixed(2);
        document.getElementById('pf').value = (salary * 0.08).toFixed(2);
        document.getElementById('esi').value = (salary * 0.0375).toFixed(2);
        calcNet();
    }
});
</script>

<?php require_once __DIR__ . '/../../includes/portal_footer.php'; ?>
