<?php
require_once __DIR__ . '/_init.php';
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
            $pdo = db();
            $pdo->beginTransaction();
            $statement = $pdo->prepare('INSERT INTO cars (brand, model, year, mileage, price, description, body_type, transmission, fuel_type, color, status, featured, contact_phone) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
            $statement->execute([$brand, $model, $year, $mileage, $price, trim((string) ($_POST['description'] ?? '')), trim((string) ($_POST['body_type'] ?? '')), trim((string) ($_POST['transmission'] ?? '')), trim((string) ($_POST['fuel_type'] ?? '')), trim((string) ($_POST['color'] ?? '')), $_POST['status'], isset($_POST['featured']) ? 1 : 0, trim((string) ($_POST['contact_phone'] ?? ''))]);
            insert_car_images($pdo, (int) $pdo->lastInsertId(), $images);
            $pdo->commit();
            flash('success', 'Your car has been added to the collection.');
            redirect('admin/cars.php');
        } catch (RuntimeException $error) { $errors[] = $error->getMessage(); }
    }
}
admin_header('Add a car');
?>
<section class="admin-content"><div class="admin-welcome"><div><p class="eyebrow">GROW THE COLLECTION</p><h1>Add a car.</h1><p>Give each car the details and images it deserves.</p></div><a class="text-link" href="<?= e(url('admin/cars.php')) ?>">← Back to cars</a></div><form class="admin-form admin-panel" method="post" enctype="multipart/form-data"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><?php if ($errors): ?><div class="notice notice-error"><ul><?php foreach ($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?><div class="form-grid"><label>Make<input name="brand" required maxlength="80" value="<?= e($_POST['brand'] ?? '') ?>"></label><label>Model<input name="model" required maxlength="100" value="<?= e($_POST['model'] ?? '') ?>"></label><label>Year<input name="year" type="number" min="1900" max="<?= (int) date('Y') + 1 ?>" required value="<?= e($_POST['year'] ?? '') ?>"></label><label>Mileage (mi)<input name="mileage" type="number" min="0" required value="<?= e($_POST['mileage'] ?? '') ?>"></label><label>Price ($)<input name="price" type="number" min="1" step="1" required value="<?= e($_POST['price'] ?? '') ?>"></label><label>Body type<input name="body_type" maxlength="50" placeholder="SUV, sedan, coupe..."></label><label>Transmission<input name="transmission" maxlength="50" placeholder="Automatic"></label><label>Fuel type<input name="fuel_type" maxlength="50" placeholder="Gasoline, hybrid..."></label><label>Exterior color<input name="color" maxlength="50"></label><label>Contact phone<input name="contact_phone" maxlength="25" value="+15550142880"></label><label>Status<select name="status"><option value="available">Available</option><option value="sold">Sold</option></select></label><label class="check-label"><input type="checkbox" name="featured"> Feature on homepage</label><label class="full-field">Description<textarea name="description" rows="5" maxlength="5000"></textarea></label><label class="full-field">Photos <span class="upload-hint">Up to 8 images · JPG, PNG, WebP · 5 MB each</span><input type="file" name="images[]" accept="image/jpeg,image/png,image/webp" multiple></label></div><button class="button button-dark">Save car <span>↗</span></button></form></section>
<?php admin_footer(); ?>
