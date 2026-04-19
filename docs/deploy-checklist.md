# Shared Hosting Deployment Plan — name.com cPanel

Deploy steps in this exact order. Each step depends on the previous.

---

## Phase 1 — Prepare Locally (before touching the server)

### 1.1 Build frontend assets

```bash
npm install
npm run build
```

Verify `public/build/` exists and contains `manifest.json`.

### 1.2 Install production Composer dependencies

```bash
composer install --optimize-autoloader --no-dev
```

This strips dev packages (`phpunit`, `faker`, `pint`, `pail`, etc.) from `vendor/` and optimises the autoloader for production.

> **Local dev warning:** this command modifies your local `vendor/`. After uploading `vendor/` to the server in step 2.2, immediately run this to restore dev dependencies:
> ```bash
> composer install
> ```
> Until you do, `php artisan test`, `pail`, and `pint` will not work locally.

### 1.3 Fill in `.env.production`

The project keeps two env files locally — both are gitignored:

| File | Purpose |
|------|---------|
| `.env` | Local dev — SQLite, debug on, localhost |
| `.env.production` | Production — SQLite, debug off, real domain |

Open `.env.production` and fill in the blanks:

```
APP_URL=https://yourdomain.com
MAIL_PASSWORD=your_mail_password
ADMIN_EMAIL=your@email.com
ADMIN_PASSWORD=strong_admin_password
CONTACT_RECIPIENT_EMAIL=your@email.com
```

No database credentials needed — SQLite is a single file, no server required.

Leave `APP_KEY` blank — it gets generated on the server in step 3.2.

**Do not upload this file as `.env` yet — that happens in step 3.1.**

---

## Phase 2 — Upload Files to Server

### 2.1 Create the directory structure in cPanel File Manager

```
/home/cpanel_user/
├── public_html/        ← already exists (web root)
└── laravel_app/        ← create this folder
```

### 2.2 Upload the Laravel project to `laravel_app/`

Upload everything **except**:
- `.git/`
- `node_modules/`
- `.env` (your local dev env — the production one goes up in step 3.1)

