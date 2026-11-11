<?php
require_once dirname(__DIR__) . '/includes/functions.php';
require_admin();

function admin_header(string $title): void
{
    $notice = take_flash();
    ?>
    <!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title><?= e($title) ?> · Northline Admin</title><link rel="stylesheet" href="<?= e(url('assets/css/style.css')) ?>"></head><body class="admin-body">
    <aside class="admin-sidebar"><a class="brand brand-light" href="<?= e(url('admin/dashboard.php')) ?>"><span class="brand-mark">N</span><span class="brand-name">NORTHLINE<small>ADMIN STUDIO</small></span></a><p class="admin-side-label">WORKSPACE</p><nav><a href="<?= e(url('admin/dashboard.php')) ?>">Overview</a><a href="<?= e(url('admin/cars.php')) ?>">Cars</a><a href="<?= e(url('admin/requests.php')) ?>">Sell requests</a><a href="<?= e(url('admin/messages.php')) ?>">Messages</a></nav><div class="admin-side-bottom"><a href="<?= e(url()) ?>" target="_blank" rel="noopener noreferrer">View website ↗</a><form method="post" action="<?= e(url('admin/logout.php')) ?>"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><button type="submit">Sign out</button></form></div></aside>
    <main class="admin-main"><header class="admin-topbar"><div><p class="eyebrow">NORTHLINE MOTOR CO.</p><span><?= e($title) ?></span></div><span class="admin-user"><?= e($_SESSION['admin_username'] ?? 'Admin') ?></span></header>
    <?php if ($notice): ?><div class="notice notice-<?= e($notice['type']) ?>"><?= e($notice['message']) ?></div><?php endif; ?>
    <?php
}

function admin_footer(): void
{
    ?></main></body></html><?php
}

function remove_car_images(array $paths): void
{
    $prefix = 'uploads/cars/';
    foreach ($paths as $path) {
        if (str_starts_with((string) $path, $prefix)) {
            $file = UPLOAD_DIR . DIRECTORY_SEPARATOR . basename($path);
            if (is_file($file)) {
                unlink($file);
            }
        }
    }
}
