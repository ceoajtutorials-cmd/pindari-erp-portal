<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/auth.php';

require_role('admin', 'hr');
$pageTitle = 'Edit Payroll';
$currentPageFile = 'payroll/index.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) redirect(APP_URL . '/app/payroll/index.php');

$stmt = db()->prepare("SELECT p.*, e.emp_code, e.first_name, e.last_name FROM payroll p JOIN employees e ON p.employee_id = e.id WHERE p.id = ?");
$stmt->execute([$id]);
$pay = $stmt->fetch();
if (!$pay) { set_flash('error', 'Payroll record not found.'); redirect(APP_URL . '/app/payroll/index.php'); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $basic = (float)($_POST['basic_salary'] ?? 0);
    $hra = (float)($_POST['hra'] ?? 0);
    $allowances = (float)($_POST['allowances'] ?? 0);
    $deductions = (float)($_POST['deductions'] ?? 0);
    $pf = (float)($_POST['pf'] ?? 0);
    $esi = (float)($_POST['esi'] ?? 0);
    $status = $_POST['status'] ?? 'pending';
    $netPay = $basic + $hra + $allowances - $deductions - $pf - $esi;

    $stmt = db()->prepare("UPDATE payroll SET basic_salary=?, hra=?, allowances=?, deductions=?, pf=?, esi=?, net_pay=?, status=?, processed_at=IF(?='paid',NOW(),processed_at) WHERE id=?");
    $stmt->execute([$basic, $hra, $allowances, $deductions, $pf, $esi, $netPay, $status, $status, $id]);
    log_activity($currentUser['id'], "Updated payroll ID $id");
    set_flash('success', "Payroll updated successfully.");
    redirect(APP_URL . '/app/payroll/index.php');
}

require_once __DIR__ . '/../../includes/portal_header.php';
?>

<div class="panel" style="max-width:700px;">
    <div class="panel-header">
        <h3>Edit Payroll: <?= e($pay['emp_code']) ?> - <?= e($pay['month']) ?> <?= e($pay['year']) ?></h3>
        <a href="<?= APP_URL ?>/app/payroll/index.php" class="btn btn-outline btn-sm">&larr; Back</a>
    </div>
    <div class="panel-body">
        <form method="POST" action="<?= APP_URL ?>/app/payroll/edit.php?id=<?= $id ?>">
            <p style="margin-bottom:20px;color:var(--gray-500);">Employee: <strong><?= e($pay['first_name'] . ' ' . $pay['last_name']) ?></strong> (<?= e($pay['emp_code']) ?>) | <?= e($pay['month']) ?> <?= e($pay['year']) ?></p>
            <div class="form-row">
                <div class="form-group"><label>Basic Salary</label><input type="number" name="basic_salary" id="basic" step="0.01" min="0" value="<?= e($pay['basic_salary']) ?>" oninput="calcNet()"></div>
                <div class="form-group"><label>HRA</label><input type="number" name="hra" id="hra" step="0.01" min="0" value="<?= e($pay['hra']) ?>" oninput="calcNet()"></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Allowances</label><input type="number" name="allowances" id="allowances" step="0.01" min="0" value="<?= e($pay['allowances']) ?>" oninput="calcNet()"></div>
                <div class="form-group"><label>Other Deductions</label><input type="number" name="deductions" id="deductions" step="0.01" min="0" value="<?= e($pay['deductions']) ?>" oninput="calcNet()"></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>PF</label><input type="number" name="pf" id="pf" step="0.01" min="0" value="<?= e($pay['pf']) ?>" oninput="calcNet()"></div>
                <div class="form-group"><label>ESI</label><input type="number" name="esi" id="esi" step="0.01" min="0" value="<?= e($pay['esi']) ?>" oninput="calcNet()"></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Status</label>
                    <select name="status">
                        <option value="pending" <?= $pay['status']==='pending'?'selected':'' ?>>Pending</option>
                        <option value="processed" <?= $pay['status']==='processed'?'selected':'' ?>>Processed</option>
                        <option value="paid" <?= $pay['status']==='paid'?'selected':'' ?>>Paid</option>
                    </select>
                </div>
                <div class="form-group"><label>Net Pay (auto)</label><input type="text" id="netPay" readonly style="font-weight:700;color:var(--blue);font-size:18px;" value="<?= format_inr($pay['net_pay']) ?>"></div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Update Payroll</button>
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
</script>

<?php require_once __DIR__ . '/../../includes/portal_footer.php'; ?>
