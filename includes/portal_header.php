<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/auth.php';

$navItems = get_nav_items($currentUser['role']);
$currentPageFile = $currentPageFile ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? 'Dashboard') ?> | Pindari ERP</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= APP_URL ?>/app/assets/css/portal.css">
</head>
<body>
<div class="portal-layout">
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <img src="<?= APP_URL ?>/public/assets/logo.png" alt="Pindari" class="logo-img">
            <div class="logo-text">
                <div class="logo-name">PINDARI</div>
                <div class="logo-sub">ERP PORTAL</div>
            </div>
        </div>
        <nav class="sidebar-nav">
            <?php
            $currentSection = '';
            foreach ($navItems as $item):
                if (isset($item['section'])):
                    $currentSection = $item['section'];
            ?>
                <div class="nav-section"><?= e($item['section']) ?></div>
            <?php else: ?>
                <a href="<?= APP_URL ?>/app/<?= e($item['url']) ?>" class="<?= $currentPageFile === $item['url'] ? 'active' : '' ?>">
                    <span class="nav-icon"><?= $item['icon'] ?></span>
                    <span><?= e($item['label']) ?></span>
                </a>
            <?php
                endif;
            endforeach;
            ?>
        </nav>
        <div class="sidebar-footer">
            <a href="<?= APP_URL ?>/app/logout.php">
                <span class="nav-icon">&#8634;</span>
                <span>Logout</span>
            </a>
        </div>
    </aside>

    <div class="portal-main">
        <header class="portal-topbar">
            <div class="topbar-left">
                <button class="sidebar-toggle" id="sidebarToggle">&#9776;</button>
                <h1><?= e($pageTitle ?? 'Dashboard') ?></h1>
            </div>
            <div class="topbar-right">
                <span class="role-badge role-<?= e($currentUser['role']) ?>"><?= ucfirst(e($currentUser['role'])) ?></span>
                <div class="topbar-user">
                    <div class="user-avatar"><?= strtoupper(substr($currentUser['name'], 0, 1)) ?></div>
                    <div class="user-info">
                        <div class="user-name"><?= e($currentUser['name']) ?></div>
                        <div class="user-role"><?= e($currentUser['email']) ?></div>
                    </div>
                </div>
            </div>
        </header>
        <div class="portal-content">
            <?php
            $flash = get_flash();
            if ($flash): ?>
                <div class="flash flash-<?= e($flash['type']) ?>"><?= e($flash['message']) ?></div>
            <?php endif; ?>