Upload **including** (these are gitignored but needed on server):
- `vendor/`  ← from step 1.2
- `public/build/`  ← from step 1.1 (you'll copy it to public_html in 2.3)

### 2.3 Copy public assets into `public_html/`

From `laravel_app/public/`, copy these into `public_html/`:
- `index.php`
- `.htaccess`
- `build/` folder (entire directory)
- `favicon.ico` (if present)

Do **not** copy the rest of `public/` — `storage/` will be a symlink (step 4.2).

### 2.4 Fix `public_html/index.php` paths

Open `public_html/index.php` and update the two require lines:

```php
// Before (default Laravel):
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

// After (shared hosting fix):
require __DIR__.'/../laravel_app/vendor/autoload.php';
$app = require_once __DIR__.'/../laravel_app/bootstrap/app.php';
```

Also fix the maintenance mode check line:
```php
// Before:
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {

// After:
if (file_exists($maintenance = __DIR__.'/../laravel_app/storage/framework/maintenance.php')) {
```

---

## Phase 3 — Configure the Environment (via SSH)

Connect via SSH:
```bash
ssh cpanel_user@yourdomain.com -p 21098
cd ~/laravel_app
```

### 3.1 Upload and rename the production `.env`

Upload your `.env.production` file to `laravel_app/` then rename it:
```bash
mv .env.production .env
```

### 3.2 Generate the app key

```bash
/opt/alt/php82/usr/bin/php artisan key:generate
```

Confirm `APP_KEY` is now filled in `.env`.

### 3.3 Set file permissions

```bash
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
```

---

## Phase 4 — Database & Storage

### 4.1 Create the SQLite database file

SQLite needs no server setup — just create an empty file and make it writable:

```bash
touch /home/cpanel_user/laravel_app/database/database.sqlite
chmod 664 database/database.sqlite
```

### 4.2 Run migrations

```bash
/opt/alt/php82/usr/bin/php artisan migrate --force
```

### 4.3 Seed the admin user

Make sure `ADMIN_EMAIL` and `ADMIN_PASSWORD` are set in `.env` first — the seeder will throw if they are missing.

```bash
/opt/alt/php82/usr/bin/php artisan db:seed
```

The seeder uses `updateOrCreate`, so running it again is safe — it updates the existing user rather than creating a duplicate. Use this whenever you change the admin password: update `.env`, then re-run the seeder.

### 4.4 Create the storage symlink

```bash
ln -s /home/cpanel_user/laravel_app/storage/app/public /home/cpanel_user/public_html/storage
```

Verify it works by visiting `https://yourdomain.com/storage` — it should not 404.

If SSH symlinks are blocked, create `public_html/symlink.php`:
```php
<?php
$target = '/home/cpanel_user/laravel_app/storage/app/public';
$link   = '/home/cpanel_user/public_html/storage';
symlink($target, $link);
echo 'Done.';
```
Visit `https://yourdomain.com/symlink.php`, confirm "Done.", then **delete the file immediately**.

### 4.5 Back up the database

The entire database is a single file. Download it via FTP/SFTP to keep a local backup:

```
laravel_app/database/database.sqlite
```

Do this before and after any significant change on the server.

---

## Phase 5 — Queue Worker (cron workaround)

The app dispatches `SendContactFormEmail` as a queued job (`QUEUE_CONNECTION=database`). Without a worker the job will sit in the `jobs` table and never send. Add a cron job in cPanel → Cron Jobs:

```
* * * * * /opt/alt/php82/usr/bin/php /home/cpanel_user/laravel_app/artisan queue:work --stop-when-empty >> /dev/null 2>&1
```

This runs every minute, processes any pending jobs, and exits. Emails will send within ~1 minute of form submission.

**Note:** Replace `php82` with whatever version is set in cPanel → Select PHP Version.

---

## Phase 6 — Optimise for Production

Run these after every deployment:

```bash
/opt/alt/php82/usr/bin/php artisan config:cache
/opt/alt/php82/usr/bin/php artisan route:cache
/opt/alt/php82/usr/bin/php artisan view:cache
```

---

## Phase 7 — Verify

Open the site and check each of these manually:

- [ ] `https://yourdomain.com` — public portfolio loads
- [ ] `https://yourdomain.com/login` — login page loads
- [ ] Login with admin credentials → redirects to `/dashboard`
- [ ] Dashboard index loads without errors
- [ ] Upload a file (e.g. client project file) → verify it stores and downloads
- [ ] Upload a cancellation document → verify `Storage::disk('public')` file is accessible
- [ ] Generate an invoice PDF → verify mPDF renders correctly
- [ ] Submit the contact form → verify email arrives within ~1 minute
- [ ] Check `storage/logs/laravel.log` for any errors

---

## PHP Extensions to Enable in cPanel

cPanel → Select PHP Version → Extensions tab. Enable all of these:

| Extension | Required by |
|-----------|-------------|
| `pdo_sqlite` | Database (SQLite) |
| `mbstring` | Laravel core, mPDF |
| `openssl` | Encryption, mail |
| `tokenizer` | Laravel internals |
| `xml` | Laravel internals |
| `ctype` | Laravel internals |
| `json` | Laravel internals |
| `bcmath` | Precise math |
| `curl` | HTTP client |
| `fileinfo` | File uploads (Spatie Media Library) |
| `dom` | mPDF (invoice PDFs) |
| `gd` | mPDF image rendering in PDFs |

---

## Re-deploy Checklist (subsequent deploys)

1. `npm run build` locally
2. `composer install --optimize-autoloader --no-dev` locally
3. **Back up** `database/database.sqlite` before uploading anything
4. Upload changed files to `laravel_app/` (overwrite)
5. Copy updated `public/build/` to `public_html/build/`
6. `composer install` locally — restores dev dependencies after step 2
7. Via SSH: `php artisan migrate --force`
8. Via SSH: `php artisan config:cache && php artisan route:cache && php artisan view:cache`
