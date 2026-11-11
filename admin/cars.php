<?php
require_once __DIR__ . '/_init.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
    $action = (string) ($_POST['action'] ?? '');
    if ($id && $action === 'status') {
        $status = (string) ($_POST['status'] ?? '');
        if (in_array($status, ['available', 'sold'], true)) {
            db()->prepare('UPDATE cars SET status = ? WHERE id = ?')->execute([$status, $id]);
            flash('success', 'Car status updated.');
        }
    } elseif ($id && $action === 'delete') {
        $pathsStatement = db()->prepare('SELECT image_path FROM car_images WHERE car_id = ?');
        $pathsStatement->execute([$id]);
        $paths = $pathsStatement->fetchAll(PDO::FETCH_COLUMN);
        db()->prepare('DELETE FROM cars WHERE id = ?')->execute([$id]);
        remove_car_images($paths);
        flash('success', 'Car deleted from the collection.');
    }
    redirect('admin/cars.php');
}
$cars = db()->query('SELECT c.*, (SELECT ci.image_path FROM car_images ci WHERE ci.car_id = c.id ORDER BY ci.sort_order, ci.id LIMIT 1) AS image_path FROM cars c ORDER BY c.created_at DESC')->fetchAll();
admin_header('Cars');
?>
<section class="admin-content"><div class="admin-welcome"><div><p class="eyebrow">YOUR COLLECTION</p><h1>Cars, with care.</h1><p>Add, update, and curate the cars you have on the floor.</p></div><a href="<?= e(url('admin/add-car.php')) ?>" class="button button-dark">Add a car <span>+</span></a></div><section class="admin-panel"><div class="admin-table-wrap"><table class="admin-table"><thead><tr><th>Vehicle</th><th>Year / Mileage</th><th>Price</th><th>Status</th><th>Actions</th></tr></thead><tbody><?php foreach ($cars as $car): ?><tr><td><div class="admin-car-cell"><img src="<?= e(url(car_image($car))) ?>" alt=""><span><?= e($car['brand'] . ' ' . $car['model']) ?></span></div></td><td><?= e($car['year']) ?> · <?= number_format((int) $car['mileage']) ?> mi</td><td><?= e(money($car['price'])) ?></td><td><span class="status-pill status-<?= e($car['status']) ?>"><?= e(ucfirst($car['status'])) ?></span></td><td><div class="row-actions"><a href="<?= e(url('admin/edit-car.php?id=' . (int) $car['id'])) ?>">Edit</a><form method="post"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="id" value="<?= (int) $car['id'] ?>"><input type="hidden" name="action" value="status"><input type="hidden" name="status" value="<?= $car['status'] === 'available' ? 'sold' : 'available' ?>"><button><?= $car['status'] === 'available' ? 'Mark sold' : 'Mark available' ?></button></form><form method="post" onsubmit="return confirm('Delete this car and its images? This cannot be undone.');"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="id" value="<?= (int) $car['id'] ?>"><input type="hidden" name="action" value="delete"><button class="delete-link">Delete</button></form></div></td></tr><?php endforeach; ?></tbody></table><?php if (!$cars): ?><p class="admin-empty">Your collection is ready for its first car.</p><?php endif; ?></div></section></section>
<?php admin_footer(); ?>
