<?php
require_once __DIR__.'/config/config.php';
$message = ''; $error = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name  = trim($_POST['full_name'] ?? '');
  $email = trim($_POST['email'] ?? '');
  if ($name && filter_var($email, FILTER_VALIDATE_EMAIL)) {
    try {
      $s = db()->prepare("INSERT INTO volunteers(full_name,email,phone,city,interests,availability,skills,message) VALUES(?,?,?,?,?,?,?,?)");
      $s->execute([
        $name, $email,
        trim($_POST['phone']??''), trim($_POST['city']??''),
        trim($_POST['interests']??''), trim($_POST['availability']??''),
        trim($_POST['skills']??''), trim($_POST['message']??'')
      ]);
      $message = 'Your volunteer interest has been submitted. The team will follow up when possible.';
    } catch (Throwable $e) {
      $message = 'Unable to save your submission. Please try again later.';
      $error = true;
    }
  } else {
    $message = 'Please enter a valid name and email.';
    $error = true;
  }
}
$page_title = 'Volunteer | '.APP_NAME;
include __DIR__.'/includes/header.php';
?>
<section class="page-hero">
  <div class="container" data-aos="fade-up">
    <span class="eyebrow">Get Involved</span>
    <h1>Give your time, skills and care.</h1>
    <p>Submit your interest and the foundation team can follow up when opportunities match.</p>
  </div>
</section>
<section class="section">
  <div class="container grid-2">
    <form class="card form" method="post" data-aos="fade-right">
      <?php if ($message): ?><div class="alert <?= $error ? 'alert-error' : '' ?>"><?= e($message) ?></div><?php endif; ?>
      <div class="form-row">
        <div class="field"><label for="full_name">Full Name *</label><input id="full_name" name="full_name" required autocomplete="name"></div>
        <div class="field"><label for="email">Email *</label><input id="email" name="email" type="email" required autocomplete="email"></div>
      </div>
      <div class="form-row">
        <div class="field"><label for="phone">Phone</label><input id="phone" name="phone" type="tel"></div>
        <div class="field"><label for="city">City</label><input id="city" name="city"></div>
      </div>
      <div class="field"><label for="interests">Areas of Interest</label><input id="interests" name="interests" placeholder="Food support, education, events…"></div>
      <div class="field"><label for="availability">Availability</label><input id="availability" name="availability" placeholder="Weekends, evenings…"></div>
      <div class="field"><label for="skills">Skills</label><textarea id="skills" name="skills" rows="3"></textarea></div>
      <div class="field"><label for="message">Message</label><textarea id="message" name="message" rows="3"></textarea></div>
      <button type="submit" class="btn btn-primary"><i class="bi bi-person-plus"></i> Submit Volunteer Interest</button>
    </form>
    <div data-aos="fade-left">
      <div class="section-head">
        <span class="eyebrow">Volunteer</span>
        <h2>Every skill can serve a purpose.</h2>
        <p>From organizing distributions to mentoring and logistics — we welcome people who want to help with care and reliability.</p>
      </div>
      <div class="card">
        <div class="icon"><i class="bi bi-handshake"></i></div>
        <h3>What happens next?</h3>
        <p>Your submission is stored securely. The team reviews interests and reaches out when there is a suitable opportunity.</p>
      </div>
    </div>
  </div>
</section>
<?php include __DIR__.'/includes/footer.php'; ?>
