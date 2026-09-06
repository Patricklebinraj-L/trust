<?php
require_once __DIR__.'/config/config.php';
$message = ''; $error = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name  = trim($_POST['name'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $msg   = trim($_POST['message'] ?? '');
  if ($name && filter_var($email, FILTER_VALIDATE_EMAIL) && $msg) {
    try {
      $s = db()->prepare("INSERT INTO contact_messages(name,email,phone,subject,message) VALUES(?,?,?,?,?)");
      $s->execute([$name, $email, trim($_POST['phone']??''), trim($_POST['subject']??''), $msg]);
      $message = 'Your message has been received. We will respond as soon as we can.';
    } catch (Throwable $e) {
      $message = 'Unable to save your message right now. Please try again later.';
      $error = true;
    }
  } else {
    $message = 'Please complete the required fields with a valid email.';
    $error = true;
  }
}
$page_title = 'Contact | '.APP_NAME;
include __DIR__.'/includes/header.php';
?>
<section class="page-hero">
  <div class="container" data-aos="fade-up">
    <span class="eyebrow">Contact</span>
    <h1>Let's connect.</h1>
    <p>Reach out for general inquiries, partnerships or media. Official contact details should be verified before public use.</p>
  </div>
</section>
<section class="section">
  <div class="container grid-2">
    <form class="card form" method="post" data-aos="fade-right" novalidate>
      <?php if ($message): ?><script>showToast(<?= $error ? '"Error"' : '"Success"' ?>, <?= json_encode($message) ?>, <?= json_encode($error ? 'error' : 'success') ?>, 6000);</script><?php endif; ?>
      <div class="form-row">
        <div class="field"><label for="name">Name *</label><input id="name" name="name" required autocomplete="name"></div>
        <div class="field"><label for="email">Email *</label><input id="email" name="email" type="email" required autocomplete="email"></div>
      </div>
      <div class="form-row">
        <div class="field"><label for="phone">Phone</label><input id="phone" name="phone" type="tel" autocomplete="tel"></div>
        <div class="field"><label for="subject">Subject</label><input id="subject" name="subject"></div>
      </div>
      <div class="field"><label for="message">Message *</label><textarea id="message" name="message" required rows="5"></textarea></div>
      <button type="submit" class="btn btn-primary"><i class="bi bi-send"></i> Send Message</button>
    </form>
    <div data-aos="fade-left">
      <div class="card mb-3">
        <div class="icon"><i class="bi bi-geo-alt"></i></div>
        <h3>Registered Office</h3>
        <p><?= e(TRUST_ADDRESS) ?></p>
      </div>
      <div class="card mb-3">
        <div class="icon"><i class="bi bi-envelope"></i></div>
        <h3>Email</h3>
        <p>Official email addresses should be confirmed before listing.</p>
      </div>
      <div class="card">
        <div class="icon"><i class="bi bi-clock"></i></div>
        <h3>Hours</h3>
        <p>Response times depend on foundation capacity. We aim to reply within a few business days.</p>
      </div>
      <p class="mt-3"><a class="link" href="<?= url('faq.php') ?>">Browse FAQs <i class="bi bi-arrow-right"></i></a></p>
    </div>
  </div>
</section>
<?php include __DIR__.'/includes/footer.php'; ?>
