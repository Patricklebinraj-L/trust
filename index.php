<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/pixabay.php';
$page_title = APP_NAME;
$meta_description = APP_NAME . ' — ' . APP_TAGLINE . ' Education, science, Tamil heritage, food support and social service.';

try {
    $programs = db()->query("SELECT * FROM programs WHERE status='published' ORDER BY id LIMIT 6")->fetchAll();
} catch (Throwable $e) {
    $programs = [];
}

try {
    $mission = db()->query("SELECT setting_value FROM settings WHERE setting_key='mission'")->fetchColumn()
        ?: 'To establish and support free or accessible educational, scientific, cultural, nutritional and community-development initiatives that improve the lives of students, families and society.';
} catch (Throwable $e) {
    $mission = 'To establish and support free or accessible educational, scientific, cultural, nutritional and community-development initiatives that improve the lives of students, families and society.';
}

    $pixabay_images = pixabay_images();
$program_images = [
      'food' => $pixabay_images[3]['url'] ?? asset('images/programs/food-support.jpg'),
      'education' => $pixabay_images[4]['url'] ?? asset('images/programs/education.jpg'),
      'clothing' => $pixabay_images[5]['url'] ?? asset('images/programs/clothing.jpg'),
      'community' => $pixabay_images[6]['url'] ?? asset('images/programs/community.jpg'),
      'health' => $pixabay_images[7]['url'] ?? asset('images/programs/health.jpg'),
    'default' => asset('images/programs/community.jpg'),
];
  $hero_image = pixabay_image(0) ?: ['url' => asset('images/hero/hero-main.jpg'), 'alt' => 'Community support'];
  $hope_image = pixabay_image(1) ?: ['url' => asset('images/hero/children-hope.jpg'), 'alt' => 'Children receiving community support'];
  $mission_image = pixabay_image(2) ?: ['url' => asset('images/hero/volunteer-team.jpg'), 'alt' => 'Community volunteers'];

include __DIR__ . '/includes/header.php';
?>

<!-- HERO -->
<section class="hero hero-v2">
  <div class="hero-bg" aria-hidden="true">
    <img src="<?= e($hero_image['url']) ?>" alt="" class="hero-bg-img" fetchpriority="high">
    <div class="hero-bg-overlay"></div>
  </div>
  <div class="hero-content">
    <div class="hero-copy" data-aos="fade-up">
      <span class="eyebrow hero-eyebrow"><?= e(APP_TAGLINE) ?></span>
      <h1>Together, We Can Turn<br><span class="text-grad">Compassion Into Change.</span></h1>
      <p class="lead">Om Aathi Sivan Pancha Mugam Charitable Trust empowers communities through education, science, Tamil heritage, food support and social service.</p>
      <div class="actions">
        <a class="btn btn-primary btn-lg" href="<?= url('donate.php') ?>"><i class="bi bi-heart-fill"></i> Support Our Mission</a>
        <a class="btn-hero-outline btn-lg" href="<?= url('about.php') ?>">Discover Our Mission <i class="bi bi-arrow-right"></i></a>
      </div>
      <div class="hero-trust">
        <i class="bi bi-shield-check"></i>
        <span>Public Charitable Trust · Reg. <?= e(TRUST_REG_NO) ?></span>
      </div>
    </div>
    <div class="hero-card-stack" data-aos="fade-up" data-aos-delay="120">
      <div class="hero-photo-card">
        <img src="<?= e($hope_image['url']) ?>" alt="<?= e($hope_image['alt']) ?> — illustrative" loading="eager">
        <span class="media-label illustrative">Illustrative · Pixabay</span>
      </div>
      <div class="hero-floating-card">
        <div class="hfc-icon"><i class="bi bi-heart-fill"></i></div>
        <div>
          <strong>Five dimensions. One purpose.</strong>
          <span>Education · Science · Heritage · Welfare · Service</span>
        </div>
      </div>
    </div>
  </div>
  <a href="#pancha-mugam" class="scroll-indicator d-none d-md-flex" aria-label="Scroll to pillars">
    <span>Scroll</span>
    <i class="bi bi-chevron-down"></i>
  </a>
</section>

