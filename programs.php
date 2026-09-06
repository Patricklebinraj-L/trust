<?php
require_once __DIR__.'/config/config.php';
$page_title = 'Programs | '.APP_NAME;
try {
  $programs = db()->query("SELECT * FROM programs WHERE status='published' ORDER BY id")->fetchAll();
} catch (Throwable $e) { $programs = []; }

$program_images = [
  'food' => asset('images/programs/food-support.jpg'),
  'education' => asset('images/programs/education.jpg'),
  'clothing' => asset('images/programs/clothing.jpg'),
  'community' => asset('images/programs/community.jpg'),
  'health' => asset('images/programs/health.jpg'),
  'default' => asset('images/programs/community.jpg'),
];

include __DIR__.'/includes/header.php';
?>
<section class="page-hero">
  <div class="container" data-aos="fade-up">
    <span class="eyebrow">Programs</span>
    <h1>How we serve our community.</h1>
    <p>Each program addresses a real need with dignity, care and practical support.</p>
  </div>
</section>
<section class="section">
  <div class="container">
    <div class="grid-3">
      <?php if ($programs): foreach ($programs as $i => $p):
        $slug = strtolower($p['slug'] ?? '');
        $img = $program_images['default'];
        if (str_contains($slug, 'food') || str_contains($slug, 'meal')) $img = $program_images['food'];
        elseif (str_contains($slug, 'educat') || str_contains($slug, 'school')) $img = $program_images['education'];
        elseif (str_contains($slug, 'cloth') || str_contains($slug, 'garment')) $img = $program_images['clothing'];
        elseif (str_contains($slug, 'health') || str_contains($slug, 'care')) $img = $program_images['health'];
        elseif (str_contains($slug, 'communit')) $img = $program_images['community'];
        if (!empty($p['image'])) $img = $p['image'];
      ?>
        <article class="card program-card" data-aos="fade-up" data-aos-delay="<?= (int)($i%3*80) ?>">
          <div class="card-media">
            <img src="<?= e($img) ?>" alt="" loading="lazy">
            <?php if (empty($p['image'])): ?><span class="media-label illustrative">Illustrative</span><?php endif; ?>
          </div>
          <div class="card-body">
            <div class="icon"><i class="bi bi-<?= e($p['icon'] ?: 'heart') ?>"></i></div>
            <h3><?= e($p['title']) ?></h3>
            <p><?= e($p['short_description']) ?></p>
            <a class="link" href="<?= url('program.php?slug='.urlencode($p['slug'])) ?>">Learn more <i class="bi bi-arrow-right"></i></a>
          </div>
        </article>
      <?php endforeach; else:
        $placeholders = [
          ['title' => 'Food Support', 'desc' => 'Nutritious meals and grocery assistance for families facing food insecurity.', 'icon' => 'basket2', 'img' => $program_images['food']],
          ['title' => 'Education Support', 'desc' => 'Learning materials, mentoring and school support so children can thrive.', 'icon' => 'book', 'img' => $program_images['education']],
          ['title' => 'Clothing & Essentials', 'desc' => 'Clothing and basic essentials distributed with respect and care.', 'icon' => 'bag-heart', 'img' => $program_images['clothing']],
          ['title' => 'Community Care', 'desc' => 'Programs that strengthen community bonds and mutual support.', 'icon' => 'people', 'img' => $program_images['community']],
          ['title' => 'Health Awareness', 'desc' => 'Awareness and support activities focused on community wellbeing.', 'icon' => 'heart-pulse', 'img' => $program_images['health']],
          ['title' => 'Volunteer Programs', 'desc' => 'Opportunities for people to give time and skills with purpose.', 'icon' => 'hand-thumbs-up', 'img' => $program_images['community']],
        ];
        foreach ($placeholders as $i => $ph): ?>
        <article class="card program-card" data-aos="fade-up" data-aos-delay="<?= (int)($i%3*80) ?>">
          <div class="card-media">
            <img src="<?= e($ph['img']) ?>" alt="" loading="lazy">
            <span class="media-label illustrative">Illustrative</span>
          </div>
          <div class="card-body">
            <div class="icon"><i class="bi bi-<?= e($ph['icon']) ?>"></i></div>
            <h3><?= e($ph['title']) ?></h3>
            <p><?= e($ph['desc']) ?></p>
          </div>
        </article>
      <?php endforeach; endif; ?>
    </div>
  </div>
</section>
<?php include __DIR__.'/includes/footer.php'; ?>
