<?php
/**
 * Pindari Enterprises - Main Configuration
 */

define('APP_NAME', 'Pindari Enterprises');
define('APP_URL', 'http://localhost/pindari-enterprises');
define('APP_URL_PORTAL', APP_URL . '/app');

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

/**
 * Sanitize output to prevent XSS
 */
function e($string) {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Redirect helper
 */
function redirect($url) {
    header("Location: " . $url);
    exit;
}

/**
 * Flash message helpers
 */
function set_flash($type, $message) {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function get_flash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

/**
 * Log activity
 */
function log_activity($userId, $action) {
    $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    try {
        $stmt = db()->prepare("INSERT INTO activity_log (user_id, action, ip_address) VALUES (?, ?, ?)");
        $stmt->execute([$userId, $action, $ip]);
    } catch (Exception $e) {
        // Silent fail for logging
    }
}

/**
 * Format currency in INR
 */
function format_inr($amount) {
    return '₹' . number_format((float)$amount, 2);
}

/**
 * Format date
 */
function format_date($date) {
    if (!$date) return '-';
    return date('d M Y', strtotime($date));
}
