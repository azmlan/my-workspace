# Laravel Shared Hosting Requirements (name.com cPanel)

> This file is for Claude Code to audit a Laravel project before deployment to shared hosting.
> Check each item against the project and report what passes, fails, or needs attention.

---

## 1. PHP Version

**Required:** PHP 8.2 or 8.3
**How to check:** Look at `composer.json` for the `require.php` field.

```json
"require": {
    "php": "^8.2"
}
```

**Action:** If set below 8.2, flag it. The developer must set PHP 8.2+ in cPanel → Select PHP Version.

---

## 2. Required PHP Extensions

The following extensions must be enabled in cPanel → Select PHP Version → Extensions tab.

| Extension   | Why it's needed                        |
|-------------|----------------------------------------|
| pdo_sqlite  | Database connection (SQLite)           |
| mbstring    | String handling                        |
| openssl     | Encryption, HTTPS, hashing             |
| tokenizer   | Laravel internals                      |
| xml         | XML parsing                            |
| ctype       | Character type checking                |
| json        | JSON encoding/decoding                 |
| bcmath      | Precise math operations                |
| curl        | HTTP client (Guzzle, APIs)             |
| fileinfo    | MIME type detection for file uploads   |
| dom         | HTML/XML DOM manipulation, dompdf      |
| gd          | Image processing, PDF image rendering  |

**How to check:** Scan `composer.json` and any package that requires extensions (e.g. dompdf needs `dom` and `gd`).

---

## 3. Environment File (.env)

**Check that the following are set correctly for production:**

```env
APP_ENV=production
APP_DEBUG=false           # MUST be false in production — leaks sensitive data if true
APP_KEY=base64:...        # Must be set — run: php artisan key:generate
APP_URL=https://yourdomain.com

DB_CONNECTION=sqlite
# No DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD needed for SQLite

CACHE_DRIVER=file         # No Redis on shared hosting
SESSION_DRIVER=file       # No Redis on shared hosting
QUEUE_CONNECTION=sync     # No queue workers on shared hosting
```

**Flag if:**
- `APP_DEBUG=true`
- `APP_KEY` is empty or default
- `QUEUE_CONNECTION` is set to `redis`, `database`, or `beanstalkd` without confirming a worker strategy
- `CACHE_DRIVER=redis` or `SESSION_DRIVER=redis`

---

## 4. File & Folder Structure

Shared hosting exposes `public_html/` as the web root. Laravel's web root is `public/`.
**Never put the full Laravel project inside `public_html/`** — this exposes `.env`, `vendor/`, and sensitive config to the internet.

**Correct server structure:**

```
/home/cpanel_user/
├── public_html/              ← web root (only public/ contents go here)
│   ├── index.php             ← modified paths (see section 5)
│   ├── .htaccess
│   └── build/                ← compiled Vite assets
└── laravel_app/              ← full Laravel project lives here (outside public_html)
    ├── app/
    ├── bootstrap/
    ├── config/
    ├── vendor/
    ├── storage/
    ├── .env
    └── ...
```

**Check:** Confirm the project is not being deployed with the full root inside `public_html/`.

---

## 5. public/index.php Path Fix

After copying `public/index.php` into `public_html/`, the relative paths break.
They must be updated to point to the Laravel root one level up.

**Required changes in `public_html/index.php`:**

```php
// Before (default Laravel):
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

// After (shared hosting fix):
require __DIR__.'/../laravel_app/vendor/autoload.php';
$app = require_once __DIR__.'/../laravel_app/bootstrap/app.php';
```

Replace `laravel_app` with whatever folder name the project is uploaded to.

---

## 6. .htaccess in public_html

A valid `.htaccess` must exist in `public_html/` to route all requests through `index.php`.
Without it, any URL beyond `/` will return 404.

**Required content:**

