<?php
// Pindari Enterprises - Database Configuration
// For Render: uses ENV variables

define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_NAME', getenv('DB_NAME') ?: 'pindari_erp');

function db() {
    static $pdo = null;
    static $failed = false;
    if ($failed) return null;
    if ($pdo === null) {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (Exception $e) {
            error_log("DB Error: " . $e->getMessage());
            $failed = true;
            return null;
        }
    }
    return $pdo;
}
