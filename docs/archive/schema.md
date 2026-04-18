# Database Schema — my-workbase

---

## Portfolio / CMS Side

### HeroSettings — via spatie/laravel-settings
Class: `App\Settings\HeroSettings`
Fields: `full_name`, `tagline`, `bio_short`, `github_url`, `linkedin_url`, `twitter_url`, `email_display`
Media: `photo`, `cv_file` — stored via Spatie Media Library on the `HeroSettings` model (use a proxy Eloquent model or store on a dedicated `SiteMedia` model)

### AboutSettings — via spatie/laravel-settings
Class: `App\Settings\AboutSettings`
Fields: `bio_full` (long text)
Media: `photo` — stored via Spatie Media Library

### portfolio_projects
```
id
title            — required (string)
description      — required (text)
tech_tags        — required (json, array of strings)
live_url         — optional (nullable string)
github_url       — optional (nullable string)
featured         — boolean, default false
sort_order       — integer, default 0
is_visible       — boolean, default true
timestamps
```
Media: `image` via Spatie Media Library (single collection)

### services
```
id
title            — required (string)
description      — required (text)
icon             — required (string — Heroicon name e.g. "heroicon-o-code-bracket")
sort_order       — integer, default 0
is_visible       — boolean, default true
timestamps
```

### testimonials
```
id
client_name      — required (string)
client_role      — optional (nullable string)
client_company   — optional (nullable string)
body             — required (text)
rating           — optional (nullable tinyint, 1–5)
is_visible       — boolean, default true
sort_order       — integer, default 0
timestamps
```
Media: `photo` via Spatie Media Library (single collection, nullable)

---

## CRM Side

### customers
```
id
name             — required (string)
phone            — required (string)
email            — optional (nullable string)
source           — optional (nullable string)
notes_general    — optional (nullable text)
timestamps
```

### client_projects
```
id
customer_id      — required (FK → customers.id, cascadeOnDelete)
title            — required (string)
status           — required (enum, default: 'lead')
type             — optional (nullable string)
description      — optional (nullable text)
start_date       — optional (nullable date)
deadline         — optional (nullable date)
timestamps
```
Status enum values: `lead`, `active`, `on_hold`, `completed`, `cancelled`
Implement as `App\Enums\ClientProjectStatus` (PHP backed enum) with `label()` and `color()` methods.

### invoices
```
id
client_project_id — required (FK → client_projects.id, cascadeOnDelete)
amount            — required (decimal 10,2)
currency          — string, default 'SAR'
status            — required (enum: unpaid, partial, paid), default: 'unpaid'
due_date          — optional (nullable date)
paid_at           — optional (nullable timestamp) — set automatically when status → paid
notes             — optional (nullable text)
timestamps
```
Implement as `App\Enums\InvoiceStatus` with `label()` and `color()` methods.
Badge colors: unpaid → red, partial → yellow, paid → green.

### notes
```
id
customer_id      — required (FK → customers.id, cascadeOnDelete)
body             — required (text)
timestamps
```

---

## System Tables
- `jobs` — `php artisan queue:table` then migrate
- `failed_jobs` — `php artisan make:migration create_failed_jobs_table` or use built-in
- `media` — created automatically by Spatie Media Library
- `settings` — created automatically by spatie/laravel-settings
- `users` — Laravel default, used only for the seeded admin
