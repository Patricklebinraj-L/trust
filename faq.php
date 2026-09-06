<?php
require_once __DIR__.'/config/config.php';
$page_title = 'FAQ | '.APP_NAME;
try {
  $faqs = db()->query("SELECT * FROM faqs WHERE status='published' ORDER BY sort_order, id")->fetchAll();
} catch (Throwable $e) { $faqs = []; }
include __DIR__.'/includes/header.php';
?>
<section class="page-hero">
  <div class="container" data-aos="fade-up">
    <span class="eyebrow">FAQ</span>
    <h1>Questions, answered clearly.</h1>
    <p>Straightforward answers about the foundation, programs and how to get involved.</p>
  </div>
</section>
<section class="section">
  <div class="container" style="max-width:800px">
    <div class="faq-list">
      <?php if ($faqs): foreach ($faqs as $i => $f): ?>
        <details data-aos="fade-up" data-aos-delay="<?= (int)($i*40) ?>">
          <summary><?= e($f['question']) ?></summary>
          <p><?= nl2br(e($f['answer'])) ?></p>
        </details>
      <?php endforeach; else: ?>
        <div class="empty-state">
          <i class="bi bi-question-circle"></i>
          <h3>No FAQs published yet</h3>
          <p>Frequently asked questions will appear here once added through the CMS.</p>
        </div>
      <?php endif; ?>
    </div>
    <p class="text-center mt-4"><a class="link" href="<?= url('contact.php') ?>">Still have a question? Contact us <i class="bi bi-arrow-right"></i></a></p>
  </div>
</section>
<?php include __DIR__.'/includes/footer.php'; ?>
