<?php
require_once __DIR__.'/config/config.php';
require_once __DIR__.'/includes/pixabay.php';
$page_title = 'Gallery | '.APP_NAME;
try {
  $items = db()->query("SELECT * FROM gallery ORDER BY id DESC")->fetchAll();
  $categories = array_unique(array_filter(array_column($items, 'category')));
} catch (Throwable $e) { $items = []; $categories = []; }

// Illustrative local media when CMS gallery is empty (clearly labeled)
$illustrative = [
  ['title' => 'Community gathering', 'category' => 'Community', 'image' => asset('images/gallery/gallery-1.jpg')],
  ['title' => 'Hands of care', 'category' => 'Support', 'image' => asset('images/gallery/gallery-2.jpg')],
  ['title' => 'Food support', 'category' => 'Food', 'image' => asset('images/gallery/gallery-3.jpg')],
  ['title' => 'Learning together', 'category' => 'Education', 'image' => asset('images/gallery/gallery-4.jpg')],
  ['title' => 'Giving with dignity', 'category' => 'Support', 'image' => asset('images/gallery/gallery-5.jpg')],
  ['title' => 'Community outreach', 'category' => 'Community', 'image' => asset('images/gallery/gallery-6.jpg')],
  ['title' => 'Hope in action', 'category' => 'Community', 'image' => asset('images/gallery/gallery-7.jpg')],
  ['title' => 'Shared moments', 'category' => 'Events', 'image' => asset('images/gallery/gallery-8.jpg')],
];
$pixabay_gallery = pixabay_images();
foreach ($pixabay_gallery as $i => $image) {
  if ($i >= count($illustrative)) break;
  $illustrative[$i]['image'] = $image['url'];
  $illustrative[$i]['title'] .= ' · Illustrative';
}

include __DIR__.'/includes/header.php';
?>
<section class="page-hero">
  <div class="container" data-aos="fade-up">
    <span class="eyebrow">Gallery</span>
    <h1>Moments of community and care.</h1>
    <p>Verified foundation activity photos appear when published in the CMS. Illustrative media is clearly labeled and is not presented as verified Om Shanthi Trust activity.</p>
  </div>
</section>
<section class="section">
  <div class="container">
    <?php if ($items): ?>
      <?php if ($categories): ?>
      <div class="d-flex flex-wrap gap-2 mb-4" data-aos="fade-up">
        <button type="button" class="btn btn-sm btn-primary amount-btn active" data-filter="all">All</button>
        <?php foreach ($categories as $cat): ?>
          <button type="button" class="btn btn-sm btn-outline-primary amount-btn" data-filter="<?= e($cat) ?>"><?= e($cat) ?></button>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
      <div class="gallery-grid">
        <?php foreach ($items as $i => $g): ?>
          <figure class="gallery-item" data-category="<?= e($g['category']) ?>" data-aos="fade-up" data-aos-delay="<?= (int)($i%4*60) ?>">
            <img loading="lazy" src="<?= e($g['image']) ?>" alt="<?= e($g['title']) ?>">
            <?php if (!empty($g['verified'])): ?><span class="badge-verified">Verified</span><?php endif; ?>
            <div class="overlay">
              <b><?= e($g['title']) ?></b>
              <small><?= e($g['category']) ?></small>
            </div>
          </figure>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <p class="mb-3" data-aos="fade-up" style="color:var(--c-muted);font-size:0.95rem">
        <i class="bi bi-info-circle"></i> Showing illustrative media only. Upload verified activity photos via the admin CMS to replace these.
      </p>
      <div class="gallery-grid">
        <?php foreach ($illustrative as $i => $g): ?>
          <figure class="gallery-item" data-category="<?= e($g['category']) ?>" data-aos="fade-up" data-aos-delay="<?= (int)($i%4*60) ?>">
            <img loading="lazy" src="<?= e($g['image']) ?>" alt="<?= e($g['title']) ?> — illustrative">
            <span class="media-label illustrative">Illustrative</span>
            <div class="overlay">
              <b><?= e($g['title']) ?></b>
              <small><?= e($g['category']) ?> · Illustrative media</small>
            </div>
          </figure>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>
<?php include __DIR__.'/includes/footer.php'; ?>
