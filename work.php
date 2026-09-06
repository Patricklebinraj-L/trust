<?php
require_once __DIR__.'/config/config.php';
$page_title = 'Our Work | '.APP_NAME;
include __DIR__.'/includes/header.php';
?>
<section class="page-hero">
  <div class="container" data-aos="fade-up">
    <span class="eyebrow">Our Work in Action</span>
    <h1>Real work deserves real evidence.</h1>
    <p>Use this area for verified activities, project reports, food distribution, education activities, volunteer work and events.</p>
  </div>
</section>
<section class="section">
  <div class="container grid-3">
    <div class="card program-card" data-aos="fade-up">
      <div class="card-media">
        <img src="<?= asset('images/gallery/gallery-3.jpg') ?>" alt="" loading="lazy">
        <span class="media-label illustrative">Illustrative</span>
      </div>
      <div class="card-body">
        <div class="icon"><i class="bi bi-clipboard2-check"></i></div>
        <h3>Verified Activities</h3>
        <p>Upload approved activity records and media through the admin CMS.</p>
      </div>
    </div>
    <div class="card program-card" data-aos="fade-up" data-aos-delay="80">
      <div class="card-media">
        <img src="<?= asset('images/programs/education.jpg') ?>" alt="" loading="lazy">
        <span class="media-label illustrative">Illustrative</span>
      </div>
      <div class="card-body">
        <div class="icon"><i class="bi bi-file-earmark-bar-graph"></i></div>
        <h3>Project Reports</h3>
        <p>Publish verified project reports with dates and supporting documentation.</p>
      </div>
    </div>
    <div class="card program-card" data-aos="fade-up" data-aos-delay="160">
      <div class="card-media">
        <img src="<?= asset('images/gallery/gallery-1.jpg') ?>" alt="" loading="lazy">
        <span class="media-label illustrative">Illustrative</span>
      </div>
      <div class="card-body">
        <div class="icon"><i class="bi bi-calendar2-event"></i></div>
        <h3>Community Events</h3>
        <p>Connect activities to events, galleries and stories.</p>
      </div>
    </div>
  </div>
</section>
<section class="section alt">
  <div class="container">
    <div class="cta-banner" data-aos="zoom-in">
      <h2>See our gallery & events</h2>
      <p>Browse visual moments and upcoming gatherings that bring the community together.</p>
      <div class="actions d-flex gap-2 flex-wrap justify-content-center">
        <a class="btn btn-gold" href="<?= url('gallery.php') ?>">Gallery</a>
        <a class="btn btn-outline-light" href="<?= url('events.php') ?>">Events</a>
      </div>
    </div>
  </div>
</section>
<?php include __DIR__.'/includes/footer.php'; ?>
