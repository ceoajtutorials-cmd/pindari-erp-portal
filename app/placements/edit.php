<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/auth.php';

require_role('admin', 'hr');
$pageTitle = 'Edit Placement';
$currentPageFile = 'placements/index.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) redirect(APP_URL . '/app/placements/index.php');

$stmt = db()->prepare("SELECT pl.*, e.emp_code, e.first_name, e.last_name, c.company_name FROM placements pl JOIN employees e ON pl.employee_id = e.id JOIN clients c ON pl.client_id = c.id WHERE pl.id = ?");
$stmt->execute([$id]);
$pl = $stmt->fetch();
if (!$pl) { set_flash('error', 'Placement not found.'); redirect(APP_URL . '/app/placements/index.php'); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $position = trim($_POST['position'] ?? '');
    $startDate = $_POST['start_date'] ?? $pl['start_date'];
    $endDate = $_POST['end_date'] ?? $pl['end_date'];
    $status = $_POST['status'] ?? 'active';

    $stmt = db()->prepare("UPDATE placements SET position=?, start_date=?, end_date=?, status=? WHERE id=?");
    $stmt->execute([$position, $startDate, $endDate, $status, $id]);
    log_activity($currentUser['id'], "Updated placement ID $id");
    set_flash('success', "Placement updated.");
    redirect(APP_URL . '/app/placements/index.php');
}

require_once __DIR__ . '/../../includes/portal_header.php';
?>

<div class="panel" style="max-width:700px;">
    <div class="panel-header"><h3>Edit Placement</h3><a href="<?= APP_URL ?>/app/placements/index.php" class="btn btn-outline btn-sm">&larr; Back</a></div>
    <div class="panel-body">
        <p style="margin-bottom:20px;color:var(--gray-500);"><strong><?= e($pl['emp_code']) ?> - <?= e($pl['first_name'] . ' ' . $pl['last_name']) ?></strong> at <strong><?= e($pl['company_name']) ?></strong></p>
        <form method="POST" action="<?= APP_URL ?>/app/placements/edit.php?id=<?= $id ?>">
            <div class="form-group"><label>Position</label><input type="text" name="position" value="<?= e($pl['position']) ?>"></div>
            <div class="form-row">
                <div class="form-group"><label>Start Date</label><input type="date" name="start_date" value="<?= e($pl['start_date']) ?>"></div>
                <div class="form-group"><label>End Date</label><input type="date" name="end_date" value="<?= e($pl['end_date']) ?>"></div>
            </div>
            <div class="form-group"><label>Status</label>
                <select name="status">
                    <option value="active" <?= $pl['status']==='active'?'selected':'' ?>>Active</option>
                    <option value="completed" <?= $pl['status']==='completed'?'selected':'' ?>>Completed</option>
                    <option value="terminated" <?= $pl['status']==='terminated'?'selected':'' ?>>Terminated</option>
                </select>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Update Placement</button>
                <a href="<?= APP_URL ?>/app/placements/index.php" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/portal_footer.php'; ?>
