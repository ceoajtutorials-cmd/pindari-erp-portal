<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/auth.php';

require_role('admin');
$pageTitle = 'Clients';
$currentPageFile = 'clients/index.php';

$clients = db()->query("
    SELECT c.*, COUNT(e.id) as emp_count 
    FROM clients c 
    LEFT JOIN employees e ON c.id = e.client_id 
    GROUP BY c.id 
    ORDER BY c.company_name
")->fetchAll();

require_once __DIR__ . '/../../includes/portal_header.php';
?>

<div class="panel">
    <div class="panel-header">
        <h3>All Clients (<?= count($clients) ?>)</h3>
        <a href="<?= APP_URL ?>/app/clients/add.php" class="btn btn-primary btn-sm">+ Add Client</a>
    </div>
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Company</th>
                    <th>Contact Person</th>
                    <th class="hide-mobile">Phone</th>
                    <th class="hide-mobile">Contract Start</th>
                    <th class="hide-mobile">Contract End</th>
                    <th>Workers</th>
                    <th>Status</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clients as $c): ?>
                <tr>
                    <td><strong><?= e($c['company_name']) ?></strong></td>
                    <td><?= e($c['contact_person'] ?: '-') ?></td>
                    <td class="hide-mobile"><?= e($c['phone'] ?: '-') ?></td>
                    <td class="hide-mobile"><?= format_date($c['contract_start']) ?></td>
                    <td class="hide-mobile"><?= format_date($c['contract_end']) ?></td>
                    <td><?= $c['emp_count'] ?></td>
                    <td>
                        <?php $cls = ['active'=>'badge-success','inactive'=>'badge-gray','pending'=>'badge-warning'][$c['status']] ?? 'badge-gray'; ?>
                        <span class="badge <?= $cls ?>"><span class="badge-dot"></span><?= ucfirst(e($c['status'])) ?></span>
                    </td>
                    <td class="text-center">
                        <a href="<?= APP_URL ?>/app/clients/view.php?id=<?= $c['id'] ?>" class="btn-icon" title="View">&#128065;</a>
                        <a href="<?= APP_URL ?>/app/clients/edit.php?id=<?= $c['id'] ?>" class="btn-icon" title="Edit">&#9998;</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($clients)): ?>
                <tr><td colspan="8" style="text-align:center;padding:40px;color:var(--gray-500);">No clients found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/portal_footer.php'; ?>
