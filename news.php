<?php
require_once __DIR__.'/config/config.php';
$page_title = 'News | '.APP_NAME;
try {
  $articles = db()->query("SELECT * FROM news_articles WHERE status='published' ORDER BY published_at DESC, id DESC")->fetchAll();
} catch (Throwable $e) { $articles = []; }
include __DIR__.'/includes/header.php';
?>
<section class="page-hero">
  <div class="container" data-aos="fade-up">
    <span class="eyebrow">News & Updates</span>
    <h1>Stories from the foundation.</h1>
    <p>Official updates, reports and announcements from Om Shanthi Trust & Foundation.</p>
  </div>
</section>
<section class="section">
  <div class="container grid-3">
    <?php if ($articles): foreach ($articles as $i => $a): ?>
      <article class="card" data-aos="fade-up" data-aos-delay="<?= (int)($i%3*80) ?>">
        <span class="eyebrow"><?= e($a['category'] ?? 'Updates') ?></span>
        <h3><?= e($a['title']) ?></h3>
        <p><?= e($a['excerpt'] ?? mb_substr(strip_tags($a['content'] ?? ''), 0, 140).'…') ?></p>
        <div class="event-meta">
          <span><i class="bi bi-person"></i> <?= e($a['author'] ?? 'Om Shanthi Trust') ?></span>
          <?php if (!empty($a['published_at'])): ?><span><i class="bi bi-calendar3"></i> <?= e(date('M j, Y', strtotime($a['published_at']))) ?></span><?php endif; ?>
        </div>
      </article>
    <?php endforeach; else: ?>
      <div class="empty-state" style="grid-column:1/-1">
        <i class="bi bi-newspaper"></i>
        <h3>No articles published yet</h3>
        <p>News and updates will appear here once published through the CMS.</p>
      </div>
    <?php endif; ?>
  </div>
</section>
<?php include __DIR__.'/includes/footer.php'; ?>
