<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/config/db.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_set_cookie_params([
        'httponly' => true,
        'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'samesite' => 'Lax',
    ]);
    session_start();
}

function url(string $path = ''): string
{
    return rtrim(APP_BASE_URL, '/') . ($path === '' ? '/' : '/' . ltrim($path, '/'));
}

function e(string|int|float|null $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf(): void
{
    $submitted = $_POST['csrf_token'] ?? '';
    if (!is_string($submitted) || !hash_equals(csrf_token(), $submitted)) {
        http_response_code(419);
        exit('Your session expired. Please go back, refresh the page, and try again.');
    }
}

function flash(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function take_flash(): ?array
{
    $message = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $message;
}

function redirect(string $path): never
{
    header('Location: ' . url($path));
    exit;
}

function require_admin(): void
{
    if (empty($_SESSION['admin_id'])) {
        flash('error', 'Please sign in to access the admin area.');
        redirect('admin/login.php');
    }
}

function money(float|int|string $amount): string
{
    return '$' . number_format((float) $amount, 0);
}

function car_image(array $car): string
{
    return ($car['image_path'] ?? '') ?: 'assets/images/car-1.jpg';
}

function upload_car_images(string $field = 'images', int $maxFiles = 8): array
{
    if (!isset($_FILES[$field]) || !is_array($_FILES[$field]['name'])) {
        return [];
    }

    $allowed = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
    ];
    $saved = [];
    $count = count($_FILES[$field]['name']);
    if ($count > $maxFiles) {
        throw new RuntimeException('Please upload no more than ' . $maxFiles . ' image(s) at a time.');
    }

    if (!is_dir(UPLOAD_DIR) && !mkdir(UPLOAD_DIR, 0755, true) && !is_dir(UPLOAD_DIR)) {
        throw new RuntimeException('The image upload folder could not be created.');
    }

    for ($i = 0; $i < $count; $i++) {
        $error = $_FILES[$field]['error'][$i] ?? UPLOAD_ERR_NO_FILE;
        if ($error === UPLOAD_ERR_NO_FILE) {
            continue;
        }
        if ($error !== UPLOAD_ERR_OK) {
            throw new RuntimeException('One of the images could not be uploaded.');
        }
        $tmp = $_FILES[$field]['tmp_name'][$i];
        $size = (int) $_FILES[$field]['size'][$i];
        $mime = (new finfo(FILEINFO_MIME_TYPE))->file($tmp);
        if ($size < 1 || $size > MAX_UPLOAD_BYTES || !isset($allowed[$mime]) || @getimagesize($tmp) === false) {
            throw new RuntimeException('Images must be JPG, PNG, or WebP and no larger than 5 MB each.');
        }
        $filename = bin2hex(random_bytes(16)) . '.' . $allowed[$mime];
        if (!move_uploaded_file($tmp, UPLOAD_DIR . DIRECTORY_SEPARATOR . $filename)) {
            throw new RuntimeException('An uploaded image could not be saved.');
        }
        $saved[] = 'uploads/cars/' . $filename;
    }

    return $saved;
}

function insert_car_images(PDO $pdo, int $carId, array $images): void
{
    $statement = $pdo->prepare('INSERT INTO car_images (car_id, image_path, sort_order) VALUES (?, ?, ?)');
    foreach ($images as $order => $image) {
        $statement->execute([$carId, $image, $order]);
    }
}

function get_car(int $id, bool $availableOnly = false): ?array
{
    $sql = 'SELECT c.*, (SELECT ci.image_path FROM car_images ci WHERE ci.car_id = c.id ORDER BY ci.sort_order, ci.id LIMIT 1) AS image_path
            FROM cars c WHERE c.id = ?';
    if ($availableOnly) {
        $sql .= " AND c.status = 'available'";
    }
    $statement = db()->prepare($sql);
    $statement->execute([$id]);
    $car = $statement->fetch();
    return $car ?: null;
}

function car_gallery(int $id): array
{
    $statement = db()->prepare('SELECT image_path FROM car_images WHERE car_id = ? ORDER BY sort_order, id');
    $statement->execute([$id]);
    return $statement->fetchAll();
}

function car_card(array $car, bool $featured = false): void
{
    ?>
    <article class="car-card<?= $featured ? ' car-card-featured' : '' ?>">
        <a class="car-card-image" href="<?= e(url('car-details.php?id=' . (int) $car['id'])) ?>">
            <img src="<?= e(url(car_image($car))) ?>" alt="<?= e($car['year'] . ' ' . $car['brand'] . ' ' . $car['model']) ?>" loading="lazy">
            <?php if ($featured): ?><span class="image-tag">EDITOR'S PICK</span><?php endif; ?>
            <?php if ($car['status'] === 'sold'): ?><span class="sold-tag">SOLD</span><?php endif; ?>
        </a>
        <div class="car-card-content">
            <div class="car-card-heading">
                <div><p class="eyebrow"><?= e($car['year']) ?> <span>·</span> <?= e($car['body_type'] ?: 'PRE-OWNED') ?></p>
                    <h3><?= e($car['brand'] . ' ' . $car['model']) ?></h3></div>
                <p class="car-price"><?= e(money($car['price'])) ?></p>
            </div>
            <div class="car-meta"><span><?= number_format((int) $car['mileage']) ?> mi</span><span><?= e($car['transmission'] ?: 'Automatic') ?></span><span><?= e($car['fuel_type'] ?: 'Gasoline') ?></span></div>
            <a class="text-link" href="<?= e(url('car-details.php?id=' . (int) $car['id'])) ?>">Explore this car <span aria-hidden="true">↗</span></a>
        </div>
    </article>
    <?php
}
