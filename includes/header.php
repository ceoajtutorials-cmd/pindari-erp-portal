<?php
// header.php - Fixed
$currentPage = $currentPage ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? APP_NAME ?> | <?= APP_NAME ?></title>
    <meta name="description" content="Pindari Enterprises - Empowering Workforce Solutions">
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
<header class="site-header" id="site-header">
    <div class="container header-inner">
        <a href="<?= APP_URL ?>/index.php" class="logo">
            <img src="<?= APP_URL ?>/assets/logo.png" alt="Pindari Enterprises" class="logo-img">
            <div class="logo-text">
                <span class="logo-name">PINDARI</span>
                <span class="logo-sub">ENTERPRISES</span>
            </div>
        </a>
        <nav class="main-nav" id="mainNav">
            <a href="<?= APP_URL ?>/index.php" class="<?= $currentPage == 'home' ? 'active' : '' ?>">Home</a>
            <a href="<?= APP_URL ?>/about.php" class="<?= $currentPage == 'about' ? 'active' : '' ?>">About</a>
            <a href="<?= APP_URL ?>/services.php" class="<?= $currentPage == 'services' ? 'active' : '' ?>">Services</a>
            <a href="<?= APP_URL ?>/clients.php" class="<?= $currentPage == 'clients' ? 'active' : '' ?>">Clients</a>
            <a href="<?= APP_URL ?>/contact.php" class="<?= $currentPage == 'contact' ? 'active' : '' ?>">Contact</a>
            <a href="<?= APP_URL ?>/login.php" class="nav-cta">Login</a>
        </nav>
    </div>
</header>
