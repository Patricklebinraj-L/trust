# Om Aathi Sivan Pancha Mugam Charitable Trust

Public website for **Om Aathi Sivan Pancha Mugam Charitable Trust**  
Production: **https://trust.fohzo.com/**

Reg. Book-IV / 42 / 2023 · 16 March 2023 · Public Charitable Trust  
Office: Thiruvannamalai, Tamil Nadu

## Brand
- Logo icon: `assets/images/brand/logo-icon.jpg`
- Full logo: `assets/images/brand/logo-full.jpg`
- Favicons: `favicon-32.png`, `favicon-64.png`

## Setup
1. Configure DB in `config/config.php` or environment variables
2. `BASE_URL` is empty for root deployment
3. `php database/migrate.php`
4. Create admin once via `/admin/create-admin.php` then delete it

## Pixabay media
- Homepage, gallery fallback media, work cards and the timed donation prompt use a server-side Pixabay image pool.
- Set `PIXABAY_API_KEY` in the server environment to override the development key in `config/config.php`.
- Responses are cached for 30 minutes in the PHP temporary directory and fall back to the last good response or local assets when Pixabay is unavailable.
- Images are randomized per session and marked as displayed, so reloads use different images until the available pool is exhausted before starting a new cycle.
- If both the API and cache fail, `assets/json/images.json` is normalized and used automatically so image slots never render empty.
- Pixabay images are labeled illustrative and must not be presented as verified Trust activity.

## Refreshing a live browser
- Open `/clear-cache.php` on the deployed domain when an older UI is still displayed.
- The utility clears this project's local/session storage, cookies, Cache Storage, service workers, IndexedDB where supported, PHP session data, Pixabay temporary cache, and OPcache when available, then reloads the homepage with a fresh cache-busting query.

## Notes
- No trustee personal names are displayed on the public site
- Impact statistics are never fabricated
- Illustrative media is labeled; verified activity media comes from CMS
