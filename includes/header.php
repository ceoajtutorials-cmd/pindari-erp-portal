<?php
require_once __DIR__ . '/../config/config.php';
$currentPage = $currentPage ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? APP_NAME) ?> | <?= e(APP_NAME) ?></title>
    <meta name="description" content="Pindari Enterprises - Empowering Workforce Solutions. Manpower outsourcing, staffing, payroll, and compliance services.">
    <link rel="stylesheet" href="<?= APP_URL ?>/public/assets/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
<header class="site-header" id="siteHeader">
    <div class="container header-inner">
        <a href="<?= APP_URL ?>/public/index.php" class="logo">
            <img src="<?= APP_URL ?>/public/assets/logo.png" alt="Pindari Enterprises" class="logo-img">
            <div class="logo-text">
                <span class="logo-name">PINDARI</span>
                <span class="logo-sub">ENTERPRISES</span>
            </div>
        </a>
        <nav class="main-nav" id="mainNav">
            <a href="<?= APP_URL ?>/public/index.php" class="<?= $currentPage === 'home' ? 'active' : '' ?>">Home</a>
            <a href="<?= APP_URL ?>/public/about.php" class="<?= $currentPage === 'about' ? 'active' : '' ?>">About Us</a>
            <a href="<?= APP_URL ?>/public/services.php" class="<?= $currentPage === 'services' ? 'active' : '' ?>">Our Services</a>
            <a href="<?= APP_URL ?>/public/clients.php" class="<?= $currentPage === 'clients' ? 'active' : '' ?>">Clients</a>
            <a href="<?= APP_URL ?>/public/contact.php" class="<?= $currentPage === 'contact' ? 'active' : '' ?>">Contact Us</a>
            <a href="<?= APP_URL ?>/app/login.php" class="nav-cta">ERP Login</a>
        </nav>
        <button class="mobile-toggle" id="mobileToggle" aria-label="Toggle menu">
            <span></span><span></span><span></span>
        </button>
    </div>
</header>
<main>