```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

**Check:** Confirm `.htaccess` exists in `public/` and contains a valid RewriteRule.

---

## 7. Storage Symlink

`php artisan storage:link` creates a symlink from `public/storage` → `storage/app/public`.
Because the folders are split on shared hosting, this must be done manually via SSH:

```bash
ln -s /home/cpanel_user/laravel_app/storage/app/public /home/cpanel_user/public_html/storage
```

**If SSH symlinks are restricted**, create a `symlink.php` in `public_html/`:

```php
<?php
$target = '/home/cpanel_user/laravel_app/storage/app/public';
$link   = '/home/cpanel_user/public_html/storage';
symlink($target, $link);
echo 'Symlink created.';
```

Visit `yourdomain.com/symlink.php` to run it, then delete the file immediately.

**Check:** Confirm the project uses `Storage::disk('public')` for any publicly accessible files,
and that `FILESYSTEM_DISK=public` or explicit disk references are correct.

---

## 8. File Permissions

```bash
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
```

**Flag if:** `storage/` or `bootstrap/cache/` are not writable — Laravel cannot cache or write logs.

---

## 9. Queue Configuration

**Shared hosting cannot run persistent queue workers.**
`php artisan queue:work` is not supported.

**Check `config/queue.php` and `.env`:**
- If `QUEUE_CONNECTION=sync` → fine, jobs run synchronously in the request.
- If any Listener uses `implements ShouldQueue` → flag it. It will silently fail unless using the `database` driver with a cron workaround.
- If `QUEUE_CONNECTION=redis` → flag it. Redis is not available on shared hosting.

**Workaround if queued jobs are needed:**
Use `QUEUE_CONNECTION=database` and add a cron job in cPanel:

```
* * * * * /opt/alt/php83/usr/bin/php /home/cpanel_user/laravel_app/artisan queue:work --stop-when-empty
```

This is not real-time but processes jobs every minute.

---

## 10. Cron Jobs (Laravel Scheduler)

If the app uses `Schedule` in `app/Console/Kernel.php` or `routes/console.php`,
add this cron job in cPanel → Cron Jobs:

```
* * * * * /opt/alt/php83/usr/bin/php /home/cpanel_user/laravel_app/artisan schedule:run >> /dev/null 2>&1
```

**Note:** Replace `php83` with the actual PHP version set in cPanel (e.g. `php82`).
**Check:** Confirm the PHP CLI binary path matches the version set in cPanel.

---

## 11. Assets — Vite Build

**Shared hosting has no Node.js.** All frontend assets must be compiled locally.

**Run locally before deploying:**

```bash
npm install
npm run build
```

This generates the `public/build/` folder. Upload it along with `public_html/`.

**Check:**
- Confirm `public/build/` exists and is not in `.gitignore` for deployment purposes.
- Confirm `@vite` directives in Blade templates match compiled manifest entries.
- Flag if `vite.config.js` has a dev-only server config that would break in production.

---

## 12. Composer — Run Locally, Not on Server

Shared hosting has memory limits that can cause `composer install` to crash.

**Run locally before deploying:**

```bash
composer install --optimize-autoloader --no-dev
```

Upload the entire `vendor/` folder to the server.

**Check:**
- Confirm `vendor/` is not in `.gitignore` when deploying via FTP/SFTP (it should be ignored in git but uploaded manually).
- Alternatively, if SSH is available and memory allows: `composer install --no-dev` on the server.

---

## 13. Artisan Caching (run after deploy)

Run these via SSH after each deployment for production performance:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate --force
```

**SSH connection for name.com:**

```bash
ssh cpanel_user@yourdomain.com -p 21098
```

**Note:** PHP CLI version may differ from web PHP. Verify with `php -v`.
If it returns an old version, use the full path:

```bash
/opt/alt/php83/usr/bin/php artisan config:cache
```

---

## 14. APP_DEBUG Must Be False

**Critical security check.**

If `APP_DEBUG=true` in production, Laravel exposes stack traces, environment variables,
database credentials, and internal paths to anyone who triggers an error.

**Check `.env`:**
```env
APP_DEBUG=false
```

---

## 15. What Does NOT Work on Shared Hosting

These features are unsupported. Flag any usage found in the project:

| Feature                        | Reason                                              | Alternative                          |
|-------------------------------|-----------------------------------------------------|--------------------------------------|
| `php artisan queue:work`       | No persistent processes                             | `QUEUE_CONNECTION=sync` or cron workaround |
| Redis                          | Not available                                       | Use `file` or `database` driver      |
| WebSockets / Laravel Reverb    | Requires persistent socket server                   | Not possible on shared hosting       |
| Custom Nginx/Apache config     | No root access                                      | Use `.htaccess` rules only           |
| Node.js on server              | Not available                                       | Compile assets locally               |
| `php artisan serve`            | Dev server only                                     | Apache serves the app via .htaccess  |
| Horizon                        | Requires queue worker + Redis                       | Not supported                        |

---

## 16. PDF Generation (dompdf)

If the project uses `barryvdh/laravel-dompdf`, ensure these extensions are enabled in cPanel:
- `dom`
- `mbstring`
- `gd` (for images inside PDFs)

No other configuration needed — PDF generation is synchronous and works fine on shared hosting.

---

## 17. IP Restriction Middleware (if used)

If `RestrictDashboardAccess` middleware is implemented, verify:

- Allowed IPs are stored in `.env` as `DASHBOARD_ALLOWED_IPS=ip1,ip2` (not hardcoded in the class).
- The middleware returns `abort(404)` for unauthorized IPs (not 403 — avoids revealing the route exists).
- The middleware is applied only to dashboard route groups, not the entire app.

---

## Audit Checklist Summary

| # | Item                                 | Status |
|---|--------------------------------------|--------|
| 1 | PHP version >= 8.2 in composer.json  | [ ]    |
| 2 | All required extensions listed       | [ ]    |
| 3 | .env production values correct       | [ ]    |
| 4 | File structure correct               | [ ]    |
| 5 | index.php paths updated              | [ ]    |
| 6 | .htaccess present and valid          | [ ]    |
| 7 | Storage symlink strategy defined     | [ ]    |
| 8 | Storage/cache permissions noted      | [ ]    |
| 9 | Queue driver set to sync             | [ ]    |
| 10| Cron job command noted               | [ ]    |
| 11| Vite assets compiled locally         | [ ]    |
| 12| Vendor folder ready for upload       | [ ]    |
| 13| Artisan cache commands noted         | [ ]    |
| 14| APP_DEBUG=false                      | [ ]    |
| 15| No unsupported features used         | [ ]    |
| 16| dompdf extensions verified (if used) | [ ]    |
| 17| IP middleware correct (if used)      | [ ]    |
