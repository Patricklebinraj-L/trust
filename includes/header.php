<?php
require_once __DIR__ . '/../config/config.php';
$current = basename($_SERVER['SCRIPT_NAME'] ?? 'index.php');
?>
<!doctype html>
<html lang="en" data-theme="light">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<meta name="description" content="<?= e($meta_description ?? APP_NAME . ' — ' . APP_TAGLINE . ' Education, science, Tamil heritage, food support and community service.') ?>">
<meta name="theme-color" content="#1A1A1A">
<title><?= e($page_title ?? APP_NAME) ?></title>
<link rel="icon" type="image/png" sizes="32x32" href="<?= asset('images/brand/favicon-32.png') ?>">
<link rel="icon" type="image/png" sizes="64x64" href="<?= asset('images/brand/favicon-64.png') ?>">
<link rel="apple-touch-icon" href="<?= asset('images/brand/favicon-64.png') ?>">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Cormorant+Garamond:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" rel="stylesheet">
<link rel="stylesheet" href="<?= asset('css/style.css') ?>">
</head>
<body>
<a class="skip-link" href="#main">Skip to content</a>

<header class="site-header" id="siteHeader">
  <div class="container nav-wrap">
    <a class="brand" href="<?= url('index.php') ?>">
      <img class="brand-logo" src="<?= asset('images/brand/logo-icon.jpg') ?>" alt="<?= e(APP_SHORT_NAME) ?>" width="42" height="42">
      <span class="brand-text"><b>Pancha Mugam</b><small>Charitable Trust</small></span>
    </a>

    <nav class="main-nav d-none d-lg-flex" id="mainNav" aria-label="Primary">
      <a class="<?= is_active('index.php') ?>" href="<?= url('index.php') ?>">Home</a>
      <a class="<?= is_active('about.php') ?>" href="<?= url('about.php') ?>">About</a>
      <a class="<?= is_active('programs.php') || is_active('program.php') ?>" href="<?= url('programs.php') ?>">Programs</a>
      <a class="<?= is_active('work.php') ?>" href="<?= url('work.php') ?>">Our Work</a>
      <a class="<?= is_active('gallery.php') ?>" href="<?= url('gallery.php') ?>">Gallery</a>
      <a class="<?= is_active('events.php') ?>" href="<?= url('events.php') ?>">Events</a>
      <a class="<?= is_active('news.php') ?>" href="<?= url('news.php') ?>">News</a>
      <a class="<?= is_active('volunteer.php') ?>" href="<?= url('volunteer.php') ?>">Volunteer</a>
      <a class="nav-donate" href="<?= url('donate.php') ?>"><i class="bi bi-heart-fill me-1"></i>Donate</a>
    </nav>

    <div class="header-actions">
      <button type="button" class="theme-toggle" id="themeToggle" aria-label="Toggle dark mode" title="Toggle theme">
        <i class="bi bi-moon-stars" id="themeIcon"></i>
      </button>
      <button class="menu-toggle" type="button" id="mobileMenuBtn" aria-controls="mobileDrawer" aria-expanded="false" aria-label="Open menu">
        <i class="bi bi-list" id="mobileMenuIcon"></i>
      </button>
    </div>
  </div>
</header>

<div class="mobile-drawer-backdrop" id="mobileBackdrop" hidden></div>
<aside class="mobile-drawer" id="mobileDrawer" aria-hidden="true" aria-label="Mobile navigation">
  <div class="mobile-drawer-header">
    <a class="brand" href="<?= url('index.php') ?>">
      <img class="brand-logo" src="<?= asset('images/brand/logo-icon.jpg') ?>" alt="" width="40" height="40">
      <span class="brand-text"><b>Pancha Mugam</b><small>Charitable Trust</small></span>
    </a>
    <button type="button" class="mobile-drawer-close" id="mobileMenuClose" aria-label="Close menu">
      <i class="bi bi-x-lg"></i>
    </button>
  </div>
  <nav class="mobile-drawer-nav" aria-label="Mobile">
    <a class="<?= is_active('index.php') ?>" href="<?= url('index.php') ?>">Home</a>
    <a class="<?= is_active('about.php') ?>" href="<?= url('about.php') ?>">About</a>
    <a class="<?= is_active('programs.php') || is_active('program.php') ?>" href="<?= url('programs.php') ?>">Programs</a>
    <a class="<?= is_active('work.php') ?>" href="<?= url('work.php') ?>">Our Work</a>
    <a class="<?= is_active('gallery.php') ?>" href="<?= url('gallery.php') ?>">Gallery</a>
    <a class="<?= is_active('events.php') ?>" href="<?= url('events.php') ?>">Events</a>
    <a class="<?= is_active('news.php') ?>" href="<?= url('news.php') ?>">News</a>
    <a class="<?= is_active('faq.php') ?>" href="<?= url('faq.php') ?>">FAQ</a>
    <a class="<?= is_active('volunteer.php') ?>" href="<?= url('volunteer.php') ?>">Volunteer</a>
    <a class="<?= is_active('contact.php') ?>" href="<?= url('contact.php') ?>">Contact</a>
    <a class="mobile-donate" href="<?= url('donate.php') ?>"><i class="bi bi-heart-fill"></i> Donate Now</a>
  </nav>
</aside>

<main id="main">
