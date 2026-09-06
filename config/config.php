<?php
declare(strict_types=1);

session_start();

define('APP_NAME', 'Om Aathi Sivan Pancha Mugam Charitable Trust');
define('APP_SHORT_NAME', 'Pancha Mugam Trust');
define('APP_TAGLINE', 'Serving People. Creating Hope.');
define('PIXABAY_API_KEY', getenv('PIXABAY_API_KEY') ?: '57456446-523eed6de21754313be86a7e6');
// Resolve the project prefix for both localhost subdirectories and production root hosting.
$document_root = realpath($_SERVER['DOCUMENT_ROOT'] ?? '') ?: '';
$project_root = realpath(__DIR__ . '/..') ?: '';
$document_root_normalized = str_replace('\\', '/', rtrim($document_root, '/\\'));
$project_root_normalized = str_replace('\\', '/', rtrim($project_root, '/\\'));
$base_url = '';
if ($document_root_normalized !== '' && str_starts_with($project_root_normalized . '/', $document_root_normalized . '/')) {
    $relative_root = trim(substr($project_root_normalized, strlen($document_root_normalized)), '/');
    $base_url = $relative_root === '' ? '' : '/' . $relative_root;
}
define('BASE_URL', $base_url);

define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_NAME', getenv('DB_NAME') ?: 'u415413678_trust');
define('DB_USER', getenv('DB_USER') ?: 'u415413678_fohzo');
define('DB_PASS', getenv('DB_PASS') ?: 'Fohzo@0101');


// define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
// define('DB_NAME', getenv('DB_NAME') ?: 'u415413678_trust');
// define('DB_USER', getenv('DB_USER') ?: 'root');
// define('DB_PASS', getenv('DB_PASS') ?: '');


// Public-facing trust facts (no personal trustee names)
define('TRUST_REG_NO', 'Book-IV / 42 / 2023');
define('TRUST_REG_DATE', '16 March 2023');
define('TRUST_TYPE', 'Public Charitable Trust');
define('TRUST_ADDRESS', 'RJ Complex, Girivala Pathai, Near VAO Office, Adi Annamalai Village & Post, Thiruvannamalai Taluk & District, Tamil Nadu – 606604');

function db(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $pdo = new PDO(
            'mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8mb4',
            DB_USER, DB_PASS,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
        );
    }
    return $pdo;
}

function e(?string $value): string {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function redirect(string $url): never {
    header('Location: '.$url);
    exit;
}

function url(string $path = ''): string {
    $path = ltrim($path, '/');
    if ($path === '') {
        return BASE_URL === '' ? '/' : BASE_URL;
    }
    return (BASE_URL === '' ? '' : BASE_URL) . '/' . $path;
}

function asset(string $path): string {
    return url('assets/' . ltrim($path, '/'));
}

function is_active(string $page): string {
    $script = basename($_SERVER['SCRIPT_NAME'] ?? '');
    return $script === $page ? 'active' : '';
}
