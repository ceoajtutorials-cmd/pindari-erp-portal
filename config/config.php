<?php
// Pindari Enterprises - Main Configuration

define('APP_URL', 'https://pindari-erp-portal.onrender.com');
define('APP_URL_PORTAL', APP_URL . '/emp');
define('APP_NAME', 'Pindari Enterprises');

// Start session securely
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 86400,
        'path' => '/',
        'httponly' => true,
        'samesite' => 'Strict',
    ]);
    session_start();
}

// Autoload database config
require_once __DIR__ . '/database.php';

// Sanitize output to prevent XSS
function e($string) {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}
