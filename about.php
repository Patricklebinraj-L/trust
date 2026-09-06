<?php
require_once __DIR__.'/config/config.php';
$page_title = 'About Us | '.APP_NAME;
$meta_description = APP_NAME.' — Empowering lives through Education, Science, Heritage and Humanity. Registered public charitable trust in Tamil Nadu.';
include __DIR__.'/includes/header.php';
?>
<section class="page-hero">
  <div class="container" data-aos="fade-up">
    <span class="eyebrow">About the Trust</span>
    <h1>Serving People. Creating Hope.</h1>
    <p>Om Aathi Sivan Pancha Mugam Charitable Trust is a public charitable trust working for education, science, Tamil heritage, food support and community service.</p>
  </div>
</section>

<!-- Brand + identity -->
<section class="section">
  <div class="container grid-2" style="align-items:center;gap:3rem">
    <div class="about-logo-panel" data-aos="fade-right">
      <img src="<?= asset('images/brand/logo-full.jpg') ?>" alt="<?= e(APP_NAME) ?> — Serving People. Creating Hope." loading="lazy">
    </div>
    <div data-aos="fade-left">
      <span class="eyebrow">Who we are</span>
      <h2>A public charitable trust for human development.</h2>
      <p>Registered under <strong><?= e(TRUST_REG_NO) ?></strong> on <strong><?= e(TRUST_REG_DATE) ?></strong>, the Trust works across education, knowledge, science, agriculture, student welfare, Tamil heritage and social service — with activities confined to India and dedicated to purely charitable purposes.</p>
      <ul class="trust-facts">
        <li><i class="bi bi-building"></i> <span><strong>Type</strong> <?= e(TRUST_TYPE) ?></span></li>
        <li><i class="bi bi-geo-alt"></i> <span><strong>Office</strong> Thiruvannamalai, Tamil Nadu</span></li>
        <li><i class="bi bi-shield-check"></i> <span><strong>Status</strong> Irrevocable charitable trust</span></li>
      </ul>
    </div>
  </div>
</section>

<!-- Vision & Mission -->
<section class="section alt">
  <div class="container grid-2">
    <div class="card vision-card" data-aos="fade-up">
      <div class="icon"><i class="bi bi-eye"></i></div>
      <span class="eyebrow">Vision</span>
      <h3>Empowering lives through Education, Science, Heritage and Humanity.</h3>
      <p>To build an inclusive society where every person has access to education, knowledge, nutrition, science, culture and opportunities for personal and community development — regardless of economic background.</p>
    </div>
    <div class="card vision-card" data-aos="fade-up" data-aos-delay="80">
      <div class="icon"><i class="bi bi-bullseye"></i></div>
      <span class="eyebrow">Mission</span>
      <h3>Practical programmes that change lives.</h3>
      <p>To establish and support free or accessible educational, scientific, cultural, nutritional and community-development initiatives that improve the lives of students, families and society.</p>
    </div>
  </div>
</section>

<!-- Five pillars / Pancha Mugam -->
<section class="section" id="objectives">
  <div class="container">
    <div class="section-head center" data-aos="fade-up">
      <span class="eyebrow">Pancha Mugam — Five Dimensions of Service</span>
      <h2>Our core objectives</h2>
      <p>Five faces. One purpose — serving humanity through education, science, heritage, welfare and social service.</p>
    </div>
    <div class="pillars-grid">
      <article class="pillar-card" data-aos="fade-up">
        <div class="pillar-num">01</div>
        <div class="pillar-icon"><i class="bi bi-mortarboard"></i></div>
        <h3>கல்வி — Education</h3>
        <p>Accessible education pathways including Tamil-medium, English-medium, CBSE, arts &amp; science, ITI, polytechnic, engineering, agriculture, paramedical and medical learning support.</p>
      </article>
      <article class="pillar-card" data-aos="fade-up" data-aos-delay="60">
        <div class="pillar-num">02</div>
        <div class="pillar-icon"><i class="bi bi-lightbulb"></i></div>
        <h3>அறிவியல் — Science</h3>
        <p>Modern science and agriculture research initiatives, science awareness programmes and student-oriented scientific activities that connect knowledge with opportunity.</p>
      </article>
      <article class="pillar-card" data-aos="fade-up" data-aos-delay="120">
        <div class="pillar-num">03</div>
        <div class="pillar-icon"><i class="bi bi-book"></i></div>
        <h3>தமிழ் &amp; பண்பாடு — Heritage</h3>
        <p>Heritage Tamil Academy, cultural programmes, annual Pancha Mugam cultural initiatives and activities that celebrate Tamil language and culture.</p>
      </article>
      <article class="pillar-card" data-aos="fade-up" data-aos-delay="180">
        <div class="pillar-num">04</div>
        <div class="pillar-icon"><i class="bi bi-cup-hot"></i></div>
        <h3>உணவு &amp; நலன் — Welfare</h3>
        <p>Free meals and food assistance for students and families in need, including support for those preparing for TNPSC, banking and other competitive examinations.</p>
      </article>
      <article class="pillar-card pillar-card-wide" data-aos="fade-up" data-aos-delay="240">
        <div class="pillar-num">05</div>
        <div class="pillar-icon"><i class="bi bi-people"></i></div>
        <h3>சமூக சேவை — Social Service</h3>
        <p>Recognition of social-service achievements through the Pancha Mugam Festival, community outreach, libraries and digital knowledge programmes that strengthen society.</p>
      </article>
    </div>
  </div>
