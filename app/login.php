<?php
require_once __DIR__ . '/../config/config.php';

// If already logged in, redirect to dashboard
if (isset($_SESSION['user_id'])) {
    redirect(APP_URL . '/app/dashboard.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = 'Please enter both email and password.';
    } else {
        try {
            $stmt = db()->prepare("SELECT * FROM users WHERE email = ? AND is_active = 1");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_role'] = $user['role'];
                log_activity($user['id'], 'Logged in');
                set_flash('success', 'Welcome back, ' . $user['name'] . '!');
                redirect(APP_URL . '/app/dashboard.php');
            } else {
                $error = 'Invalid email or password.';
            }
        } catch (Exception $e) {
            $error = 'Login failed. Is the database set up? Run setup.php first.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Pindari ERP Portal</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= APP_URL ?>/app/assets/css/portal.css">
</head>
<body>
<div class="login-page">
    <div class="login-card">
        <div class="login-logo">
            <img src="<?= APP_URL ?>/public/assets/logo.png" alt="Pindari Enterprises" class="logo-img-login">
            <h1>PINDARI ENTERPRISES</h1>
            <p>ERP Client Portal</p>
        </div>
        <h2>Welcome Back</h2>
        <p class="subtitle">Sign in to access your dashboard</p>

        <?php if ($error): ?>
            <div class="flash flash-error"><?= e($error) ?></div>
        <?php endif; ?>

        <?php
        $flash = get_flash();
        if ($flash): ?>
            <div class="flash flash-<?= e($flash['type']) ?>"><?= e($flash['message']) ?></div>
        <?php endif; ?>

        <form method="POST" action="<?= APP_URL ?>/app/login.php">
            <div class="form-group">
                <label>Email Address</label>
                <div class="input-icon">
                    <span class="icon">&#9993;</span>
                    <input type="email" name="email" required placeholder="you@example.com" autofocus value="<?= e($_POST['email'] ?? '') ?>">
                </div>
            </div>
            <div class="form-group">
                <label>Password</label>
                <div class="input-icon">
                    <span class="icon">&#128274;</span>
                    <input type="password" name="password" required placeholder="Enter your password">
                </div>
            </div>
            <button type="submit" class="btn-login">Sign In &#8594;</button>
        </form>

        <div class="login-hint">
            <strong>Demo Credentials:</strong>
            <div class="cred"><span>Admin</span><span>admin@pindari.com / admin123</span></div>
            <div class="cred"><span>HR</span><span>hr@pindari.com / hr123</span></div>
            <div class="cred"><span>Client</span><span>client@tatamotors.com / client123</span></div>
            <div class="cred"><span>Employee</span><span>employee@gmail.com / emp123</span></div>
        </div>

        <p style="text-align:center;margin-top:20px;">
            <a href="<?= APP_URL ?>/public/index.php" style="font-size:14px;color:var(--gray-500);">&larr; Back to Website</a>
        </p>
    </div>
</div>
</body>
</html>
