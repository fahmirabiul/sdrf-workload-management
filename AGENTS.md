# AGENTS.md

## What is this

SDRF (Software Development Request Form) — a Laravel 10 app for managing software request lifecycles and programmer workload capacity at a university IT department. The core domain logic lives in the controllers (no service layer).

## Quick commands

```bash
# Dev server
php artisan serve

# Build frontend assets
npm run dev        # Vite dev server with HMR
npm run build      # Production build

# Database
php artisan migrate --seed    # Full reset with seed data
php artisan migrate:fresh --seed  # Clean slate

# Tests
php artisan test              # Run all (Unit + Feature)
php artisan test --testsuite=Unit
php artisan test --testsuite=Feature
```

No linting, formatting, or static analysis commands are configured. No CI/CD pipeline exists.

## Stack

- **Backend**: Laravel 10, PHP 8.1+, Sanctum (installed but not actively used for API auth)
- **Database**: MySQL 8 via Docker (`docker-compose.yml`), exposed on **port 3307** (not 3306)
- **Frontend**: Tailwind CSS 3, Alpine.js, Vite 5. Blade templates with Breeze components.
- **Auth**: Laravel Breeze. Login uses `username` field (not email). Seed password for all users: `password123`.

## Database setup gotcha

The Docker volume `sdrf_db_data_sdrf` is declared `external: true`. You must create it manually before first `docker-compose up -d`:

```bash
docker volume create sdrf_db_data_sdrf
docker-compose up -d
```

## User roles and access

Defined as enum in migration (`users.role`): `user`, `kepala_tik`, `programmer`, `infra`.

- **kepala_tik**: Sees all requests/projects, approves requests, allocates schedules, triggers conflict protocol
- **programmer**: Sees only own projects, updates project status (start coding, submit to UAT)
- **user**: Creates software requests, reviews UAT (approve/reject with feedback), rates completed projects

## Core domain concepts

### T-Shirt size to story points

| Size | Days | Points |
|------|------|--------|
| S | 1–3 | 2 |
| M | 7–14 | 5 |
| L | 21–28 | 10 |
| XL | 28+ | 20 |

### Capacity rules

- Max 20 story points per programmer per month
- Capacity is calculated **proportionally** based on overlapping days within a month (`DevelopmentProject::calculatePointsForMonth()`)
- Projects with `is_active_load = true` count toward current capacity

### Conflict management protocol (urgent requests)

When approving a request would push a programmer over 20 pts/month:
1. The existing active project gets `CLOSE_SUSPENDED` status
2. Its `end_date` is cut to the new project's start date
3. Remaining work is **cloned** as a new `WAITING` project with `[SISA SUSPEND: X Pts]` prefix in `phase_title`
4. The cloned project bypasses T-Shirt duration validation (flexible scheduling)
5. All mutations wrapped in `DB::transaction`

### Request lifecycle

`submitted` → `analysis_scheduled` → `approved` (creates `DevelopmentProject`) → project goes through: `waiting` → `in_development` → `uat_testing` → `ready_for_production` → `production` → `closed`

### Enums

Status enums are PHP 8.1 backed enums with `label()` and `colorClass()` methods:
- `App\Enums\RequestStatus` — request ticket states (only `label()`, no `colorClass()`)
- `App\Enums\ProjectStatus` — development project states (has both `label()` and `colorClass()`)

Both are cast in their respective models. Use `::from()` to convert request input.

## Architecture notes

- **No service layer**: All business logic (conflict protocol, capacity calculation, UAT review) lives directly in controllers (`SoftwareRequestController`, `DevelopmentProjectController`)
- **Audit trail**: Every status change, schedule allocation, and conflict intervention is logged to `project_history_logs` with `action_type`, `old_value`, `new_value`, and `reason`
- **Ticket numbers**: Auto-generated as `REQ-YYYY-XXX` (sequential per year)
- **File uploads**: Stored in `storage/app/public/attachments`, served via `viewFile()` controller method
- **Old/dead code**: `DevelopmentProjectControllerOld` and `updateStatusOld()`/`review_old()` methods exist — ignore them, they are superseded

## Views structure

```
resources/views/
├── auth/           # Breeze auth views (login, register, etc.)
├── components/     # Reusable Blade components (buttons, inputs, modals)
├── dashboard/      # Dashboard partials
├── layouts/        # app.blade.php (main), guest.blade.php, navigation.blade.php
├── profile/        # User profile edit
├── projects/       # index, show (development project management)
├── requests/       # index, create, show (SDRF ticket management)
├── dashboard.blade.php
└── welcome.blade.php
```

Design tokens and component styling follow `DESIGN.md` (Apple-inspired design system with Action Blue `#0066cc` as single accent). Use inline `style` attributes for font-family fallbacks since SF Pro is not available via CDN.

## Coding conventions

- 4-space indentation, LF line endings (`.editorconfig`)
- Controllers use `compact()` to pass data to views
- Form validation errors are passed via `withErrors()` and displayed with `$errors->get()` or `@error` directive
- Flash messages via `->with('success', '...')` for user feedback after redirects
- Route prefixes: `/requests`, `/projects`, `/metrics` — all under `auth` middleware
- Views reference Tailwind utility classes directly; no custom CSS framework