<!-- PANCHA MUGAM -->
<section class="section pillars-section" id="pancha-mugam">
  <div class="container">
    <div class="section-head center" data-aos="fade-up">
      <span class="eyebrow">Pancha Mugam</span>
      <h2>Five dimensions of service</h2>
      <p>One vision for humanity — education, science, Tamil heritage, welfare and social service working together.</p>
    </div>
    <div class="pillars-grid">
      <article class="pillar-card" data-aos="fade-up">
        <div class="pillar-num">01</div>
        <div class="pillar-icon"><i class="bi bi-mortarboard-fill"></i></div>
        <h3>கல்வி — Education</h3>
        <p>Accessible pathways across Tamil-medium, English-medium, arts, science, technical and higher education support.</p>
      </article>
      <article class="pillar-card" data-aos="fade-up" data-aos-delay="60">
        <div class="pillar-num">02</div>
        <div class="pillar-icon"><i class="bi bi-lightbulb-fill"></i></div>
        <h3>அறிவியல் — Science</h3>
        <p>Science awareness, agriculture research initiatives and student-oriented programmes that connect knowledge with opportunity.</p>
      </article>
      <article class="pillar-card" data-aos="fade-up" data-aos-delay="120">
        <div class="pillar-num">03</div>
        <div class="pillar-icon"><i class="bi bi-book-half"></i></div>
        <h3>தமிழ் — Heritage</h3>
        <p>Heritage Tamil Academy, cultural programmes and the annual Pancha Mugam cultural initiatives for students and community.</p>
      </article>
      <article class="pillar-card" data-aos="fade-up" data-aos-delay="180">
        <div class="pillar-num">04</div>
        <div class="pillar-icon"><i class="bi bi-cup-hot-fill"></i></div>
        <h3>உணவு — Welfare</h3>
        <p>Free meals and support for students and families in need, including aspirants preparing for competitive examinations.</p>
      </article>
      <article class="pillar-card" data-aos="fade-up" data-aos-delay="240">
        <div class="pillar-num">05</div>
        <div class="pillar-icon"><i class="bi bi-people-fill"></i></div>
        <h3>சேவை — Social Service</h3>
        <p>Recognition of service through the Pancha Mugam Festival, libraries, digital knowledge and community outreach.</p>
      </article>
    </div>
    <div class="text-center mt-4" data-aos="fade-up">
      <a class="btn btn-outline-primary" href="<?= url('about.php') ?>#objectives">Explore all objectives <i class="bi bi-arrow-right"></i></a>
    </div>
  </div>
</section>

