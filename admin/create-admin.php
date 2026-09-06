<?php
require_once __DIR__.'/../config/config.php';
$message = ''; $error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';
  if ($name && filter_var($email, FILTER_VALIDATE_EMAIL) && strlen($password) >= 10) {
    try {
      $s = db()->prepare("INSERT INTO admins(name,email,password_hash) VALUES(?,?,?)");
      $s->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT)]);
      $message = 'Admin created successfully. Delete this file from production immediately.';
    } catch (Throwable $e) {
      $error = 'Could not create admin. Email may already exist or database is unavailable.';
    }
  } else {
    $error = 'Use a valid email and a password of at least 10 characters.';
  }
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title>Create Admin | <?= e(APP_NAME) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="<?= asset('css/style.css') ?>">
<style>
  body {
    min-height: 100vh; display: grid; place-items: center; padding: 1.5rem;
    background:
      radial-gradient(ellipse 70% 60% at 100% 0%, rgba(232,114,12,.35), transparent 55%),
      radial-gradient(ellipse 60% 60% at 0% 100%, rgba(232,114,12,.25), transparent 55%),
      linear-gradient(155deg, #1A1A1A 0%, #0D0D0D 100%);
  }
  .card {
    width: min(420px, 100%);
    animation: fadeScaleIn .6s var(--ease) both;
    box-shadow: var(--shadow-lg);
    border: 1px solid rgba(255,255,255,.5);
  }
</style>
</head>
<body>
<div class="card form">
  <h2 style="margin-top:0">Create initial admin</h2>
  <p style="color:var(--c-muted);font-size:0.9rem">Run once, then delete this file.</p>
  <?php if ($message): ?><div class="alert"><?= e($message) ?></div><?php endif; ?>
  <?php if ($error): ?><div class="alert alert-error"><?= e($error) ?></div><?php endif; ?>
  <form method="post">
    <div class="field"><label>Name</label><input name="name" required></div>
    <div class="field"><label>Email</label><input name="email" type="email" required></div>
    <div class="field"><label>Password (min 10 chars)</label><input name="password" type="password" required minlength="10"></div>
    <button type="submit" class="btn btn-primary w-100">Create admin</button>
  </form>
</div>
</body>
</html>
