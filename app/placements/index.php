<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/auth.php';

require_role('admin', 'hr', 'client');
$pageTitle = 'Placements';
$currentPageFile = 'placements/index.php';

$clientId = $currentUser['client_id'];

$where = [];
$params = [];
if ($currentUser['role'] === 'client') { $where[] = "pl.client_id = ?"; $params[] = $clientId; }
$whereSql = $where ? "WHERE " . implode(" AND ", $where) : "";

$sql = "SELECT pl.*, e.emp_code, e.first_name, e.last_name, c.company_name 
        FROM placements pl 
        JOIN employees e ON pl.employee_id = e.id 
        JOIN clients c ON pl.client_id = c.id 
        $whereSql ORDER BY pl.start_date DESC";
$stmt = db()->prepare($sql);
$stmt->execute($params);
$placements = $stmt->fetchAll();

$canManage = in_array($currentUser['role'], ['admin', 'hr']);

require_once __DIR__ . '/../../includes/portal_header.php';
?>

<div class="panel">
    <div class="panel-header">
        <h3>Placements (<?= count($placements) ?>)</h3>
        <?php if ($canManage): ?>
        <a href="<?= APP_URL ?>/app/placements/add.php" class="btn btn-primary btn-sm">+ Add Placement</a>
        <?php endif; ?>
    </div>
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead><tr><th>Code</th><th>Name</th><th class="hide-mobile">Client</th><th>Position</th><th>Start</th><th class="hide-mobile">End</th><th>Status</th><?php if ($canManage): ?><th class="text-center">Actions</th><?php endif; ?></tr></thead>
            <tbody>
                <?php foreach ($placements as $pl): ?>
                <tr>
                    <td><strong><?= e($pl['emp_code']) ?></strong></td>
                    <td><?= e($pl['first_name'] . ' ' . $pl['last_name']) ?></td>
                    <td class="hide-mobile"><?= e($pl['company_name']) ?></td>
                    <td><?= e($pl['position']) ?></td>
                    <td><?= format_date($pl['start_date']) ?></td>
                    <td class="hide-mobile"><?= format_date($pl['end_date']) ?></td>
                    <td><?php $cls = ['active'=>'badge-success','completed'=>'badge-info','terminated'=>'badge-danger'][$pl['status']] ?? 'badge-gray'; ?>
                        <span class="badge <?= $cls ?>"><?= ucfirst(e($pl['status'])) ?></span></td>
                    <?php if ($canManage): ?>
                    <td class="text-center"><a href="<?= APP_URL ?>/app/placements/edit.php?id=<?= $pl['id'] ?>" class="btn-icon" title="Edit">&#9998;</a></td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($placements)): ?>
                <tr><td colspan="8" style="text-align:center;padding:40px;color:var(--gray-500);">No placements found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/portal_footer.php'; ?>