<!-- PROGRAMMES -->
<section class="section alt" id="programs">
  <div class="container">
    <div class="section-head" data-aos="fade-up">
      <span class="eyebrow">What We Do</span>
      <h2>Practical programmes with dignity.</h2>
      <p>Initiatives designed around education, care and stronger communities — published through the Trust CMS when available.</p>
    </div>
    <div class="grid-3">
      <?php if ($programs): ?>
        <?php foreach ($programs as $i => $p):
          $slug = strtolower($p['slug'] ?? '');
          $img = $program_images['default'];
          if (str_contains($slug, 'food') || str_contains($slug, 'meal')) $img = $program_images['food'];
          elseif (str_contains($slug, 'educat') || str_contains($slug, 'school')) $img = $program_images['education'];
          elseif (str_contains($slug, 'cloth')) $img = $program_images['clothing'];
          elseif (str_contains($slug, 'health')) $img = $program_images['health'];
          elseif (str_contains($slug, 'communit')) $img = $program_images['community'];
          if (!empty($p['image'])) $img = $p['image'];
        ?>
          <article class="card program-card" data-aos="fade-up" data-aos-delay="<?= (int)($i * 70) ?>">
            <div class="card-media">
              <img src="<?= e($img) ?>" alt="<?= e($p['title']) ?>" loading="lazy">
              <?php if (empty($p['image'])): ?><span class="media-label illustrative">Illustrative</span><?php endif; ?>
            </div>
            <div class="card-body">
              <div class="icon"><i class="bi bi-<?= e($p['icon'] ?: 'heart') ?>"></i></div>
              <h3><?= e($p['title']) ?></h3>
              <p><?= e($p['short_description']) ?></p>
              <a class="link" href="<?= url('program.php?slug=' . urlencode($p['slug'])) ?>">Learn more <i class="bi bi-arrow-right"></i></a>
            </div>
          </article>
        <?php endforeach; ?>
      <?php else: ?>
        <?php
        $placeholders = [
          ['title' => 'Education Support', 'desc' => 'Learning materials, mentoring and pathways so students can thrive.', 'icon' => 'mortarboard', 'img' => $program_images['education']],
          ['title' => 'Food & Student Welfare', 'desc' => 'Meals and assistance for students and families facing hardship.', 'icon' => 'cup-hot', 'img' => $program_images['food']],
          ['title' => 'Community Service', 'desc' => 'Outreach, recognition of service and programmes that strengthen society.', 'icon' => 'people', 'img' => $program_images['community']],
        ];
        foreach ($placeholders as $i => $ph): ?>
          <article class="card program-card" data-aos="fade-up" data-aos-delay="<?= (int)($i * 70) ?>">
            <div class="card-media">
              <img src="<?= e($ph['img']) ?>" alt="<?= e($ph['title']) ?>" loading="lazy">
              <span class="media-label illustrative">Illustrative</span>
            </div>
            <div class="card-body">
              <div class="icon"><i class="bi bi-<?= e($ph['icon']) ?>"></i></div>
              <h3><?= e($ph['title']) ?></h3>
              <p><?= e($ph['desc']) ?></p>
              <a class="link" href="<?= url('programs.php') ?>">Learn more <i class="bi bi-arrow-right"></i></a>
            </div>
          </article>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
    <div class="text-center mt-4" data-aos="fade-up">
      <a class="btn btn-outline-primary" href="<?= url('programs.php') ?>">View All Programmes <i class="bi bi-arrow-right"></i></a>
    </div>
  </div>
</section>

<!-- MISSION -->
<section class="section">
  <div class="container grid-2" style="align-items:center;gap:3rem">
    <div data-aos="fade-right">
      <span class="eyebrow">Our Mission</span>
      <h2>Empowering lives through Education, Science, Heritage and Humanity.</h2>
      <p class="lead mt-2"><?= e($mission) ?></p>
      <a class="btn btn-primary mt-3" href="<?= url('about.php') ?>">Our vision &amp; objectives <i class="bi bi-arrow-right"></i></a>
    </div>
    <div class="mission-photo" data-aos="fade-left">
      <img src="<?= e($mission_image['url']) ?>" alt="<?= e($mission_image['alt']) ?> — illustrative" loading="lazy">
      <span class="media-label illustrative">Illustrative · Pixabay</span>
    </div>
  </div>
</section>

<!-- IMPACT PLACEHOLDER -->
<section class="section impact">
  <div class="container">
    <div class="section-head center" data-aos="fade-up">
      <span class="eyebrow">Impact</span>
      <h2>Evidence before numbers.</h2>
      <p>Verified organisational statistics appear here only when documented. We never invent impact figures.</p>
    </div>
    <div class="stats" data-aos="fade-up" data-aos-delay="80">
      <div class="stat"><strong>—</strong><span>Verified metrics</span></div>
      <div class="stat"><strong>—</strong><span>when evidence exists</span></div>
      <div class="stat"><strong>—</strong><span>from the Trust CMS</span></div>
      <div class="stat"><strong>—</strong><span>Never fabricated</span></div>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="section alt">
  <div class="container">
    <div class="cta-banner" data-aos="zoom-in">
      <h2>Be part of Pancha Mugam.</h2>
      <p>Support education, science, heritage, welfare and social service — five dimensions, one vision for humanity.</p>
      <div class="actions d-flex gap-2 flex-wrap justify-content-center">
        <a class="btn btn-gold" href="<?= url('donate.php') ?>"><i class="bi bi-heart-fill"></i> Donate Now</a>
        <a class="btn btn-outline-light" href="<?= url('volunteer.php') ?>"><i class="bi bi-person-heart"></i> Volunteer</a>
        <a class="btn btn-outline-light" href="<?= url('contact.php') ?>">Contact Us</a>
      </div>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
