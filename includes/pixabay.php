<?php
declare(strict_types=1);

function pixabay_fallback_images(): array {
    static $fallback = null;
    if ($fallback !== null) {
        return $fallback;
    }

    $jsonFile = __DIR__ . '/../assets/json/images.json';
    $payload = is_file($jsonFile) ? json_decode((string)file_get_contents($jsonFile), true) : null;
    $fallback = [];

    foreach (($payload['hits'] ?? []) as $hit) {
        $url = $hit['webformatURL'] ?? $hit['previewURL'] ?? '';
        if ($url === '') {
            continue;
        }
        $fallback[] = [
            'url' => $url,
            'large' => $hit['largeImageURL'] ?? $url,
            'alt' => trim((string)($hit['tags'] ?? 'Community support'), ', '),
            'source' => $hit['pageURL'] ?? 'https://pixabay.com/',
        ];
    }

    return $fallback;
}

function pixabay_display_order(string $cacheKey, array $images): array {
    if (!$images) {
        return [];
    }

    static $displayed = [];
    if (isset($displayed[$cacheKey])) {
        return $displayed[$cacheKey];
    }

    $seen = $_SESSION['pixabay_seen'][$cacheKey] ?? [];
    $seen = array_fill_keys(array_map('strval', $seen), true);
    $available = [];

    foreach ($images as $index => $image) {
        $identity = (string)($image['url'] ?? $index);
        if (!isset($seen[$identity])) {
            $available[] = $index;
        }
    }

    if (count($available) < min(10, count($images))) {
        $seen = [];
        $available = array_keys($images);
    }

    shuffle($available);
    $ordered = [];
    foreach ($available as $index) {
        $ordered[] = $images[$index];
        if (count($ordered) >= 10) {
            break;
        }
    }

    foreach ($ordered as $image) {
        $seen[(string)$image['url']] = true;
    }
    $_SESSION['pixabay_seen'][$cacheKey] = array_keys($seen);
    return $displayed[$cacheKey] = array_merge($ordered, array_values(array_filter($images, static function (array $image) use ($seen): bool {
        return !isset($seen[(string)$image['url']]);
    })));
}

function pixabay_images(string $query = 'poverty children suffering for food', int $perPage = 20): array {
    static $memory = [];
    $query = trim($query) ?: 'poverty children suffering for food';
    $perPage = max(3, min(20, $perPage));
    $cacheKey = sha1('rotation-v2|' . strtolower($query) . '|' . $perPage);

    if (isset($memory[$cacheKey])) {
        return $memory[$cacheKey];
    }

    $cacheDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'pancha-mugam-pixabay';
    $cacheFile = $cacheDir . DIRECTORY_SEPARATOR . $cacheKey . '.json';
    $cacheTtl = 1800;
    $cached = is_file($cacheFile) ? json_decode((string)file_get_contents($cacheFile), true) : null;

    if (is_array($cached) && ($cached['expires'] ?? 0) > time() && !empty($cached['images'])) {
        return $memory[$cacheKey] = pixabay_display_order($cacheKey, $cached['images']);
    }

    $url = 'https://pixabay.com/api/?' . http_build_query([
        'key' => PIXABAY_API_KEY,
        'q' => $query,
        'image_type' => 'photo',
        'per_page' => $perPage,
        'safesearch' => 'true',
        'order' => 'popular',
    ]);
    $context = stream_context_create(['http' => [
        'method' => 'GET',
        'timeout' => 4,
        'ignore_errors' => true,
        'header' => "Accept: application/json\r\nUser-Agent: PanchaMugamTrust/1.0\r\n",
    ]]);
    $response = @file_get_contents($url, false, $context);
    $payload = is_string($response) ? json_decode($response, true) : null;
    $images = [];

    foreach (($payload['hits'] ?? []) as $hit) {
        if (!empty($hit['webformatURL'])) {
            $images[] = [
                'url' => $hit['webformatURL'],
                'large' => $hit['largeImageURL'] ?? $hit['webformatURL'],
                'alt' => trim((string)($hit['tags'] ?? 'Community support'), ', '),
                'source' => $hit['pageURL'] ?? 'https://pixabay.com/',
            ];
        }
    }

    if (!$images && is_array($cached) && !empty($cached['images'])) {
        return $memory[$cacheKey] = pixabay_display_order($cacheKey, $cached['images']);
    }

    if ($images) {
        if (!is_dir($cacheDir)) {
            @mkdir($cacheDir, 0775, true);
        }
        @file_put_contents($cacheFile, json_encode([
            'expires' => time() + $cacheTtl,
            'images' => $images,
        ], JSON_UNESCAPED_SLASHES), LOCK_EX);
    }

    $images = $images ?: pixabay_fallback_images();
    return $memory[$cacheKey] = pixabay_display_order($cacheKey, $images);
}

function pixabay_image(int $slot = 0, string $query = 'poverty children suffering for food'): ?array {
    $images = pixabay_images($query);
    if (!$images) {
        return null;
    }
    return $images[$slot % count($images)];
}