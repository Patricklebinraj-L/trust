<?php
require_once __DIR__.'/../config/config.php';
if (empty($_SESSION['admin_id'])) {
  redirect(url('admin/login.php'));
}
$counts = [];
$tables = ['programs','news_articles','events','volunteers','donations','contact_messages','gallery','faqs'];
foreach ($tables as $t) {
  try {
    $counts[$t] = (int) db()->query("SELECT COUNT(*) FROM `$t`")->fetchColumn();
  } catch (Throwable $e) {
    $counts[$t] = 0;
  }
}
$page_title = 'Dashboard';
include __DIR__.'/header.php';
?>
<div class="admin-topbar">
  <div>
    <h1 style="font-size:1.75rem;margin:0">Dashboard</h1>
    <p style="margin:0.25rem 0 0;color:var(--c-muted)">Welcome<?= !empty($_SESSION['admin_name']) ? ', '.e($_SESSION['admin_name']) : '' ?>.</p>
  </div>
  <a class="btn btn-outline-primary btn-sm" href="<?= url('admin/logout.php') ?>"><i class="bi bi-box-arrow-right"></i> Sign out</a>
</div>

<div class="grid-4 mb-4">
  <?php
  $labels = [
    'programs' => ['Programs','folder'],
    'news_articles' => ['News','newspaper'],
    'events' => ['Events','calendar-event'],
    'volunteers' => ['Volunteers','people'],
    'donations' => ['Donations','heart'],
    'contact_messages' => ['Messages','envelope'],
    'gallery' => ['Gallery','images'],
    'faqs' => ['FAQs','question-circle'],
  ];
  foreach ($counts as $k => $v):
    $meta = $labels[$k] ?? [$k, 'circle'];
  ?>
  <div class="stat-card">
    <div class="d-flex justify-content-between align-items-start">
      <div>
        <div class="value"><?= $v ?></div>
        <div class="label"><?= e($meta[0]) ?></div>
      </div>
      <i class="bi bi-<?= e($meta[1]) ?>" style="font-size:1.5rem;color:var(--c-primary);opacity:0.5"></i>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<div class="card">
  <h3>CMS foundation</h3>
  <p>This dashboard shows live counts from the database. Full CRUD screens for each entity can be expanded here. Content is managed through the existing schema and migrations.</p>
  <ul style="color:var(--c-muted)">
    <li>Programs, news, events, gallery and FAQs drive the public site</li>
    <li>Volunteer and contact form submissions are stored for follow-up</li>
    <li>Donation requests are recorded with pending status until a payment provider is connected</li>
  </ul>
  <a class="btn btn-primary mt-2" href="<?= url('index.php') ?>" target="_blank"><i class="bi bi-box-arrow-up-right"></i> Open public site</a>
</div>
<?php include __DIR__.'/footer.php'; ?>
