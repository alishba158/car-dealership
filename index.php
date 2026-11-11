<?php
require_once __DIR__ . '/includes/functions.php';
$featured = db()->query("SELECT c.*, (SELECT ci.image_path FROM car_images ci WHERE ci.car_id = c.id ORDER BY ci.sort_order, ci.id LIMIT 1) AS image_path FROM cars c WHERE c.status = 'available' AND c.featured = 1 ORDER BY c.created_at DESC LIMIT 3")->fetchAll();
$latest = db()->query("SELECT c.*, (SELECT ci.image_path FROM car_images ci WHERE ci.car_id = c.id ORDER BY ci.sort_order, ci.id LIMIT 1) AS image_path FROM cars c WHERE c.status = 'available' ORDER BY c.created_at DESC LIMIT 3")->fetchAll();
$brands = db()->query("SELECT DISTINCT brand FROM cars WHERE status = 'available' ORDER BY brand")->fetchAll(PDO::FETCH_COLUMN);
$pageTitle = 'Thoughtfully chosen. Ready for the road.';
$activePage = 'home';
require __DIR__ . '/includes/header.php';
?>
<section class="hero">
    <div class="hero-photo"></div><div class="hero-shade"></div>
    <div class="container hero-content">
        <p class="eyebrow light-eyebrow"><span class="eyebrow-line"></span> A MORE CONSIDERED CAR EXPERIENCE</p>
        <h1>The right car<br>changes <em>everything.</em></h1>
        <p class="hero-copy">Good cars, chosen with care. Meet the one that makes every mile feel like yours.</p>
        <a class="button button-light" href="<?= e(url('buy-cars.php')) ?>">Explore the collection <span>↗</span></a>
        <div class="hero-caption"><span>01 — 03</span><span>EVERY CAR HAS A STORY. LET'S FIND YOURS.</span></div>
    </div>
    <div class="hero-search">
        <div><span class="eyebrow">YOUR NEXT CHAPTER</span><strong>Find a car that fits.</strong></div>
        <form action="<?= e(url('buy-cars.php')) ?>" method="get" class="search-fields">
            <label><span>MAKE</span><select name="brand"><option value="">All makes</option><?php foreach ($brands as $brand): ?><option value="<?= e($brand) ?>"><?= e($brand) ?></option><?php endforeach; ?></select></label>
            <label><span>MODEL</span><input name="model" type="search" placeholder="Try a model name"></label>
            <button type="submit" class="button button-dark">Browse cars <span>↗</span></button>
        </form>
    </div>
</section>
<section class="section container">
    <div class="section-heading"><div><p class="eyebrow">THE NORTHLINE EDIT</p><h2>Chosen for the way<br>you want to <em>feel.</em></h2></div><a class="text-link" href="<?= e(url('buy-cars.php')) ?>">View the full collection <span>↗</span></a></div>
    <?php if ($featured): ?><div class="car-grid"><?php foreach ($featured as $car) { car_card($car, true); } ?></div><?php else: ?><div class="empty-state"><p>Our next collection is taking shape.</p><a href="<?= e(url('buy-cars.php')) ?>" class="text-link">Browse available cars ↗</a></div><?php endif; ?>
</section>
<section class="editorial-band"><div class="container editorial-inner"><div><p class="eyebrow light-eyebrow">A BETTER WAY TO MOVE ON</p><h2>Ready for a<br>new <em>road?</em></h2></div><div class="editorial-copy"><p>We make selling feel as considered as buying. Tell us a little about your car and our team will take it from there — with a straightforward offer and no pressure.</p><a href="<?= e(url('sell-car.php')) ?>" class="button button-outline-light">Sell your car with us <span>↗</span></a></div></div></section>
<section class="section container">
    <div class="section-heading"><div><p class="eyebrow">JUST ARRIVED</p><h2>New to the <em>floor.</em></h2></div><a class="text-link" href="<?= e(url('buy-cars.php')) ?>">See every car <span>↗</span></a></div>
    <?php if ($latest): ?><div class="car-grid"><?php foreach ($latest as $car) { car_card($car); } ?></div><?php else: ?><div class="empty-state"><p>Check back soon for fresh arrivals.</p></div><?php endif; ?>
</section>
<section class="about-strip"><div class="container about-strip-inner"><div class="about-photo"></div><div class="about-copy"><p class="eyebrow">MORE THAN A MOTOR</p><h2>Buying a car should feel <em>good.</em></h2><p>We believe the right car isn't just about the numbers. It's about the way you feel when you find it. Every car we choose is carefully inspected, honestly described, and backed by real people who care.</p><a class="text-link" href="<?= e(url('about.php')) ?>">Get to know us <span>↗</span></a></div></div></section>
<section class="section container testimonials"><p class="eyebrow">KIND WORDS, REAL MILEAGE</p><h2>Don't just take<br><em>our word for it.</em></h2><div class="quote-grid"><blockquote><span class="quote-mark">“</span><p>They made the whole process feel refreshingly human. Found my car, loved the drive home, and never once felt rushed.</p><footer>— Jamie R. <span>2022 Volvo XC60</span></footer></blockquote><blockquote><span class="quote-mark">“</span><p>Sold my old car in a few days. A genuinely fair offer, clear communication, and not a pile of paperwork in sight.</p><footer>— Alex M. <span>Seller & Northline friend</span></footer></blockquote><blockquote><span class="quote-mark">“</span><p>Exactly the kind of place you want to find. Thoughtful people, beautiful cars, and zero pressure to make a decision.</p><footer>— Taylor S. <span>2021 Audi A5</span></footer></blockquote></div></section>
<?php require __DIR__ . '/includes/footer.php'; ?>
