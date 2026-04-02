# CLAUDE.md — my-workbase

## Project
Laravel 12 app with two surfaces:
- **Public** — portfolio landing page, content from DB, no auth
- **Private** — custom admin dashboard at `/dashboard`, single seeded admin, client CRM

Full schema → @docs/schema.md
Hosting constraints → @docs/hosting-constraints.md
Implementation phases → @docs/phases.md

---

## Tech Stack
- Laravel 12, PHP 8.2+
- `spatie/laravel-medialibrary:^11.0` — file/image uploads
- `spatie/laravel-settings:^3.0` — Hero and About single-row settings
- Blade + Tailwind CSS + Alpine.js + Vite
- MySQL — `pdo_mysql` only, no PostgreSQL
- Queue: `database` driver — see hosting constraints
- No Filament. No Nova. No third-party admin panel package.

---

## Admin Panel Approach
The dashboard is a custom-built admin panel using standard Laravel + Blade + Tailwind + Alpine.js.

Structure:
- All admin routes under `/dashboard` prefix, protected by `auth` middleware
- Single admin layout: `resources/views/layouts/admin.blade.php` — sidebar + topbar
- One controller per resource: `Dashboard\PortfolioProjectController`, `Dashboard\CustomerController`, etc.
- Standard Laravel resource controllers (index, create, store, edit, update, destroy)
- Alpine.js for modals, confirmations, and inline interactivity
- No Livewire. No Filament. No external admin package.

---

## Naming — Non-Negotiable
| Thing | Model | Table | Controller |
|---|---|---|---|
| Shown on public portfolio | `PortfolioProject` | `portfolio_projects` | `Dashboard\PortfolioProjectController` |
| Work done for a client | `ClientProject` | `client_projects` | `Dashboard\ClientProjectController` |

Never use the word "project" alone as a model or variable name.

---

## Auth
- Single admin user, seeded from `.env` (`ADMIN_EMAIL`, `ADMIN_PASSWORD`)
- Standard Laravel auth — login route at `/login`, redirects to `/dashboard`
- No public registration. No password reset. No guest access to dashboard.
- Middleware `auth` on all `/dashboard/*` routes via route group

---

## Behavior Rules
- After completing each task in @docs/phases.md, check it off immediately with [x]
- After completing an entire phase, mark the phase header as done in @docs/phases.md and stop — do not start the next phase
- After each task that touches routes or models, run `php artisan route:list` and `php artisan test` before moving on
- If a next step is unclear, stop and ask — do not invent or assume

---

## Hard Rules
- No Redis, no WebSockets, no Horizon, no persistent daemons
- No `npm` on the server — build assets locally, upload `public/build/`
- No `Route::get('/migrate')` or any route that runs artisan commands
- Every model has `$fillable`. No `$guarded = []`
- All form inputs have validation. Nothing saves without it
- Use PHP 8.2+ syntax: enums, match, readonly, named arguments
- Tailwind utility classes only — no custom CSS files unless necessary
- Never hardcode paths — use `storage_path()`, `public_path()`, `asset()`
- CSRF protection on every POST/PUT/DELETE form — always `@csrf`
