# Cariloker – Job Portal (Laravel 11)

A Laravel-based job portal with search, filters, job details, applications with resume upload, saved jobs, and company pages. UI is built with Breeze (Blade + Tailwind) and modeled after a modern job board.

## Quick start

1. Requirements: PHP 8.2+, Composer, Node 18+
2. Install and set up

```bash
cd app
composer install
cp .env.example .env
php artisan key:generate
# Use SQLite by default
# A database file is already created at database/database.sqlite
npm install
npm run build
php artisan migrate --seed
php artisan serve
```

Open http://127.0.0.1:8000 to browse jobs.

## Test accounts

- Admin: admin@cariloker.test / password
- Employer: employer@cariloker.test / password
- Candidate: candidate@cariloker.test / password

## Features

- Find jobs: keyword, location, type, salary, experience, remote filter, sort
- Job detail: description, skills, salary, apply with resume upload
- Save/unsave jobs (requires login)
- Company pages with open roles
- Auth scaffolding via Breeze (Blade + Tailwind)
- SQLite by default for easy setup; `php artisan storage:link` enabled

## Project structure

Laravel app lives in the `app/` directory.

- Models: `App\\Models` (Job = `job_listings` table)
- Controllers: `App\\Http\\Controllers`
- Views: `resources/views/jobs`, `resources/views/companies`
- Seeders and factories populate sample data

## Notes

- To change the app name, set `APP_NAME="Cariloker"` in `.env`.
- Job table name is `job_listings` to avoid collision with Laravel's queue `jobs` table.
- Uploaded resumes are stored in `storage/app/public/resumes`. Public link is configured.

## Next steps (optional)

- Employer dashboard (post/manage jobs, view applicants)
- Email job alerts subscriptions
- Authorization policies for employer/admin roles
- Tests for search/apply flows (Pest)

