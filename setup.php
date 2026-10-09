<?php
/**
 * Run this script ONCE to create the database and set up hashed passwords.
 * Access via browser: http://localhost/pindari-enterprises/setup.php
 * DELETE this file after setup is complete.
 */
require_once __DIR__ . '/config/config.php';

// Create database and tables
$dsn = "mysql:host=" . DB_HOST . ";charset=utf8mb4";
try {
    $rootPdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
} catch (PDOException $e) {
    die("Cannot connect to MySQL. Is XAMPP MySQL running? Error: " . $e->getMessage());
}

// Read and execute schema
$schema = file_get_contents(__DIR__ . '/database/schema.sql');

// Split on semicolons but handle the INSERT statements with hashed passwords
// First, create database and tables without the user inserts
$rootPdo->exec("CREATE DATABASE IF NOT EXISTS " . DB_NAME . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

// Execute the schema statements (split by semicolon followed by newline)
$statements = preg_split('/;\s*\n/', $schema);
foreach ($statements as $stmt) {
    $stmt = trim($stmt);
    if (empty($stmt) || strpos($stmt, '--') === 0) continue;
    // Skip the default user inserts with placeholder hashes - we'll insert properly
    if (strpos($stmt, "YourHashHere") !== false) continue;
    try {
        $rootPdo->exec($stmt);
    } catch (PDOException $e) {
        // Table might already exist
        if (strpos($e->getMessage(), 'already exists') === false && strpos($e->getMessage(), 'Duplicate entry') === false) {
            echo "<p style='color:orange'>Warning: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    }
}

// Now insert users with properly hashed passwords
$pdo = db();

$users = [
    ['System Admin', 'admin@pindari.com', 'admin123', 'admin', '9876543210', null],
    ['HR Manager', 'hr@pindari.com', 'hr123', 'hr', '9876543211', null],
    ['Tata Client', 'client@tatamotors.com', 'client123', 'client', '9876543212', 1],
    ['Test Employee', 'employee@gmail.com', 'emp123', 'employee', '9876543213', null],
];

$checkStmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
$insertStmt = $pdo->prepare("INSERT INTO users (name, email, password, role, phone, client_id) VALUES (?, ?, ?, ?, ?, ?)");

$insertedCount = 0;
foreach ($users as $user) {
    $checkStmt->execute([$user[1]]);
    if ($checkStmt->fetchColumn() == 0) {
        $hashed = password_hash($user[2], PASSWORD_DEFAULT);
        $insertStmt->execute([$user[0], $user[1], $hashed, $user[3], $user[4], $user[5]]);
        $insertedCount++;
    }
}

// Also link the employee user to an employee record
$empStmt = $pdo->prepare("UPDATE users SET client_id = 1 WHERE email = 'employee@gmail.com'");
// Create an employee record for the employee user if not exists
$empCheck = $pdo->prepare("SELECT COUNT(*) FROM employees WHERE email = 'employee@gmail.com'");
$empCheck->execute();
if ($empCheck->fetchColumn() == 0) {
    $empInsert = $pdo->prepare("INSERT INTO employees (emp_code, first_name, last_name, email, phone, client_id, department, designation, shift, salary, hire_date, status, address) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $empInsert->execute(['PE-000', 'Test', 'Employee', 'employee@gmail.com', '9876543213', 1, 'Production', 'Assembly Worker', 'General', 18500.00, date('Y-m-d'), 'active', 'Pune, Maharashtra']);
}

echo "<!DOCTYPE html><html><head><title>Setup Complete</title>";
echo "<style>body{font-family:Arial,sans-serif;max-width:700px;margin:50px auto;padding:20px;background:#f5f5f5;}";
echo ".box{background:white;padding:30px;border-radius:12px;box-shadow:0 2px 10px rgba(0,0,0,0.1);}";
echo "h1{color:#0a2a5e;}table{width:100%;border-collapse:collapse;margin:15px 0;}";
echo "td,th{padding:10px;border:1px solid #ddd;text-align:left;}th{background:#0a2a5e;color:white;}";
echo ".ok{color:green;font-size:18px;font-weight:bold;}a.btn{display:inline-block;padding:12px 24px;background:#0a2a5e;color:white;text-decoration:none;border-radius:6px;margin-top:15px;}";
echo "</style></head><body><div class='box'>";
echo "<h1>Setup Complete!</h1>";
echo "<p class='ok'>Database created and seeded successfully.</p>";
echo "<p>Inserted <strong>$insertedCount</strong> new user(s).</p>";
echo "<h3>Login Credentials:</h3>";
echo "<table><tr><th>Role</th><th>Email</th><th>Password</th></tr>";
echo "<tr><td>ADMIN</td><td>admin@pindari.com</td><td>admin123</td></tr>";
echo "<tr><td>HR</td><td>hr@pindari.com</td><td>hr123</td></tr>";
echo "<tr><td>CLIENT</td><td>client@tatamotors.com</td><td>client123</td></tr>";
echo "<tr><td>EMPLOYEE</td><td>employee@gmail.com</td><td>emp123</td></tr>";
echo "</table>";
echo "<p><a class='btn' href='public/'>Visit Website</a> <a class='btn' href='app/login.php' style='background:#d71921;margin-left:10px'>ERP Portal Login</a></p>";
echo "<p style='color:red;font-weight:bold;margin-top:20px'>IMPORTANT: Delete setup.php for security!</p>";
echo "</div></body></html>";
