</main>

<footer class="site-footer">
  <div class="footer-wave" aria-hidden="true"></div>
  <div class="container">
    <div class="footer-grid">
      <div class="footer-brand-col" data-aos="fade-up">
        <a class="brand footer-brand" href="<?= url('index.php') ?>">
          <img class="brand-logo" src="<?= asset('images/brand/logo-icon.jpg') ?>" alt="" width="42" height="42">
          <span class="brand-text"><b>Pancha Mugam</b><small>Charitable Trust</small></span>
        </a>
        <p class="footer-mission"><?= e(APP_TAGLINE) ?> Education, science, Tamil heritage, food support and community service.</p>
        <p class="footer-reg" style="font-size:0.8rem;color:rgba(255,255,255,0.5);margin-top:0.5rem">Reg. <?= e(TRUST_REG_NO) ?> · <?= e(TRUST_REG_DATE) ?></p>
        <div class="footer-social">
          <a href="#" aria-label="Facebook" class="social-link"><i class="bi bi-facebook"></i></a>
          <a href="#" aria-label="Instagram" class="social-link"><i class="bi bi-instagram"></i></a>
          <a href="#" aria-label="YouTube" class="social-link"><i class="bi bi-youtube"></i></a>
        </div>
      </div>

      <div data-aos="fade-up" data-aos-delay="50">
        <h4>Explore</h4>
        <ul class="footer-links">
          <li><a href="<?= url('about.php') ?>">About Us</a></li>
          <li><a href="<?= url('programs.php') ?>">Programs</a></li>
          <li><a href="<?= url('work.php') ?>">Our Work</a></li>
          <li><a href="<?= url('gallery.php') ?>">Gallery</a></li>
          <li><a href="<?= url('news.php') ?>">News</a></li>
        </ul>
      </div>

      <div data-aos="fade-up" data-aos-delay="100">
        <h4>Get Involved</h4>
        <ul class="footer-links">
          <li><a href="<?= url('volunteer.php') ?>">Volunteer</a></li>
          <li><a href="<?= url('donate.php') ?>">Donate</a></li>
          <li><a href="<?= url('events.php') ?>">Events</a></li>
          <li><a href="<?= url('faq.php') ?>">FAQ</a></li>
          <li><a href="<?= url('contact.php') ?>">Contact</a></li>
        </ul>
      </div>

      <div data-aos="fade-up" data-aos-delay="150">
        <h4>Registered Office</h4>
        <p class="footer-newsletter-text" style="margin-bottom:1rem"><?= e(TRUST_ADDRESS) ?></p>
        <a class="btn btn-outline-light btn-sm" href="<?= url('donate.php') ?>"><i class="bi bi-heart-fill me-1"></i> Support Our Mission</a>
      </div>
    </div>

    <div class="footer-bottom">
      <span>&copy; <?= date('Y') ?> <?= e(APP_NAME) ?>. All rights reserved.</span>
      <span class="footer-legal">
        <a href="#">Privacy</a>
        <a href="#">Terms</a>
        <a href="#">Accessibility</a>
      </span>
    </div>
  </div>
</footer>

<button type="button" class="back-to-top" id="backToTop" aria-label="Back to top" title="Back to top">
  <i class="bi bi-arrow-up"></i>
</button>

<?php $donation_image = pixabay_image(9) ?: ['url' => asset('images/hero/children-hope.jpg'), 'alt' => 'Children receiving support']; ?>
<?php if (basename($_SERVER['SCRIPT_NAME'] ?? '') !== 'donate.php'): ?>
<div class="donation-modal" id="donationModal" hidden>
  <div class="donation-modal-backdrop" data-modal-close></div>
  <section class="donation-dialog" role="dialog" aria-modal="true" aria-labelledby="donationModalTitle" aria-describedby="donationModalText">
    <button class="donation-modal-close" type="button" data-modal-close aria-label="Close donation message"><i class="bi bi-x-lg"></i></button>
    <div class="donation-dialog-media">
      <img src="<?= e($donation_image['url']) ?>" alt="<?= e($donation_image['alt']) ?> — illustrative media" loading="lazy">
      <span class="media-label illustrative">Illustrative · Pixabay</span>
    </div>
    <div class="donation-dialog-copy">
      <span class="eyebrow">A little help, right on time</span>
      <h2 id="donationModalTitle">Your ₹100 can help move a meal closer.</h2>
      <p id="donationModalText">When people come together, a modest contribution can support food and dignity for children and families facing hardship.</p>
      <a class="btn btn-primary btn-lg" href="<?= url('donate.php') ?>"><i class="bi bi-heart-fill"></i> Donate</a>
      <small><i class="bi bi-shield-check"></i> Give with clarity. Donation requests are recorded securely.</small>
    </div>
  </section>
</div>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="<?= asset('js/app.js') ?>?v=<?= (int)@filemtime(__DIR__ . '/../assets/js/app.js') ?>"></script>
</body>
</html>
