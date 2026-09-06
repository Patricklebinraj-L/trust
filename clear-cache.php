<?php
declare(strict_types=1);

require_once __DIR__ . '/config/config.php';

header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: 0');

$deletedCacheFiles = 0;
$cacheDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'pancha-mugam-pixabay';
if (is_dir($cacheDir)) {
    foreach (glob($cacheDir . DIRECTORY_SEPARATOR . '*.json') ?: [] as $cacheFile) {
        if (is_file($cacheFile) && @unlink($cacheFile)) {
            $deletedCacheFiles++;
        }
    }
}

if (function_exists('opcache_reset')) {
    @opcache_reset();
}

setcookie('PHPSESSID', '', [
    'expires' => time() - 3600,
    'path' => '/',
    'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
    'httponly' => true,
    'samesite' => 'Lax',
]);
session_destroy();
$reloadUrl = url('index.php');
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Refreshing <?= e(APP_SHORT_NAME) ?></title>
  <style>
    :root { color-scheme: light; font-family: system-ui, sans-serif; background: #f7f1e6; color: #1a1a1a; }
    body { min-height: 100vh; display: grid; place-items: center; margin: 0; padding: 1rem; }
    main { width: min(34rem, 100%); padding: 2rem; border: 1px solid #e3d9c6; border-radius: 1rem; background: #fff; box-shadow: 0 1rem 3rem rgba(26,26,26,.12); text-align: center; }
    h1 { margin-top: 0; }
    p { color: #6e6e6e; line-height: 1.6; }
    a { display: inline-block; margin-top: .75rem; padding: .8rem 1.3rem; border-radius: 999px; background: #e8720c; color: #fff; text-decoration: none; font-weight: 700; }
  </style>
</head>
<body>
<main>
  <h1>Refreshing the website</h1>
  <p>Project browser storage and cached image data are being cleared. The latest interface will reload automatically.</p>
  <a href="<?= e($reloadUrl) ?>">Reload now</a>
</main>
<script>
(() => {
  const reloadUrl = <?= json_encode($reloadUrl, JSON_UNESCAPED_SLASHES) ?>;
  const cacheBust = Date.now().toString();

  try { localStorage.clear(); } catch (error) {}
  try { sessionStorage.clear(); } catch (error) {}

  document.cookie.split(';').forEach((cookie) => {
    const name = cookie.split('=')[0].trim();
    if (!name) return;
    document.cookie = `${name}=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/`;
    document.cookie = `${name}=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=${location.pathname}`;
  });

  const clearBrowserCaches = 'caches' in window
    ? caches.keys().then((keys) => Promise.all(keys.map((key) => caches.delete(key))))
    : Promise.resolve();
  const clearWorkers = 'serviceWorker' in navigator
    ? navigator.serviceWorker.getRegistrations().then((registrations) => Promise.all(registrations.map((registration) => registration.unregister())))
    : Promise.resolve();
  const clearDatabases = 'indexedDB' in window && indexedDB.databases
    ? indexedDB.databases().then((databases) => Promise.all(databases.map((database) => database.name && new Promise((resolve) => {
        const request = indexedDB.deleteDatabase(database.name);
        request.onsuccess = request.onerror = request.onblocked = () => resolve();
      }))))
    : Promise.resolve();

  Promise.allSettled([clearBrowserCaches, clearWorkers, clearDatabases]).finally(() => {
    window.location.replace(`${reloadUrl}?cache_reset=${cacheBust}`);
  });
})();
</script>
</body>
</html>