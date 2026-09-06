<?php
require_once __DIR__.'/config/config.php';
$message = ''; $error = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name   = trim($_POST['donor_name'] ?? '');
  $email  = trim($_POST['email'] ?? '');
  $amount = (float)($_POST['amount'] ?? 0);
  if ($name && filter_var($email, FILTER_VALIDATE_EMAIL) && $amount > 0) {
    try {
      $s = db()->prepare("INSERT INTO donations(donor_name,email,amount,frequency,status) VALUES(?,?,?,?,?)");
      $s->execute([$name, $email, $amount, ($_POST['frequency']??'') === 'monthly' ? 'monthly' : 'one_time', 'pending']);
      $message = 'Donation request recorded. A real payment provider must be configured before funds can be processed.';
    } catch (Throwable $e) {
      $message = 'Unable to save donation request. Please try again later.';
      $error = true;
    }
  } else {
    $message = 'Please enter valid donor details and an amount greater than zero.';
    $error = true;
  }
}
$page_title = 'Donate | '.APP_NAME;
include __DIR__.'/includes/header.php';
?>
<section class="page-hero">
  <div class="container" data-aos="fade-up">
    <span class="eyebrow">Donate</span>
    <h1>Turn generosity into action.</h1>
    <p>This page implements a secure donation request boundary. No payment is processed until a real provider is connected.</p>
  </div>
</section>
<section class="section">
  <div class="container grid-2">
    <form class="card form" method="post" data-aos="fade-right" id="donateForm">
      <?php if ($message): ?><script>showToast(<?= $error ? '"Error"' : '"Success"' ?>, <?= json_encode($message) ?>, <?= json_encode($error ? 'error' : 'success') ?>, 6000);</script><?php endif; ?>
      <div class="field"><label for="donor_name">Donor Name *</label><input id="donor_name" name="donor_name" required autocomplete="name"></div>
      <div class="field"><label for="email">Email *</label><input id="email" name="email" type="email" required autocomplete="email"></div>
      <div class="field">
        <label>Select amount (₹)</label>
        <div class="amount-grid">
          <button type="button" class="amount-btn" data-amount="100">₹100</button>
          <button type="button" class="amount-btn" data-amount="250">₹250</button>
          <button type="button" class="amount-btn active" data-amount="500">₹500</button>
          <button type="button" class="amount-btn" data-amount="1000">₹1,000</button>
        </div>
        <input id="amount" name="amount" type="number" min="1" step="1" value="500" required aria-label="Custom amount">
      </div>
      <div class="field">
        <label for="frequency">Frequency</label>
        <select id="frequency" name="frequency">
          <option value="one_time">One-time</option>
          <option value="monthly">Monthly</option>
        </select>
      </div>
      <button type="submit" class="btn btn-primary w-100"><i class="bi bi-shield-lock"></i> Continue to Secure Payment</button>
      <p class="mt-2" style="font-size:0.85rem;color:var(--c-muted)"><i class="bi bi-info-circle"></i> Payment processing is not active until a provider is configured by the foundation.</p>
    </form>
    <div data-aos="fade-left">
      <div class="section-head">
        <span class="eyebrow">Your Impact</span>
        <h2>Your contribution deserves clear context.</h2>
        <p>Impact descriptions should be configured only when the foundation has verified evidence for the stated outcome. We do not invent numbers.</p>
      </div>
      <div class="card">
        <div class="icon"><i class="bi bi-heart-pulse"></i></div>
        <h3>Transparent by design</h3>
        <p>Donation requests are stored securely. Real payment flows require provider credentials and should never be simulated.</p>
      </div>
    </div>
  </div>
</section>
<?php include __DIR__.'/includes/footer.php'; ?>