</section>

<!-- Programme highlights from deed -->
<section class="section alt">
  <div class="container">
    <div class="section-head" data-aos="fade-up">
      <span class="eyebrow">From our charter</span>
      <h2>Key programme areas</h2>
      <p>Objectives set out in the Trust’s governing documents include the following areas of work.</p>
    </div>
    <div class="grid-3">
      <div class="card" data-aos="fade-up"><div class="icon"><i class="bi bi-calendar-event"></i></div><h3>Cultural &amp; Youth Programmes</h3><p>Annual Pancha Mugam Cultural Programme for school and college students; APJ Abdul Kalam Jayanthi / Science &amp; Humanity Day programmes.</p></div>
      <div class="card" data-aos="fade-up" data-aos-delay="60"><div class="icon"><i class="bi bi-award"></i></div><h3>Social Service Awards</h3><p>Annual Pancha Mugam Festival recognising social-service achievements and celebrating a culture of service.</p></div>
      <div class="card" data-aos="fade-up" data-aos-delay="120"><div class="icon"><i class="bi bi-journal-richtext"></i></div><h3>Libraries &amp; Learning</h3><p>Free library and digital library support so knowledge is accessible to those who cannot afford expensive resources.</p></div>
      <div class="card" data-aos="fade-up"><div class="icon"><i class="bi bi-egg-fried"></i></div><h3>Meals Support</h3><p>Free meals support for students and parents in need, and for aspirants preparing for competitive examinations.</p></div>
      <div class="card" data-aos="fade-up" data-aos-delay="60"><div class="icon"><i class="bi bi-flower1"></i></div><h3>Agriculture Research</h3><p>Modern science agriculture research centre concepts linking traditional knowledge with scientific farming practices.</p></div>
      <div class="card" data-aos="fade-up" data-aos-delay="120"><div class="icon"><i class="bi bi-heart-pulse"></i></div><h3>Healthcare Pathway</h3><p>Long-term objectives include paramedical and medical education and healthcare-related charitable activities.</p></div>
    </div>
  </div>
</section>

<!-- Values -->
<section class="section">
  <div class="container">
    <div class="section-head center" data-aos="fade-up">
      <span class="eyebrow">How we work</span>
      <h2>Principles that guide us</h2>
    </div>
    <div class="grid-3">
      <div class="card" data-aos="fade-up"><div class="icon"><i class="bi bi-person-hearts"></i></div><h3>Dignity</h3><p>Support should respect every person's humanity.</p></div>
      <div class="card" data-aos="fade-up" data-aos-delay="80"><div class="icon"><i class="bi bi-eye"></i></div><h3>Transparency</h3><p>Verified information should be clear and reviewable. Impact figures are published only when evidence exists.</p></div>
      <div class="card" data-aos="fade-up" data-aos-delay="160"><div class="icon"><i class="bi bi-globe"></i></div><h3>India-focused service</h3><p>Charitable activities are confined to India, in line with the Trust’s governing purpose.</p></div>
    </div>
  </div>
</section>

<section class="section alt">
  <div class="container">
    <div class="cta-banner" data-aos="zoom-in">
      <h2>Be part of Pancha Mugam</h2>
      <p>Support education, science, heritage, welfare and social service — five dimensions, one vision for humanity.</p>
      <div class="actions d-flex gap-2 flex-wrap justify-content-center">
        <a class="btn btn-gold" href="<?= url('donate.php') ?>"><i class="bi bi-heart-fill"></i> Donate</a>
        <a class="btn btn-outline-light" href="<?= url('volunteer.php') ?>">Volunteer</a>
        <a class="btn btn-outline-light" href="<?= url('contact.php') ?>">Contact</a>
      </div>
    </div>
  </div>
</section>
<?php include __DIR__.'/includes/footer.php'; ?>
