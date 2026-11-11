<?php
require_once __DIR__ . '/includes/functions.php';
$where = ["c.status = 'available'"];
$params = [];
$brand = trim((string) ($_GET['brand'] ?? ''));
$model = trim((string) ($_GET['model'] ?? ''));
$maxPrice = trim((string) ($_GET['max_price'] ?? ''));
$sort = (string) ($_GET['sort'] ?? 'newest');
if ($brand !== '') { $where[] = 'c.brand = ?'; $params[] = $brand; }
if ($model !== '') { $where[] = 'c.model LIKE ?'; $params[] = '%' . $model . '%'; }
if ($maxPrice !== '' && ctype_digit($maxPrice)) { $where[] = 'c.price <= ?'; $params[] = (int) $maxPrice; }
$order = ['price-low' => 'c.price ASC', 'price-high' => 'c.price DESC', 'year' => 'c.year DESC', 'newest' => 'c.created_at DESC'][$sort] ?? 'c.created_at DESC';
$statement = db()->prepare('SELECT c.*, (SELECT ci.image_path FROM car_images ci WHERE ci.car_id = c.id ORDER BY ci.sort_order, ci.id LIMIT 1) AS image_path FROM cars c WHERE ' . implode(' AND ', $where) . ' ORDER BY ' . $order);
$statement->execute($params);
$cars = $statement->fetchAll();
$brands = db()->query("SELECT DISTINCT brand FROM cars WHERE status = 'available' ORDER BY brand")->fetchAll(PDO::FETCH_COLUMN);
$pageTitle = 'The Collection';
$activePage = 'cars';
require __DIR__ . '/includes/header.php';
?>
<section class="page-hero collection-hero"><div class="container"><p class="eyebrow light-eyebrow"><span class="eyebrow-line"></span> THE NORTHLINE COLLECTION</p><h1>Find your next<br><em>favorite thing.</em></h1><p>Every car here has been chosen with intention. Browse at your pace; the right one will find you.</p></div></section>
<section class="section container collection-section">
    <div class="collection-top"><div><p class="eyebrow">A LITTLE SOMETHING FOR EVERY JOURNEY</p><h2><?= count($cars) ?> thoughtfully chosen <?= count($cars) === 1 ? 'car' : 'cars' ?></h2></div></div>
    <form class="filter-bar" action="<?= e(url('buy-cars.php')) ?>" method="get">
        <label><span>MAKE</span><select name="brand"><option value="">All makes</option><?php foreach ($brands as $option): ?><option value="<?= e($option) ?>" <?= $brand === $option ? 'selected' : '' ?>><?= e($option) ?></option><?php endforeach; ?></select></label>
        <label><span>MODEL</span><input type="search" name="model" value="<?= e($model) ?>" placeholder="Any model"></label>
        <label><span>MAX. PRICE</span><select name="max_price"><option value="">Any budget</option><?php foreach ([25000, 40000, 60000, 85000, 120000] as $limit): ?><option value="<?= $limit ?>" <?= $maxPrice === (string) $limit ? 'selected' : '' ?>>Up to <?= e(money($limit)) ?></option><?php endforeach; ?></select></label>
        <label><span>SORT BY</span><select name="sort"><option value="newest">Recently added</option><option value="price-low" <?= $sort === 'price-low' ? 'selected' : '' ?>>Price: low to high</option><option value="price-high" <?= $sort === 'price-high' ? 'selected' : '' ?>>Price: high to low</option><option value="year" <?= $sort === 'year' ? 'selected' : '' ?>>Newest model year</option></select></label>
        <button class="button button-dark filter-submit" type="submit">Apply filters <span>↗</span></button>
    </form>
    <?php if ($cars): ?><div class="car-grid collection-grid"><?php foreach ($cars as $car) { car_card($car); } ?></div><?php else: ?><div class="empty-state"><p>No cars match those filters just yet.</p><a class="text-link" href="<?= e(url('buy-cars.php')) ?>">Clear filters ↗</a></div><?php endif; ?>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
