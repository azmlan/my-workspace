# Implementation Phases — my-workbase

> One phase per session. Do not start the next phase until all checkboxes in the current one are checked.
> Check off each task as you complete it.

---

## Phase 1 — Foundation
**Goal:** Working Laravel app, all packages installed, all migrations run, seeded admin can log in at `/dashboard`.

- [x] Install packages: `spatie/laravel-medialibrary:"^11.0"` and `spatie/laravel-settings:"^3.0"`
- [x] Run all migrations (see @docs/schema.md for exact schema)
- [x] Set up standard Laravel auth (login/logout only — no registration, no password reset)
- [x] Seed admin user from `ADMIN_EMAIL` and `ADMIN_PASSWORD` env values
- [x] Create route group: all `/dashboard/*` routes protected by `auth` middleware
- [x] Create admin layout: `resources/views/layouts/admin.blade.php` — sidebar with nav links, topbar with logout, content slot
- [x] Create a placeholder dashboard home page at `GET /dashboard` — just the layout with a "Welcome" heading
- [x] Configure `.env.example` with all required keys (see @docs/hosting-constraints.md)
- [x] Create `public/build/.gitkeep` with comment: "Run npm run build locally. Do not run npm on the server."

**Exit condition:** Visit `/login` → login works → redirects to `/dashboard` → admin layout renders → logout works.

---

## Phase 2 — Portfolio CMS (Admin) ✓
**Goal:** Admin can manage all public portfolio content from the dashboard.

### Hero & About Settings
- [x] Publish and run `spatie/laravel-settings` migrations
- [x] Create `App\Settings\HeroSettings` class — fields: `full_name`, `tagline`, `bio_short`, `github_url`, `linkedin_url`, `twitter_url`, `email_display`
- [x] Create `App\Settings\AboutSettings` class — fields: `bio_full`
- [x] `Dashboard\HeroSettingController` — `edit` (GET) and `update` (PUT) — single form page, no index
- [x] `Dashboard\AboutSettingController` — `edit` (GET) and `update` (PUT)
- [x] Hero form: handle photo upload and CV file upload via Spatie Media Library
- [x] About form: handle photo upload via Spatie Media Library
- [x] Add sidebar links: "Hero Settings", "About Settings"

### Portfolio Projects
- [x] `Dashboard\PortfolioProjectController` — full resource (index, create, store, edit, update, destroy)
- [x] Index: table with title, featured toggle, visible toggle, sort order, edit/delete actions
- [x] Form: title, description, tech_tags (comma-separated input, stored as JSON), live_url, github_url, featured checkbox, is_visible checkbox, sort_order, image upload
- [x] Delete: confirmation via Alpine.js modal before submitting delete form
- [x] Add sidebar link: "Projects"

### Services
- [x] `Dashboard\ServiceController` — full resource
- [x] Index: table with title, icon, visible toggle, sort order, edit/delete
- [x] Form: title, description, icon (text input — Heroicon name), sort_order, is_visible
- [x] Add sidebar link: "Services"

### Testimonials
- [x] `Dashboard\TestimonialController` — full resource
- [x] Index: table with client_name, rating, visible toggle, edit/delete
- [x] Form: client_name, client_role, client_company, body, rating (select 1–5), is_visible, photo upload (nullable)
- [x] Add sidebar link: "Testimonials"

**Exit condition:** Admin can create/edit/delete all portfolio content. Image uploads work. Settings save correctly.

---

## Phase 3 — Landing Page ✓
**Goal:** Polished, fully responsive public portfolio pulling all content from DB.

- [x] Single route `GET /` → `LandingController@index` — eager-loads all visible content and settings
- [x] Layout: `resources/views/layouts/landing.blade.php` — Vite assets, meta tags, smooth scroll, sticky nav
- [x] Section partials in `resources/views/landing/sections/`:
    - [x] `hero.blade.php` — full-screen, name, tagline, photo, GitHub/LinkedIn/email links, CV download button
    - [x] `about.blade.php` — bio_full, photo
    - [x] `projects.blade.php` — grid of visible PortfolioProjects ordered by sort_order, tech tags as badges
    - [x] `services.blade.php` — grid of visible Services ordered by sort_order
    - [x] `testimonials.blade.php` — visible Testimonials ordered by sort_order, show rating if present
    - [x] `contact.blade.php` — name, email, message. On submit: dispatch queued job to email `CONTACT_RECIPIENT_EMAIL`. Alpine.js for loading/success state. Flash on success/error
- [x] Hide sections entirely when no visible records exist
- [x] Fully responsive — mobile and desktop
- [x] Design: modern developer aesthetic. Distinctive font pair — not Inter, not Roboto. Commit fully to dark or light. Subtle scroll-triggered reveal animations.

**Exit condition:** All sections render from DB. Contact form sends email. Page is fully responsive.

---

## Phase 4 — Client CRM (Admin) ✓
**Goal:** Full client management inside the dashboard.

### Customers
- [x] `Dashboard\CustomerController` — full resource
- [x] Index: searchable table (name, email) with columns: name, phone, email, source, project count, created_at, actions
- [x] Show page: customer detail — displays their info, lists their client projects, invoices summary, and notes
- [x] Create/Edit form: name (required), phone (required), email (optional), source (optional), notes_general (optional)
- [x] Delete: Alpine.js confirmation modal
- [x] Add sidebar link: "Customers"

### Client Projects
- [x] `Dashboard\ClientProjectController` — full resource
- [x] Index: filterable by status, table with title, customer name, status badge, deadline (highlight red if overdue and not completed/cancelled)
- [x] Create/Edit form: customer (select), title (required), status (select — defaults to lead), type (optional), description (optional), start_date (optional), deadline (optional)
- [x] Status displayed as colored badge: lead (gray), active (blue), on_hold (yellow), completed (green), cancelled (red)
- [x] Add sidebar link: "Projects"

### Invoices
- [x] `Dashboard\InvoiceController` — accessible from customer show page or client project show page only, not a standalone nav link
- [x] Full CRUD per client project
- [x] Form: amount (required), currency (default SAR), status (select: unpaid/partial/paid), due_date (optional), notes (optional)
- [x] When status set to `paid`, automatically set `paid_at` to current timestamp
- [x] Status badges: unpaid (red), partial (yellow), paid (green)

### Notes
- [x] `Dashboard\NoteController` — create and delete only, scoped to customer
- [x] Displayed on customer show page as a chronological list
- [x] Inline create form (textarea + submit) on the customer show page

### Enums
- [x] `App\Enums\ClientProjectStatus` — backed enum with `label()` and `color()` methods
- [x] `App\Enums\InvoiceStatus` — backed enum with `label()` and `color()` methods

**Exit condition:** Full CRM flow works — create customer → add project → add invoice → add notes → change statuses.

---

## Phase 5 — Dashboard Home & Reports ✓
**Goal:** Admin opens `/dashboard` and sees a real overview, not a placeholder.

- [x] Replace placeholder dashboard home with a real stats view
- [x] Stats cards:
    - [x] Total customers
    - [x] Active client projects (status = active)
    - [x] Total unpaid invoice amount (sum where status != paid), in SAR
    - [x] Overdue projects (deadline < today AND status not in completed/cancelled)
- [x] Recent customers table — last 5 added, with project count
- [x] Client projects by status — simple bar breakdown (pure Blade/Tailwind, no JS chart library)
- [x] Generate `DEPLOY.md` in project root (content defined in @docs/hosting-constraints.md)

**Exit condition:** Dashboard home shows real data. DEPLOY.md exists in project root.
