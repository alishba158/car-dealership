<?php
require_once __DIR__ . '/_init.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
    if ($id) {
        db()->prepare('DELETE FROM messages WHERE id = ?')->execute([$id]);
        flash('success', 'Message deleted.');
    }
    redirect('admin/messages.php');
}
$messages = db()->query('SELECT * FROM messages ORDER BY created_at DESC')->fetchAll();
admin_header('Messages');
?>
<section class="admin-content"><div class="admin-welcome"><div><p class="eyebrow">INBOX</p><h1>Messages.</h1><p>Notes from people who’d like to talk cars.</p></div></div><div class="message-list"><?php foreach ($messages as $message): ?><article class="message-card"><div class="message-head"><div><p class="eyebrow"><?= e(date('M j, Y · g:i a', strtotime($message['created_at']))) ?></p><h2><?= e($message['subject']) ?></h2></div><form method="post" onsubmit="return confirm('Delete this message?');"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="id" value="<?= (int) $message['id'] ?>"><button class="delete-link">Delete</button></form></div><p><?= nl2br(e($message['message'])) ?></p><div class="message-sender"><strong><?= e($message['name']) ?></strong> · <a href="mailto:<?= e($message['email']) ?>"><?= e($message['email']) ?></a><?php if ($message['phone']): ?> · <a href="tel:<?= e(preg_replace('/\D+/', '', $message['phone'])) ?>"><?= e($message['phone']) ?></a><?php endif; ?></div></article><?php endforeach; ?><?php if (!$messages): ?><div class="admin-panel admin-empty">Your inbox is clear. Contact form messages will show up here.</div><?php endif; ?></div></section>
<?php admin_footer(); ?>
