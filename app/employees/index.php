<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/auth.php';

require_role('admin', 'hr', 'client');
$pageTitle = 'Employees';
$currentPageFile = 'employees/index.php';

// Build query based on role
$clientId = $currentUser['client_id'];
$search = trim($_GET['search'] ?? '');
$statusFilter = trim($_GET['status'] ?? '');

$params = [];
$where = [];

if ($currentUser['role'] === 'client') {
    $where[] = "e.client_id = ?";
    $params[] = $clientId;
}
if ($search) {
    $where[] = "(e.emp_code LIKE ? OR e.first_name LIKE ? OR e.last_name LIKE ? OR e.phone LIKE ?)";
    $term = "%$search%";
    $params[] = $term; $params[] = $term; $params[] = $term; $params[] = $term;
}
if ($statusFilter) {
    $where[] = "e.status = ?";
    $params[] = $statusFilter;
}

$whereSql = $where ? "WHERE " . implode(" AND ", $where) : "";

$sql = "SELECT e.*, c.company_name FROM employees e LEFT JOIN clients c ON e.client_id = c.id $whereSql ORDER BY e.created_at DESC";
$stmt = db()->prepare($sql);
$stmt->execute($params);
$employees = $stmt->fetchAll();

require_once __DIR__ . '/../../includes/portal_header.php';
?>

<div class="panel">
    <div class="panel-header">
        <h3><?= $currentUser['role'] === 'client' ? 'My Workforce' : 'All Employees' ?> (<?= count($employees) ?>)</h3>
        <?php if (in_array($currentUser['role'], ['admin', 'hr'])): ?>
        <a href="<?= APP_URL ?>/app/employees/add.php" class="btn btn-primary btn-sm">+ Add Employee</a>
        <?php endif; ?>
    </div>
    <div style="padding:20px 24px;border-bottom:1px solid var(--gray-200);">
        <form method="GET" class="filter-bar">
            <input type="text" name="search" placeholder="Search by name, code, phone..." value="<?= e($search) ?>" style="flex:1;min-width:200px;">
            <select name="status">
                <option value="">All Status</option>
                <option value="active" <?= $statusFilter === 'active' ? 'selected' : '' ?>>Active</option>
                <option value="on_leave" <?= $statusFilter === 'on_leave' ? 'selected' : '' ?>>On Leave</option>
                <option value="terminated" <?= $statusFilter === 'terminated' ? 'selected' : '' ?>>Terminated</option>
                <option value="inactive" <?= $statusFilter === 'inactive' ? 'selected' : '' ?>>Inactive</option>
            </select>
            <button type="submit" class="btn btn-outline btn-sm">Filter</button>
            <?php if ($search || $statusFilter): ?>
                <a href="<?= APP_URL ?>/app/employees/index.php" class="btn btn-outline btn-sm">Clear</a>
            <?php endif; ?>
        </form>
    </div>
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Name</th>
                    <th class="hide-mobile">Department</th>
                    <th class="hide-mobile">Designation</th>
                    <?php if ($currentUser['role'] !== 'client'): ?><th class="hide-mobile">Client</th><?php endif; ?>
                    <th>Salary</th>
                    <th>Status</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($employees as $emp): ?>
                <tr>
                    <td><strong><?= e($emp['emp_code']) ?></strong></td>
                    <td><?= e($emp['first_name'] . ' ' . $emp['last_name']) ?></td>
                    <td class="hide-mobile"><?= e($emp['department']) ?></td>
                    <td class="hide-mobile"><?= e($emp['designation']) ?></td>
                    <?php if ($currentUser['role'] !== 'client'): ?><td class="hide-mobile"><?= e($emp['company_name'] ?? '-') ?></td><?php endif; ?>
                    <td><?= format_inr($emp['salary']) ?></td>
                    <td>
                        <?php $cls = ['active'=>'badge-success','on_leave'=>'badge-warning','terminated'=>'badge-danger','inactive'=>'badge-gray'][$emp['status']] ?? 'badge-gray'; ?>
                        <span class="badge <?= $cls ?>"><span class="badge-dot"></span><?= ucfirst(str_replace('_', ' ', $emp['status'])) ?></span>
                    </td>
                    <td class="text-center">
                        <a href="<?= APP_URL ?>/app/employees/view.php?id=<?= $emp['id'] ?>" class="btn-icon" title="View">&#128065;</a>
                        <?php if (in_array($currentUser['role'], ['admin', 'hr'])): ?>
                        <a href="<?= APP_URL ?>/app/employees/edit.php?id=<?= $emp['id'] ?>" class="btn-icon" title="Edit">&#9998;</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($employees)): ?>
                <tr><td colspan="8" style="text-align:center;padding:40px;color:var(--gray-500);">No employees found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/portal_footer.php'; ?>
