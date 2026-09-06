<?php
require_once __DIR__.'/../config/config.php';
$error = '';
if (!empty($_SESSION['admin_id'])) {
  redirect(url('admin/index.php'));
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';
  try {
    $s = db()->prepare("SELECT * FROM admins WHERE email = ? LIMIT 1");
    $s->execute([$email]);
    $admin = $s->fetch();
    if ($admin && password_verify($password, $admin['password_hash'])) {
      $_SESSION['admin_id'] = $admin['id'];
      $_SESSION['admin_name'] = $admin['name'];
      redirect(url('admin/index.php'));
    }
    $error = 'Invalid email or password.';
  } catch (Throwable $e) {
    $error = 'Login temporarily unavailable.';
  }
}
$page_title = 'Admin Login';
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title>Admin Login | <?= e(APP_NAME) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link rel="stylesheet" href="<?= asset('css/style.css') ?>">
<style>
  body {
    min-height: 100vh; display: grid; place-items: center;
    background:
      radial-gradient(ellipse 70% 60% at 100% 0%, rgba(232,114,12,.35), transparent 55%),
      radial-gradient(ellipse 60% 60% at 0% 100%, rgba(232,114,12,.25), transparent 55%),
      linear-gradient(155deg, #1A1A1A 0%, #0D0D0D 100%);
    padding: 1.5rem;
  }
  .login-card {
    width: min(400px, 100%);
    animation: fadeScaleIn .6s var(--ease) both;
    box-shadow: var(--shadow-lg);
    border: 1px solid rgba(255,255,255,.5);
  }
  .login-brand-mark {
    display: inline-grid; place-items: center;
    width: 56px; height: 56px; font-size: 1.5rem;
    border-radius: 16px; color: #F7F1E6;
    background: linear-gradient(145deg, #1A1A1A, #E8720C);
    box-shadow: 0 10px 26px rgba(26,26,26,.3);
  }
</style>
</head>
<body>
  <div class="card login-card form">
    <div class="text-center mb-3">
      <span class="login-brand-mark">ॐ</span>
      <h1 style="font-size:1.5rem;margin:0.75rem 0 0.25rem">Admin Sign In</h1>
      <p style="margin:0;font-size:0.9rem;color:var(--c-muted)">Om Shanthi Trust & Foundation</p>
    </div>
    <?php if ($error): ?><script>showToast('Login Error', <?= json_encode($error) ?>, 'error', 8000);</script><?php endif; ?>
    <form method="post">
      <div class="field"><label for="email">Email</label><input id="email" name="email" type="email" required autocomplete="username"></div>
      <div class="field"><label for="password">Password</label><input id="password" name="password" type="password" required autocomplete="current-password"></div>
      <button type="submit" class="btn btn-primary w-100">Sign in</button>
    </form>
    <p class="text-center mt-3 mb-0" style="font-size:0.85rem"><a href="<?= url('index.php') ?>" class="link">← Back to site</a></p>
  </div>
</body>
</html>
