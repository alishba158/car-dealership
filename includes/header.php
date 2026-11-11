<?php
require_once __DIR__ . '/functions.php';
$pageTitle = $pageTitle ?? 'Thoughtfully chosen. Ready for the road.';
$activePage = $activePage ?? '';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f5f4f1">
    <title><?= e($pageTitle) ?> | <?= e(APP_NAME) ?></title>
    <link rel="stylesheet" href="<?= e(url('assets/css/style.css')) ?>">
    <script defer src="<?= e(url('assets/js/main.js')) ?>"></script>
</head>
<body>
<div class="announcement"><span>Curated cars. Considered ownership.</span><a href="<?= e(url('sell-car.php')) ?>">Thinking of selling? Start here <span aria-hidden="true">↗</span></a></div>
<header class="site-header">
    <div class="container nav-wrap">
        <a class="brand" href="<?= e(url()) ?>" aria-label="<?= e(APP_NAME) ?> home"><span class="brand-mark">N</span><span class="brand-name">NORTHLINE<small>MOTOR CO.</small></span></a>
        <button class="menu-toggle" type="button" aria-expanded="false" aria-label="Open navigation"><span></span><span></span></button>
        <nav class="main-nav" aria-label="Main navigation">
            <a class="<?= $activePage === 'home' ? 'active' : '' ?>" href="<?= e(url()) ?>">Home</a>
            <a class="<?= $activePage === 'cars' ? 'active' : '' ?>" href="<?= e(url('buy-cars.php')) ?>">The Collection</a>
            <a class="<?= $activePage === 'sell' ? 'active' : '' ?>" href="<?= e(url('sell-car.php')) ?>">Sell your car</a>
            <a class="<?= $activePage === 'about' ? 'active' : '' ?>" href="<?= e(url('about.php')) ?>">Our story</a>
            <a class="<?= $activePage === 'contact' ? 'active' : '' ?>" href="<?= e(url('contact.php')) ?>">Contact</a>
        </nav>
        <a class="nav-cta" href="<?= e(url('buy-cars.php')) ?>">Find your car <span aria-hidden="true">↗</span></a>
    </div>
</header>
<main>
<?php if ($notice = take_flash()): ?>
    <div class="container"><div class="notice notice-<?= e($notice['type']) ?>" role="status"><?= e($notice['message']) ?></div></div>
<?php endif; ?>
