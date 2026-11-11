<?php
require_once __DIR__ . '/_init.php';
$stats = [
    'cars' => (int) db()->query('SELECT COUNT(*) FROM cars')->fetchColumn(),
    'available' => (int) db()->query("SELECT COUNT(*) FROM cars WHERE status = 'available'")->fetchColumn(),
    'requests' => (int) db()->query('SELECT COUNT(*) FROM sell_requests')->fetchColumn(),
    'messages' => (int) db()->query('SELECT COUNT(*) FROM messages')->fetchColumn(),
];
$recent = db()->query('SELECT id, customer_name, brand, model, created_at, status FROM sell_requests ORDER BY created_at DESC LIMIT 5')->fetchAll();
admin_header('Overview');
?>
<section class="admin-content"><div class="admin-welcome"><div><p class="eyebrow">YOUR NORTHLINE AT A GLANCE</p><h1>A little overview.</h1><p>Here’s what’s happening with your dealership today.</p></div><a href="<?= e(url('admin/add-car.php')) ?>" class="button button-dark">Add a car <span>+</span></a></div>
<div class="stat-grid"><a class="stat-card" href="<?= e(url('admin/cars.php')) ?>"><span>ALL CARS</span><strong><?= $stats['cars'] ?></strong><small>On your lot</small></a><a class="stat-card" href="<?= e(url('admin/cars.php')) ?>"><span>AVAILABLE</span><strong><?= $stats['available'] ?></strong><small>Ready for a new home</small></a><a class="stat-card" href="<?= e(url('admin/requests.php')) ?>"><span>SELL REQUESTS</span><strong><?= $stats['requests'] ?></strong><small>From potential sellers</small></a><a class="stat-card" href="<?= e(url('admin/messages.php')) ?>"><span>MESSAGES</span><strong><?= $stats['messages'] ?></strong><small>Customer conversations</small></a></div>
<section class="admin-panel"><div class="admin-panel-heading"><div><p class="eyebrow">RECENT ACTIVITY</p><h2>Latest sell requests</h2></div><a class="text-link" href="<?= e(url('admin/requests.php')) ?>">View all requests ↗</a></div><?php if ($recent): ?><div class="admin-table-wrap"><table class="admin-table"><thead><tr><th>Customer</th><th>Vehicle</th><th>Received</th><th>Status</th><th></th></tr></thead><tbody><?php foreach ($recent as $request): ?><tr><td><?= e($request['customer_name']) ?></td><td><?= e($request['brand'] . ' ' . $request['model']) ?></td><td><?= e(date('M j, Y', strtotime($request['created_at']))) ?></td><td><span class="status-pill status-<?= e($request['status']) ?>"><?= e(ucfirst($request['status'])) ?></span></td><td><a href="<?= e(url('admin/requests.php')) ?>">Review →</a></td></tr><?php endforeach; ?></tbody></table></div><?php else: ?><p class="admin-empty">No sell requests yet. They’ll show up here when they arrive.</p><?php endif; ?></section></section>
<?php admin_footer(); ?>
