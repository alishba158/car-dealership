<?php
require_once __DIR__ . '/includes/functions.php';
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $name = trim((string) ($_POST['name'] ?? ''));
    $email = trim((string) ($_POST['email'] ?? ''));
    $phone = trim((string) ($_POST['phone'] ?? ''));
    $subject = trim((string) ($_POST['subject'] ?? ''));
    $message = trim((string) ($_POST['message'] ?? ''));
    if ($name === '' || strlen($name) > 120) $errors[] = 'Please enter your name.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Please enter a valid email address.';
    if ($phone !== '' && !preg_match('/^[0-9+().\\s-]{7,25}$/', $phone)) $errors[] = 'Please enter a valid phone number.';
    if ($subject === '' || strlen($subject) > 180) $errors[] = 'Please add a subject.';
    if ($message === '' || strlen($message) > 5000) $errors[] = 'Please enter a message (up to 5,000 characters).';
    if (!$errors) {
        $statement = db()->prepare('INSERT INTO messages (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)');
        $statement->execute([$name, $email, $phone ?: null, $subject, $message]);
        flash('success', 'Your message is on its way. We’ll be in touch soon.');
        redirect('contact.php');
    }
}
$pageTitle = 'Get in touch';
$activePage = 'contact';
require __DIR__ . '/includes/header.php';
?>
<section class="page-hero contact-hero"><div class="container"><p class="eyebrow light-eyebrow"><span class="eyebrow-line"></span> WE'RE GOOD LISTENERS</p><h1>Come as you are.<br>Ask us <em>anything.</em></h1><p>Cars, trade-ins, a test drive, or just a question. There's a real person here, and we'd love to hear from you.</p></div></section>
<section class="container contact-layout"><div class="contact-details"><p class="eyebrow">THE NORTHLINE HELLO</p><h2>We'll make<br>time for <em>you.</em></h2><p>Drop us a note or give us a call. We'll get back to you as soon as we can — usually the same business day.</p><div class="contact-line"><span>CALL US</span><a href="tel:+15550142880">(555) 014-2880</a></div><div class="contact-line"><span>SEND A NOTE</span><a href="mailto:hello@northlinemotor.com">hello@northlinemotor.com</a></div><div class="contact-line"><span>FIND US</span><p>48 Westfield Avenue<br>Portland, OR 97205</p></div><div class="contact-line"><span>FOLLOW ALONG</span><div class="social-links"><a href="https://www.instagram.com/" target="_blank" rel="noopener noreferrer">Instagram ↗</a><a href="https://www.facebook.com/" target="_blank" rel="noopener noreferrer">Facebook ↗</a></div></div></div>
<form class="form-card contact-form" method="post"><?php if ($errors): ?><div class="notice notice-error"><ul><?php foreach ($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><h3>Leave us a note</h3><div class="form-grid"><label>Your name<input name="name" required maxlength="120" value="<?= e($_POST['name'] ?? '') ?>"></label><label>Email address<input name="email" required type="email" maxlength="190" value="<?= e($_POST['email'] ?? '') ?>"></label><label class="full-field">Phone (optional)<input name="phone" type="tel" maxlength="25" value="<?= e($_POST['phone'] ?? '') ?>"></label><label class="full-field">What's on your mind?<input name="subject" required maxlength="180" value="<?= e($_POST['subject'] ?? '') ?>"></label><label class="full-field">Your message<textarea name="message" rows="5" required maxlength="5000"><?= e($_POST['message'] ?? '') ?></textarea></label></div><button class="button button-dark button-wide">Send your note <span>↗</span></button><p class="form-privacy">We only use your information to respond to you.</p></form></section>
<section class="map-band"><div class="container map-content"><p class="eyebrow">SOMEWHERE IN PORTLAND</p><h2>Take the scenic<br><em>route over.</em></h2><p>48 Westfield Avenue · Portland, OR 97205</p><a href="https://maps.google.com/?q=48+Westfield+Avenue+Portland+OR+97205" target="_blank" rel="noopener noreferrer" class="text-link">Get directions <span>↗</span></a></div></section>
<?php require __DIR__ . '/includes/footer.php'; ?>
