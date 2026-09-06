<?php
require_once __DIR__.'/config/config.php';
$slug = trim($_GET['slug'] ?? '');
$program = null;
if ($slug) {
  try {
    $s = db()->prepare("SELECT * FROM programs WHERE slug=? AND status='published' LIMIT 1");
    $s->execute([$slug]);
    $program = $s->fetch();
  } catch (Throwable $e) {}
}
if (!$program) {
  $page_title = 'Program not found | '.APP_NAME;
  include __DIR__.'/includes/header.php';
  echo '<section class="page-hero"><div class="container"><h1>Program not found</h1><p>The program you are looking for is not available.</p><a class="btn btn-primary mt-3" href="'.url('programs.php').'">View all programs</a></div></section>';
  include __DIR__.'/includes/footer.php';
  exit;
}
$page_title = e($program['title']).' | '.APP_NAME;
include __DIR__.'/includes/header.php';
?>
<section class="page-hero">
  <div class="container" data-aos="fade-up">
    <span class="eyebrow">Program</span>
    <h1><?= e($program['title']) ?></h1>
    <p><?= e($program['short_description']) ?></p>
  </div>
</section>
<section class="section">
  <div class="container grid-2">
    <div data-aos="fade-right">
      <div class="icon" style="width:64px;height:64px;font-size:1.6rem;margin-bottom:1.25rem"><i class="bi bi-<?= e($program['icon'] ?: 'heart') ?>"></i></div>
      <div class="content"><?= nl2br(e($program['description'])) ?></div>
      <div class="actions mt-4 d-flex gap-2 flex-wrap">
        <a class="btn btn-primary" href="<?= url('donate.php') ?>"><i class="bi bi-heart-fill"></i> Support this work</a>
        <a class="btn btn-outline-primary" href="<?= url('volunteer.php') ?>">Volunteer</a>
      </div>
    </div>
    <div class="card" data-aos="fade-left">
      <h3>How you can help</h3>
      <p>Your support — whether through donations, volunteering or spreading awareness — helps us deliver this program with dignity and care.</p>
      <a class="link" href="<?= url('contact.php') ?>">Get in touch <i class="bi bi-arrow-right"></i></a>
    </div>
  </div>
</section>
<?php include __DIR__.'/includes/footer.php'; ?>
