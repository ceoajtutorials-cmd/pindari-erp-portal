<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/auth.php';

require_role('admin', 'hr');
$id = (int)($_GET['id'] ?? 0);
if (!$id) redirect(APP_URL . '/app/payroll/index.php');

$stmt = db()->prepare("UPDATE payroll SET status = 'paid', processed_at = NOW() WHERE id = ?");
$stmt->execute([$id]);
log_activity($currentUser['id'], "Marked payroll ID $id as paid");
set_flash('success', 'Payroll marked as paid.');
redirect(APP_URL . '/app/payroll/index.php');
