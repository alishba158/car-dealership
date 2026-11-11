<?php
require_once __DIR__ . '/includes/functions.php';
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $name = trim((string) ($_POST['customer_name'] ?? ''));
    $phone = trim((string) ($_POST['phone'] ?? ''));
    $email = trim((string) ($_POST['email'] ?? ''));
    $brand = trim((string) ($_POST['brand'] ?? ''));
    $model = trim((string) ($_POST['model'] ?? ''));
    $year = filter_var($_POST['year'] ?? null, FILTER_VALIDATE_INT);
    $mileage = filter_var($_POST['mileage'] ?? null, FILTER_VALIDATE_INT);
    $price = filter_var($_POST['expected_price'] ?? null, FILTER_VALIDATE_FLOAT);
    $description = trim((string) ($_POST['description'] ?? ''));
    if ($name === '' || strlen($name) > 120) $errors[] = 'Please enter your name.';
    if (!preg_match('/^[0-9+().\\s-]{7,25}$/', $phone)) $errors[] = 'Please enter a valid phone number.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Please enter a valid email address.';
    if ($brand === '' || strlen($brand) > 80 || $model === '' || strlen($model) > 100) $errors[] = 'Please enter the car make and model.';
    if (!$year || $year < 1900 || $year > (int) date('Y') + 1) $errors[] = 'Please enter a valid manufacturing year.';
    if ($mileage === false || $mileage < 0 || $mileage > 2000000) $errors[] = 'Please enter a valid mileage.';
    if ($price === false || $price <= 0 || $price > 10000000) $errors[] = 'Please enter a valid expected price.';
    if (strlen($description) > 5000) $errors[] = 'Please keep the description under 5,000 characters.';
    if (!$errors) {
        try {
            $images = upload_car_images('car_image', 1);
            $statement = db()->prepare("INSERT INTO sell_requests (customer_name, phone, email, brand, model, year, mileage, expected_price, description, image_path, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')");
            $statement->execute([$name, $phone, $email, $brand, $model, $year, $mileage, $price, $description, $images[0] ?? null]);
            flash('success', 'Thanks for reaching out. Our team will be in touch shortly.');
            redirect('sell-car.php');
        } catch (RuntimeException $error) { $errors[] = $error->getMessage(); }
    }
}
$pageTitle = 'Sell your car';
$activePage = 'sell';
require __DIR__ . '/includes/header.php';
?>
<section class="page-hero sell-hero"><div class="container"><p class="eyebrow light-eyebrow"><span class="eyebrow-line"></span> A GOOD CAR DESERVES A GOOD NEXT CHAPTER</p><h1>Your next move<br>starts <em>here.</em></h1><p>A thoughtful, straightforward way to sell. Tell us about your car and a real person from our team will be in touch.</p></div></section>
<section class="container form-section"><div class="form-intro"><p class="eyebrow">SELL WITH NORTHLINE</p><h2>Let's start with<br>the <em>details.</em></h2><p>Share a few details below. No commitment, no awkward calls — just a fair conversation about what your car is worth.</p><div class="process-note"><span>01</span><p><strong>Tell us about it.</strong><br>A few basics help us get to know your car.</p></div><div class="process-note"><span>02</span><p><strong>We take a closer look.</strong><br>Our team will follow up with a straightforward valuation.</p></div><div class="process-note"><span>03</span><p><strong>You decide what feels right.</strong><br>There's never any pressure to move forward.</p></div></div>
<form class="form-card" method="post" enctype="multipart/form-data">
    <?php if ($errors): ?><div class="notice notice-error"><ul><?php foreach ($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
    <h3>Your details</h3><div class="form-grid"><label>Full name<input required name="customer_name" maxlength="120" value="<?= e($_POST['customer_name'] ?? '') ?>"></label><label>Phone number<input required name="phone" type="tel" maxlength="25" value="<?= e($_POST['phone'] ?? '') ?>"></label><label class="full-field">Email address<input required name="email" type="email" maxlength="190" value="<?= e($_POST['email'] ?? '') ?>"></label></div>
    <h3 class="form-group-title">A little about your car</h3><div class="form-grid"><label>Make<input required name="brand" maxlength="80" placeholder="e.g. Volvo" value="<?= e($_POST['brand'] ?? '') ?>"></label><label>Model<input required name="model" maxlength="100" placeholder="e.g. XC60" value="<?= e($_POST['model'] ?? '') ?>"></label><label>Manufacturing year<input required name="year" type="number" min="1900" max="<?= (int) date('Y') + 1 ?>" value="<?= e($_POST['year'] ?? '') ?>"></label><label>Current mileage<input required name="mileage" type="number" min="0" max="2000000" placeholder="e.g. 32,000" value="<?= e($_POST['mileage'] ?? '') ?>"></label><label class="full-field">Expected price ($)<input required name="expected_price" type="number" min="1" step="1" value="<?= e($_POST['expected_price'] ?? '') ?>"></label><label class="full-field">Tell us anything else<textarea name="description" rows="4" maxlength="5000" placeholder="Trim, condition, service history..."><?= e($_POST['description'] ?? '') ?></textarea></label><label class="full-field upload-field">Car photo <span class="upload-hint">JPG, PNG, WebP · up to 5 MB</span><input name="car_image[]" type="file" accept="image/jpeg,image/png,image/webp"></label></div>
    <button class="button button-dark button-wide" type="submit">Send my car details <span>↗</span></button><p class="form-privacy">Your details are only used to respond to this inquiry.</p>
</form></section>
<?php require __DIR__ . '/includes/footer.php'; ?>
