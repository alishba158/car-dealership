<?php
require_once dirname(__DIR__) . '/includes/functions.php';
$adminCount = (int) db()->query('SELECT COUNT(*) FROM admins')->fetchColumn();
if ($adminCount > 0) {
    http_response_code(404);
    exit('Initial admin setup is already complete.');
}
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $username = trim((string) ($_POST['username'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');
    $confirm = (string) ($_POST['confirm_password'] ?? '');
    if (!preg_match('/^[A-Za-z0-9_.-]{3,40}$/', $username)) $errors[] = 'Username must be 3–40 letters, numbers, dots, dashes, or underscores.';
    if (strlen($password) < 12) $errors[] = 'Choose a password with at least 12 characters.';
    if ($password !== $confirm) $errors[] = 'The passwords do not match.';
    if (!$errors && (int) db()->query('SELECT COUNT(*) FROM admins')->fetchColumn() === 0) {
        $statement = db()->prepare('INSERT INTO admins (username, password_hash) VALUES (?, ?)');
        $statement->execute([$username, password_hash($password, PASSWORD_DEFAULT)]);
        flash('success', 'Admin account created. Sign in with the username and password you just chose.');
        redirect('admin/login.php');
    }
}
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Initial setup · Northline</title><link rel="stylesheet" href="<?= e(url('assets/css/style.css')) ?>"></head><body class="login-page"><section class="login-card"><a class="brand" href="<?= e(url()) ?>"><span class="brand-mark">N</span><span class="brand-name">NORTHLINE<small>MOTOR CO.</small></span></a><p class="eyebrow">FIRST-RUN SETUP</p><h1>Create your<br><em>admin account.</em></h1><p>This one-time step creates the first administrator. Choose your own credentials; the password is stored as a secure hash.</p><?php if ($errors): ?><div class="notice notice-error"><ul><?php foreach ($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?><form method="post"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><label>Username<input name="username" required minlength="3" maxlength="40" autocomplete="username" value="<?= e($_POST['username'] ?? '') ?>"></label><label>Password<input name="password" type="password" required minlength="12" autocomplete="new-password"></label><label>Confirm password<input name="confirm_password" type="password" required minlength="12" autocomplete="new-password"></label><button class="button button-dark button-wide">Create admin account</button></form><a class="text-link" href="<?= e(url('admin/login.php')) ?>">Back to sign in</a></section></body></html>
