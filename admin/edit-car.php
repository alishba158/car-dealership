<?php
require_once __DIR__ . '/_init.php';
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$car = $id ? get_car((int) $id) : null;
if (!$car) { http_response_code(404); exit('Car not found.'); }
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $brand = trim((string) ($_POST['brand'] ?? ''));
    $model = trim((string) ($_POST['model'] ?? ''));
    $year = filter_var($_POST['year'] ?? null, FILTER_VALIDATE_INT);
    $mileage = filter_var($_POST['mileage'] ?? null, FILTER_VALIDATE_INT);
    $price = filter_var($_POST['price'] ?? null, FILTER_VALIDATE_FLOAT);
    if ($brand === '' || strlen($brand) > 80 || $model === '' || strlen($model) > 100) $errors[] = 'Make and model are required.';
    if (!$year || $year < 1900 || $year > (int) date('Y') + 1) $errors[] = 'Enter a valid model year.';
    if ($mileage === false || $mileage < 0 || $mileage > 2000000) $errors[] = 'Enter a valid mileage.';
    if ($price === false || $price <= 0) $errors[] = 'Enter a valid price.';
    if (!in_array($_POST['status'] ?? '', ['available', 'sold'], true)) $errors[] = 'Choose a valid status.';
    foreach (['body_type' => 50, 'transmission' => 50, 'fuel_type' => 50, 'color' => 50] as $field => $limit) {
        if (strlen(trim((string) ($_POST[$field] ?? ''))) > $limit) $errors[] = ucfirst(str_replace('_', ' ', $field)) . ' is too long.';
    }
    if (strlen((string) ($_POST['description'] ?? '')) > 5000) $errors[] = 'Description must be under 5,000 characters.';
    if (!preg_match('/^[0-9+().\\s-]{7,25}$/', (string) ($_POST['contact_phone'] ?? ''))) $errors[] = 'Enter a valid contact phone number.';
    if (!$errors) {
        try {
            $images = upload_car_images();
            $statement = db()->prepare('UPDATE cars SET brand=?, model=?, year=?, mileage=?, price=?, description=?, body_type=?, transmission=?, fuel_type=?, color=?, status=?, featured=?, contact_phone=? WHERE id=?');
            $statement->execute([$brand, $model, $year, $mileage, $price, trim((string) ($_POST['description'] ?? '')), trim((string) ($_POST['body_type'] ?? '')), trim((string) ($_POST['transmission'] ?? '')), trim((string) ($_POST['fuel_type'] ?? '')), trim((string) ($_POST['color'] ?? '')), $_POST['status'], isset($_POST['featured']) ? 1 : 0, trim((string) ($_POST['contact_phone'] ?? '')), $id]);
            if ($images) insert_car_images(db(), (int) $id, $images);
            flash('success', 'Car details updated.');
            redirect('admin/cars.php');
        } catch (RuntimeException $error) { $errors[] = $error->getMessage(); }
    }
}
$gallery = car_gallery((int) $id);
admin_header('Edit car');
?>
<section class="admin-content"><div class="admin-welcome"><div><p class="eyebrow">COLLECTION DETAILS</p><h1>Edit this car.</h1><p>Changes will appear on the website as soon as you save.</p></div><a class="text-link" href="<?= e(url('admin/cars.php')) ?>">← Back to cars</a></div><form class="admin-form admin-panel" method="post" enctype="multipart/form-data"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><?php if ($errors): ?><div class="notice notice-error"><ul><?php foreach ($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?><div class="form-grid"><label>Make<input name="brand" required maxlength="80" value="<?= e($_POST['brand'] ?? $car['brand']) ?>"></label><label>Model<input name="model" required maxlength="100" value="<?= e($_POST['model'] ?? $car['model']) ?>"></label><label>Year<input name="year" type="number" min="1900" max="<?= (int) date('Y') + 1 ?>" required value="<?= e($_POST['year'] ?? $car['year']) ?>"></label><label>Mileage (mi)<input name="mileage" type="number" min="0" required value="<?= e($_POST['mileage'] ?? $car['mileage']) ?>"></label><label>Price ($)<input name="price" type="number" min="1" step="1" required value="<?= e($_POST['price'] ?? $car['price']) ?>"></label><label>Body type<input name="body_type" maxlength="50" value="<?= e($_POST['body_type'] ?? $car['body_type']) ?>"></label><label>Transmission<input name="transmission" maxlength="50" value="<?= e($_POST['transmission'] ?? $car['transmission']) ?>"></label><label>Fuel type<input name="fuel_type" maxlength="50" value="<?= e($_POST['fuel_type'] ?? $car['fuel_type']) ?>"></label><label>Exterior color<input name="color" maxlength="50" value="<?= e($_POST['color'] ?? $car['color']) ?>"></label><label>Contact phone<input name="contact_phone" maxlength="25" value="<?= e($_POST['contact_phone'] ?? $car['contact_phone']) ?>"></label><label>Status<select name="status"><option value="available" <?= $car['status'] === 'available' ? 'selected' : '' ?>>Available</option><option value="sold" <?= $car['status'] === 'sold' ? 'selected' : '' ?>>Sold</option></select></label><label class="check-label"><input type="checkbox" name="featured" <?= $car['featured'] ? 'checked' : '' ?>> Feature on homepage</label><label class="full-field">Description<textarea name="description" rows="5" maxlength="5000"><?= e($_POST['description'] ?? $car['description']) ?></textarea></label><?php if ($gallery): ?><div class="full-field current-images"><span>Current images</span><div><?php foreach ($gallery as $image): ?><img src="<?= e(url($image['image_path'])) ?>" alt="Current vehicle photo"><?php endforeach; ?></div><small>New photos will be added to the gallery.</small></div><?php endif; ?><label class="full-field">Add photos <span class="upload-hint">Up to 8 images · JPG, PNG, WebP · 5 MB each</span><input type="file" name="images[]" accept="image/jpeg,image/png,image/webp" multiple></label></div><button class="button button-dark">Save changes <span>↗</span></button></form></section>
<?php admin_footer(); ?>
