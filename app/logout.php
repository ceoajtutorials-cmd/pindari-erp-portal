<?php
require_once __DIR__ . '/../config/config.php';

if (isset($_SESSION['user_id'])) {
    log_activity($_SESSION['user_id'], 'Logged out');
}

$_SESSION = [];
session_destroy();

redirect(APP_URL . '/app/login.php');
