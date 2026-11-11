<?php
require_once __DIR__ . '/includes/functions.php';
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$car = $id ? get_car((int) $id) : null;
if (!$car) { http_response_code(404); $pageTitle = 'Car not found'; require __DIR__ . '/includes/header.php'; echo '<section class="container section empty-state"><h1>That car has moved on.</h1><a class="text-link" href="' . e(url('buy-cars.php')) . '">Back to the collection ↗</a></section>'; require __DIR__ . '/includes/footer.php'; exit; }
$gallery = car_gallery((int) $car['id']);
$detailImages = $gallery ?: [['image_path' => car_image($car)]];
$phone = preg_replace('/\D+/', '', (string) $car['contact_phone']);
$pageTitle = $car['year'] . ' ' . $car['brand'] . ' ' . $car['model'];
require __DIR__ . '/includes/header.php';
?>
<div class="container breadcrumb"><a href="<?= e(url('buy-cars.php')) ?>">The collection</a><span>/</span><span><?= e($car['brand'] . ' ' . $car['model']) ?></span></div>
<section class="container detail-layout">
    <div class="detail-gallery">
        <img class="detail-main-image" src="<?= e(url($detailImages[0]['image_path'])) ?>" alt="<?= e($pageTitle) ?>" id="main-car-image">
        <?php if (count($detailImages) > 1): ?><div class="detail-thumbnails"><?php foreach ($detailImages as $index => $image): ?><button type="button" class="thumbnail<?= $index === 0 ? ' selected' : '' ?>" data-image="<?= e(url($image['image_path'])) ?>" aria-label="View image <?= $index + 1 ?>"><img src="<?= e(url($image['image_path'])) ?>" alt=""></button><?php endforeach; ?></div><?php endif; ?>
    </div>
    <aside class="detail-info"><p class="eyebrow"><?= e($car['year']) ?> <span>·</span> <?= e($car['body_type'] ?: 'PRE-OWNED') ?></p><h1><?= e($car['brand']) ?><br><em><?= e($car['model']) ?></em></h1><p class="detail-price"><?= e(money($car['price'])) ?></p><p class="detail-description"><?= nl2br(e($car['description'])) ?></p>
        <div class="spec-grid"><div><span>MILEAGE</span><strong><?= number_format((int) $car['mileage']) ?> mi</strong></div><div><span>TRANSMISSION</span><strong><?= e($car['transmission'] ?: 'Automatic') ?></strong></div><div><span>FUEL</span><strong><?= e($car['fuel_type'] ?: 'Gasoline') ?></strong></div><div><span>EXTERIOR</span><strong><?= e($car['color'] ?: '—') ?></strong></div><div><span>CONDITION</span><strong><?= e(ucfirst($car['status'])) ?></strong></div><div><span>YEAR</span><strong><?= e($car['year']) ?></strong></div></div>
        <?php if ($car['status'] === 'available'): ?><a class="button button-dark button-wide" href="tel:<?= e($phone) ?>">Call our team <span>↗</span></a><a class="button button-whatsapp button-wide" target="_blank" rel="noopener noreferrer" href="https://wa.me/<?= e($phone) ?>?text=I'm%20interested%20in%20the%20<?= rawurlencode($pageTitle) ?>">Ask about this car on WhatsApp <span>↗</span></a><?php else: ?><p class="sold-notice">This car has found its next home.</p><?php endif; ?>
        <p class="detail-reassurance">No pressure. Just a real conversation about a car you love.</p>
    </aside>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
