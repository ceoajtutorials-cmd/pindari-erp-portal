<?php
/**
 * ERP Portal - Authentication Guard
 * Include at top of every protected page.
 */

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    redirect(APP_URL . '/app/login.php');
}

// Load current user
$currentUserId = $_SESSION['user_id'];
try {
    $stmt = db()->prepare("SELECT * FROM users WHERE id = ? AND is_active = 1");
    $stmt->execute([$currentUserId]);
    $currentUser = $stmt->fetch();
} catch (Exception $e) {
    $currentUser = false;
}

if (!$currentUser) {
    session_destroy();
    redirect(APP_URL . '/app/login.php');
}

// Define role-based navigation
function get_nav_items($role) {
    $nav = [];

    $nav[] = ['section' => 'Main'];
    $nav[] = ['url' => 'dashboard.php', 'icon' => '&#8962;', 'label' => 'Dashboard'];

    if (in_array($role, ['admin', 'hr'])) {
        $nav[] = ['section' => 'Workforce'];
        $nav[] = ['url' => 'employees/index.php', 'icon' => '&#128100;', 'label' => 'Employees'];
        $nav[] = ['url' => 'placements/index.php', 'icon' => '&#128269;', 'label' => 'Placements'];
        $nav[] = ['url' => 'payroll/index.php', 'icon' => '&#128176;', 'label' => 'Payroll'];
        $nav[] = ['url' => 'compliance/index.php', 'icon' => '&#128220;', 'label' => 'Compliance'];
    }

    if ($role === 'admin') {
        $nav[] = ['section' => 'Management'];
        $nav[] = ['url' => 'clients/index.php', 'icon' => '&#127970;', 'label' => 'Clients'];
        $nav[] = ['url' => 'reports/index.php', 'icon' => '&#128202;', 'label' => 'Reports'];
    }

    if ($role === 'client') {
        $nav[] = ['section' => 'Workforce'];
        $nav[] = ['url' => 'employees/index.php', 'icon' => '&#128100;', 'label' => 'My Workforce'];
        $nav[] = ['url' => 'placements/index.php', 'icon' => '&#128269;', 'label' => 'Placements'];
        $nav[] = ['url' => 'payroll/index.php', 'icon' => '&#128176;', 'label' => 'Payroll'];
    }

    if ($role === 'employee') {
        $nav[] = ['section' => 'My Info'];
        $nav[] = ['url' => 'employees/profile.php', 'icon' => '&#128100;', 'label' => 'My Profile'];
        $nav[] = ['url' => 'payroll/payslips.php', 'icon' => '&#128176;', 'label' => 'My Payslips'];
    }

    return $nav;
}

/**
 * Check if current user has required role(s)
 */
function require_role(...$roles) {
    global $currentUser;
    if (!in_array($currentUser['role'], $roles)) {
        set_flash('error', 'You do not have permission to access that page.');
        redirect(APP_URL . '/app/dashboard.php');
    }
}
