<?php
require_once dirname(__DIR__) . '/includes/functions.php';
if (!empty($_SESSION['admin_id'])) redirect('admin/dashboard.php');
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $username = trim((string) ($_POST['username'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');
    $statement = db()->prepare('SELECT id, username, password_hash FROM admins WHERE username = ? LIMIT 1');
    $statement->execute([$username]);
    $admin = $statement->fetch();
    if ($admin && password_verify($password, $admin['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['admin_id'] = (int) $admin['id'];
        $_SESSION['admin_username'] = $admin['username'];
        redirect('admin/dashboard.php');
    }
    $errors[] = 'That username and password combination was not recognized.';
}
$notice = take_flash();
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Admin sign in · Northline</title><link rel="stylesheet" href="<?= e(url('assets/css/style.css')) ?>"></head><body class="login-page"><section class="login-card"><a class="brand" href="<?= e(url()) ?>"><span class="brand-mark">N</span><span class="brand-name">NORTHLINE<small>MOTOR CO.</small></span></a><p class="eyebrow">ADMIN STUDIO</p><h1>Good to see<br><em>you again.</em></h1><p>Sign in to manage your collection and customer inquiries.</p><?php if ($notice): ?><div class="notice notice-<?= e($notice['type']) ?>"><?= e($notice['message']) ?></div><?php endif; ?><?php if ($errors): ?><div class="notice notice-error"><?= e($errors[0]) ?></div><?php endif; ?><form method="post"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><label>Username<input name="username" required autocomplete="username" value="<?= e($_POST['username'] ?? '') ?>"></label><label>Password<input name="password" type="password" required autocomplete="current-password"></label><button class="button button-dark button-wide">Sign in <span>↗</span></button></form><?php if ((int) db()->query('SELECT COUNT(*) FROM admins')->fetchColumn() === 0): ?><p class="setup-link">First visit? <a href="<?= e(url('admin/setup.php')) ?>">Create the initial admin account</a>.</p><?php endif; ?><a class="text-link" href="<?= e(url()) ?>">← Back to the website</a></section></body></html>
