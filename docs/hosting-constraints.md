# Hosting Constraints — name.com cPanel (Shared Hosting)

---

## PHP
- Minimum: PHP 8.2 (confirmed available)
- Set via: cPanel → MultiPHP Manager → select domain

---

## Queue Workers
No persistent workers. No Supervisor. No daemon support.

**Use `QUEUE_CONNECTION=database` in production.**

Add this cron in cPanel:
```
* * * * * /usr/local/bin/php /home/{user}/{project}/artisan queue:work --stop-when-empty --tries=3
```
`--stop-when-empty` is mandatory — without it the process hangs and cron piles up.

Rules for jobs:
- Must be idempotent (safe to run more than once)
- Must be short-running (under 1 minute)
- `failed_jobs` table is required — set `QUEUE_FAILED_DRIVER=database`

---

## Scheduler
One single cron entry for the scheduler:
```
* * * * * /usr/local/bin/php /home/{user}/{project}/artisan schedule:run >> /dev/null 2>&1
```
Do not add multiple cron entries for individual commands.

---

## Cache & Session
| Driver | Available |
|---|---|
| `file` | ✅ Use this |
| `database` | ✅ |
| `redis` | ❌ No Redis server |
| `memcached` | ❌ No server |

**Use `CACHE_DRIVER=file` and `SESSION_DRIVER=file`.**

---

## Database
- MySQL only via cPanel MySQL Databases
- No PostgreSQL
- `DB_HOST=localhost`

---

## File Storage
- Local filesystem only. No S3.
- `FILESYSTEM_DISK=public`
- Run `php artisan storage:link` after deployment
- Never hardcode paths — use `storage_path()`, `public_path()`, `asset()`

---

## Frontend Assets
**Node.js does not exist on the server.**
- Never run `npm` on the server
- Build locally: `npm run build`
- Upload `public/build/` to server manually or commit it
- Be explicit in any instructions: "Run `npm run build` locally before deploying"

---

## Document Root
- Deploy project **outside** `public_html`
- Symlink `public/` into the domain document root (Option A — preferred)

```
/home/{user}/my-workbase/        ← Laravel root
/home/{user}/public_html/        ← symlink → my-workbase/public
```

---

## SSH & Composer
- SSH is available — use it for all post-deploy commands
- Run `composer install --no-dev --optimize-autoloader` on server
- Never upload the `vendor/` folder

---

## Not Supported — Do Not Build For These
- WebSockets, Broadcasting, Pusher, Reverb
- Redis as cache or queue backend
- Laravel Horizon
- PostgreSQL
- Docker
- Node.js / npm on server
- Persistent background daemons

---

## .env.example — Required Keys
```env
APP_NAME=my-workbase
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=database
QUEUE_FAILED_DRIVER=database

FILESYSTEM_DISK=public

MAIL_MAILER=smtp
MAIL_HOST=
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=
MAIL_FROM_NAME="${APP_NAME}"

ADMIN_EMAIL=admin@example.com
ADMIN_PASSWORD=changeme

CONTACT_RECIPIENT_EMAIL=
```

---

## DEPLOY.md Content
Generate this file at `DEPLOY.md` in the project root during Phase 5:

```markdown
# Deployment — my-workbase

## First Deploy
1. Upload project files to server (outside public_html)
2. Symlink public/ into domain document root
3. Copy .env.example → .env and fill all values
4. SSH into server, navigate to project root
5. composer install --no-dev --optimize-autoloader
6. php artisan key:generate
7. php artisan migrate --force
8. php artisan db:seed
9. php artisan storage:link
10. php artisan config:cache
11. php artisan route:cache
12. php artisan view:cache
13. chmod -R 775 storage bootstrap/cache
14. Add both cPanel cron jobs (see hosting-constraints.md)

## Subsequent Deploys
1. Run npm run build locally → upload public/build/ to server
2. Upload changed files
3. php artisan migrate --force
4. php artisan config:cache && php artisan route:cache && php artisan view:cache
5. chmod -R 775 storage bootstrap/cache

## Local Before Every Deploy
npm run build   ← run this locally, then upload public/build/
```
