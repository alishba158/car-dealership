<?php
require_once __DIR__ . '/_init.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
    $status = (string) ($_POST['status'] ?? '');
    if ($id && in_array($status, ['approved', 'rejected', 'pending'], true)) {
        db()->prepare('UPDATE sell_requests SET status = ? WHERE id = ?')->execute([$status, $id]);
        flash('success', 'Sell request status updated.');
    }
    redirect('admin/requests.php');
}
$requests = db()->query('SELECT * FROM sell_requests ORDER BY created_at DESC')->fetchAll();
admin_header('Sell requests');
?>
<section class="admin-content"><div class="admin-welcome"><div><p class="eyebrow">CUSTOMER VEHICLES</p><h1>Sell requests.</h1><p>Every inquiry is a chance to help someone make a good next move.</p></div></div><div class="request-list"><?php foreach ($requests as $request): ?><article class="request-card"><div class="request-top"><div><p class="eyebrow"><?= e(date('M j, Y · g:i a', strtotime($request['created_at']))) ?></p><h2><?= e($request['year'] . ' ' . $request['brand'] . ' ' . $request['model']) ?></h2></div><span class="status-pill status-<?= e($request['status']) ?>"><?= e(ucfirst($request['status'])) ?></span></div><div class="request-details"><?php if ($request['image_path']): ?><a href="<?= e(url($request['image_path'])) ?>" target="_blank" rel="noopener noreferrer"><img src="<?= e(url($request['image_path'])) ?>" alt="Customer car"></a><?php endif; ?><div><p><strong><?= e($request['customer_name']) ?></strong></p><p><a href="tel:<?= e(preg_replace('/\D+/', '', $request['phone'])) ?>"><?= e($request['phone']) ?></a> · <a href="mailto:<?= e($request['email']) ?>"><?= e($request['email']) ?></a></p><p><?= number_format((int) $request['mileage']) ?> mi · Asking <?= e(money($request['expected_price'])) ?></p><p><?= nl2br(e($request['description'] ?: 'No additional notes.')) ?></p></div></div><form method="post" class="request-actions"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="id" value="<?= (int) $request['id'] ?>"><button name="status" value="approved" class="button button-small">Approve</button><button name="status" value="rejected" class="button button-small button-muted">Reject</button><?php if ($request['status'] !== 'pending'): ?><button name="status" value="pending" class="text-link">Mark pending</button><?php endif; ?></form></article><?php endforeach; ?><?php if (!$requests): ?><div class="admin-panel admin-empty">No sell requests yet. New submissions will appear here.</div><?php endif; ?></div></section>
<?php admin_footer(); ?>
