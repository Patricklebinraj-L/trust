<?php
require_once __DIR__.'/config/config.php';
$page_title = 'Events | '.APP_NAME;
try {
  $events = db()->query("SELECT * FROM events ORDER BY event_date DESC")->fetchAll();
} catch (Throwable $e) { $events = []; }
include __DIR__.'/includes/header.php';
?>
<section class="page-hero">
  <div class="container" data-aos="fade-up">
    <span class="eyebrow">Events</span>
    <h1>Gathering people around purpose.</h1>
    <p>Upcoming and past events can be managed through the CMS.</p>
  </div>
</section>
<section class="section">
  <div class="container grid-3">
    <?php if ($events): foreach ($events as $i => $e):
      $ts = strtotime($e['event_date']);
      $day = date('d', $ts);
      $month = date('M', $ts);
    ?>
      <article class="card event-card" data-aos="fade-up" data-aos-delay="<?= (int)($i%3*80) ?>">
        <div class="event-date-badge">
          <span class="day"><?= e($day) ?></span>
          <span class="month"><?= e($month) ?></span>
        </div>
        <h3><?= e($e['name']) ?></h3>
        <p><?= e($e['description']) ?></p>
        <div class="event-meta">
          <?php if (!empty($e['venue'])): ?><span><i class="bi bi-geo-alt"></i> <?= e($e['venue']) ?></span><?php endif; ?>
          <?php if (!empty($e['event_time'])): ?><span><i class="bi bi-clock"></i> <?= e(substr($e['event_time'],0,5)) ?></span><?php endif; ?>
          <span><i class="bi bi-tag"></i> <?= e(ucfirst($e['status'] ?? 'upcoming')) ?></span>
        </div>
      </article>
    <?php endforeach; else: ?>
      <div class="empty-state" style="grid-column:1/-1">
        <i class="bi bi-calendar-x"></i>
        <h3>No events published yet</h3>
        <p>Add verified events through the admin dashboard.</p>
      </div>
    <?php endif; ?>
  </div>
</section>
<?php include __DIR__.'/includes/footer.php'; ?>
