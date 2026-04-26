# Betopia LMS Backend

Laravel backend for the Betopia LMS frontend demo. Default database is PostgreSQL.

## Folder map

- `app/Http/Controllers/Api`
  API endpoints grouped by feature:
  auth, branding, courses, assessments, live classes, compliance, certificates, billing, notifications, audit.
- `app/Models`
  Eloquent domain models and relationships, including enrollments, attendance, invoices, roles, and permissions.
- `app/Support/LmsSupport.php`
  Shared LMS business logic:
  plan matrix, fallback question bank, AI question generation, essay scoring, CSV export, payload serialization.
- `config/lms.php`
  LMS-specific configuration and frontend-compatible endpoints.
- `database/migrations`
  PostgreSQL schema for tenants, users, courses, lessons, enrollments, assessments, submissions, live classes, attendance, certificates, compliance, billing, invoices, notifications, audit, roles, and permissions.
- `database/seeders`
  Modular seeders:
  roles/permissions, tenants, users, courses, enrollments, assessments, live classes, billing, notifications, audit logs.
- `database/seeders/Support/BangladeshLmsDataset.php`
  Bangladesh-only realistic seed source for names, institutes, cities, courses, addresses, phones, and helper generators.
- `routes/api.php`
  Versioned API routes under `/api/v1/...` plus frontend-compatible teacher aliases under `/api/teacher/...`.

## PostgreSQL Setup (Default)

1. Create database `lms_db`.

If `psql` is available:

```bash
psql -U postgres -h 127.0.0.1 -p 5432 -c "CREATE DATABASE lms_db;"
```

2. Configure `.env`:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=lms_db
DB_USERNAME=postgres
DB_PASSWORD=password
```

3. Install dependencies and prepare app:

```bash
composer install
php artisan key:generate
```

4. Clear caches, migrate, and seed:

```bash
php artisan config:clear
php artisan cache:clear
php artisan migrate:fresh --seed
```

5. Run tests:

```bash
php artisan test
```

6. Start backend server:

```bash
php artisan serve --host=127.0.0.1 --port=8000
```

## Safe Database Reset

Use any one of the following:

```bash
composer db:reset
```

```powershell
powershell -ExecutionPolicy Bypass -File scripts/db-reset.ps1
```

```bash
bash scripts/db-reset.sh
```

## Demo Users

Seeded credentials:

- `admin@example.com` / `password123`
- `teacher@example.com` / `password123`
- `student@example.com` / `password123`

## Optional SQLite Fallback

If you need a local fallback, SQLite is still supported by Laravel config. You can switch only in local `.env`:

```env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

## Notes

- Default `.env` expects PostgreSQL at `127.0.0.1:5432`
- Sanctum is enabled for API token auth
- Teacher note upload is implemented with local file storage
- The AI generation and essay evaluation logic is deterministic demo logic that can later be swapped for real services
- Bangladesh-only dummy data is generated for multi-tenant institutes, users, billing, live classes, attendance, reporting, and analytics use cases
