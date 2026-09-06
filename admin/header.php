<?php require_once __DIR__.'/../config/config.php'; ?>
<!doctype html>
<html lang="en" data-theme="light">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title><?= e($page_title ?? 'Admin') ?> | <?= e(APP_NAME) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link rel="stylesheet" href="<?= asset('css/style.css') ?>">
</head>
<body>
<div class="admin-layout">
  <aside class="admin-sidebar">
    <a class="brand" href="<?= url('admin/index.php') ?>">
      <span class="brand-mark">ॐ</span>
      <span class="brand-text"><b>Om Shanthi</b><small>Admin CMS</small></span>
    </a>
    <nav class="admin-nav">
      <a class="active" href="<?= url('admin/index.php') ?>"><i class="bi bi-grid-1x2"></i> Dashboard</a>
      <a href="<?= url('index.php') ?>" target="_blank"><i class="bi bi-box-arrow-up-right"></i> View Site</a>
      <a href="<?= url('admin/logout.php') ?>"><i class="bi bi-box-arrow-right"></i> Sign out</a>
    </nav>
  </aside>
  <div class="admin-main">
