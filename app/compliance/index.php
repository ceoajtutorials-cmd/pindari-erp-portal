<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/auth.php';

require_role('admin', 'hr');
$pageTitle = 'Compliance';
$currentPageFile = 'compliance/index.php';

$statusFilter = trim($_GET['status'] ?? '');
$where = [];
$params = [];
if ($statusFilter) { $where[] = "comp.verification_status = ?"; $params[] = $statusFilter; }
$whereSql = $where ? "WHERE " . implode(" AND ", $where) : "";

$sql = "SELECT comp.*, e.emp_code, e.first_name, e.last_name, c.company_name 
        FROM compliance comp 
        JOIN employees e ON comp.employee_id = e.id 
        LEFT JOIN clients c ON e.client_id = c.id 
        $whereSql ORDER BY comp.created_at DESC";
$stmt = db()->prepare($sql);
$stmt->execute($params);
$records = $stmt->fetchAll();

$pendingCount = db()->query("SELECT COUNT(*) FROM compliance WHERE verification_status = 'pending'")->fetchColumn();
$verifiedCount = db()->query("SELECT COUNT(*) FROM compliance WHERE verification_status = 'verified'")->fetchColumn();
$rejectedCount = db()->query("SELECT COUNT(*) FROM compliance WHERE verification_status = 'rejected'")->fetchColumn();

require_once __DIR__ . '/../../includes/portal_header.php';
?>

<div class="stat-grid">
    <div class="stat-card-erp"><div class="stat-icon-box stat-icon-blue">&#128220;</div><div class="stat-info"><div class="stat-val"><?= count($records) ?></div><div class="stat-lbl">Total Records</div></div></div>
    <div class="stat-card-erp"><div class="stat-icon-box stat-icon-green">&#9989;</div><div class="stat-info"><div class="stat-val"><?= $verifiedCount ?></div><div class="stat-lbl">Verified</div></div></div>
    <div class="stat-card-erp"><div class="stat-icon-box stat-icon-amber">&#9203;</div><div class="stat-info"><div class="stat-val"><?= $pendingCount ?></div><div class="stat-lbl">Pending</div></div></div>
    <div class="stat-card-erp"><div class="stat-icon-box stat-icon-red">&#10060;</div><div class="stat-info"><div class="stat-val"><?= $rejectedCount ?></div><div class="stat-lbl">Rejected</div></div></div>
</div>

<div class="panel">
    <div class="panel-header">
        <h3>Compliance Records</h3>
        <a href="<?= APP_URL ?>/app/compliance/add.php" class="btn btn-primary btn-sm">+ Add Record</a>
    </div>
    <div style="padding:20px 24px;border-bottom:1px solid var(--gray-200);">
        <form method="GET" class="filter-bar">
            <select name="status">
                <option value="">All Status</option>
                <option value="pending" <?= $statusFilter==='pending'?'selected':'' ?>>Pending</option>
                <option value="verified" <?= $statusFilter==='verified'?'selected':'' ?>>Verified</option>
                <option value="rejected" <?= $statusFilter==='rejected'?'selected':'' ?>>Rejected</option>
            </select>
            <button type="submit" class="btn btn-outline btn-sm">Filter</button>
            <?php if ($statusFilter): ?><a href="<?= APP_URL ?>/app/compliance/index.php" class="btn btn-outline btn-sm">Clear</a><?php endif; ?>
        </form>
    </div>
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead><tr><th>Code</th><th>Name</th><th class="hide-mobile">Client</th><th>PF</th><th>ESI</th><th class="hide-mobile">Contract Type</th><th>Verification</th><th class="text-center">Actions</th></tr></thead>
            <tbody>
                <?php foreach ($records as $r): ?>
                <tr>
                    <td><strong><?= e($r['emp_code']) ?></strong></td>
                    <td><?= e($r['first_name'] . ' ' . $r['last_name']) ?></td>
                    <td class="hide-mobile"><?= e($r['company_name'] ?? '-') ?></td>
                    <td><?= e($r['pf_number'] ?: '-') ?></td>
                    <td><?= e($r['esi_number'] ?: '-') ?></td>
                    <td class="hide-mobile"><span class="badge badge-info"><?= ucfirst(e($r['contract_type'])) ?></span></td>
                    <td><?php $cls = ['verified'=>'badge-success','pending'=>'badge-warning','rejected'=>'badge-danger'][$r['verification_status']] ?? 'badge-gray'; ?>
                        <span class="badge <?= $cls ?>"><?= ucfirst(e($r['verification_status'])) ?></span></td>
                    <td class="text-center">
                        <a href="<?= APP_URL ?>/app/compliance/edit.php?id=<?= $r['id'] ?>" class="btn-icon" title="Edit">&#9998;</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($records)): ?>
                <tr><td colspan="8" style="text-align:center;padding:40px;color:var(--gray-500);">No compliance records found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/portal_footer.php'; ?>
