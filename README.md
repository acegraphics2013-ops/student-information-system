# Student Information Management System (SIMS)

Laravel 12 student information system using Breeze authentication, Blade, Tailwind,
Alpine.js, Eloquent, SQLite, and a Sanctum-protected Student REST API.

## Features

- Breeze registration, login, logout, profile, password, and display-photo flows
- Official NEMSU emblem displayed in the login screen, dashboard, sidebar, and browser tab
- Role-based admin writes and staff read access
- Administrator-only system dashboard with database-backed user, student,
  subject, enrollment, and academic catalog statistics
- Administrator-only account directory with name/email search, role filtering,
  account details, and protected Staff/Administrator role management
- Student, subject, and enrollment CRUD with server-side validation
- Dashboard counts sourced from the current database
- NEMSU Tandag academic catalog for School Year 2026–2027 (7 colleges,
  13 departments, and 22 degree programs), with searchable staff/admin access
- Dependent College → Department → Degree Program student selectors; legacy
  course values remain editable without being silently rewritten
- Student REST API with bearer-token authentication and JSON validation errors
- PHPUnit feature tests using in-memory SQLite

## Requirements

- PHP 8.2 or newer with the SQLite PDO extension enabled
- Composer
- Node.js and npm
- Laragon on Windows, or another PHP-compatible local environment

## Run locally with Laragon

Open PowerShell in the project directory:

```powershell
cd C:\laragon\www\student-information-system
```

Install dependencies and build the frontend:

```powershell
composer install
npm ci
npm run build
```

For a new checkout only, copy `.env.example` to `.env` and generate an
application key. Do not replace an existing `.env` or application key:

```powershell
if (-not (Test-Path .env)) { Copy-Item .env.example .env }
if (-not (Select-String -Path .env -Pattern '^APP_KEY=base64:' -Quiet)) { php artisan key:generate }
```

The application is configured for SQLite. Create the database file only if it
does not already exist, then apply outstanding migrations:

```powershell
if (-not (Test-Path database\database.sqlite)) { New-Item database\database.sqlite -ItemType File }
php artisan migrate
php artisan db:seed --class=NemsuAcademicCatalogSeeder
php artisan storage:link
```

Migrations update the existing schema and do not reset records. Back up the
SQLite file before applying migrations to an existing installation. Never use
`migrate:fresh` or `db:wipe` on data you want to keep.

The storage link allows uploaded public profile photos to be displayed. Run
`php artisan storage:link` once per local checkout if `public\storage` does not
already exist.

The academic catalog seeder is safe to rerun: it adds missing catalog rows
without deleting records. It will stop with an explicit error if a catalog
abbreviation is already assigned to a different college name. The catalog is
separate from `subjects`; student course/program values do not create or alter
enrollments.

The supplied subject catalog is demonstration data, not an independently
verified NEMSU curriculum. It is not seeded by default. To explicitly add
missing sample subjects to a local database, run:

```powershell
php artisan db:seed --class=SubjectCatalogSeeder
```

This seeder inserts missing codes only and never overwrites an existing
subject's name or unit count. Review local subject rows before running it.
`php artisan db:seed` does not create demonstration users or seed sample
subjects.

Start the local server:

```powershell
php artisan serve --host=127.0.0.1 --port=8000
```

Open [http://127.0.0.1:8000](http://127.0.0.1:8000), register, and sign in.
New public registrations receive the `staff` role. Promote a trusted account
to administrator explicitly in local Tinker when needed:

```powershell
php artisan tinker
```

```php
App\Models\User::where('email', 'admin@example.com')->update(['role' => 'admin']);
```

Use the email address of the account you registered. Stop the local server with
`Ctrl+C`.

## Roles

- **Administrator:** manage users' Staff/Administrator roles, and read/manage
  students, subjects, and enrollments. The Administration dashboard and user
  management routes are protected by server-side role middleware.
- **Staff:** read students, subjects, and enrollments; may create and edit
  student records and manage enrollments in the web application. Subject
  catalog mutations and student deletion remain administrator-only. Through
  the Student REST API, Staff can only read; all API writes are
  Administrator-only.
- Each user can update their profile information and upload/remove a JPG, PNG,
  or WebP display photo up to 2 MB. Photos are stored on Laravel's public disk.

Public registration assigns Staff regardless of submitted role fields. An
administrator cannot change their own role, and role updates are validated
server-side so manually crafted requests cannot bypass the UI. Staff may add
and edit students, and add, update, or unload enrollments in the web
application; API writes require an Administrator. A transaction protects the
last administrator from demotion or deletion through User Management or
profile deletion. An Administrator cannot delete their own account through
User Management; profile deletion is allowed only if another Administrator
remains. User-management pages expose no password hashes, reset credentials,
sessions, or access tokens.

The Administration dashboard links to the existing Student, Subject,
Enrollment, User, and Academic Programs routes rather than duplicating CRUD.
Recent panels report records ordered by their creation timestamps; they are
not an audit trail. The student program report matches stored course names to
one catalog program exactly and flags unmatched legacy values rather than
misclassifying them.

## Student API

All endpoints require a Sanctum personal access token. Read [docs/api.md](docs/api.md) for endpoints, response shapes, status codes, and local token provisioning.

## Tests and checks

```powershell
php artisan test
composer validate --no-check-publish
npm run build
```

The test configuration uses in-memory SQLite. Running tests does not reset or
modify the local application database.

## Troubleshooting

- **`APP_KEY` is missing:** create `.env` from `.env.example` if it does not
  exist, then run `php artisan key:generate`. Do not replace an existing key
  for a database with encrypted data.
- **Vite manifest is missing:** run `npm ci` and `npm run build`, or run
  `npm run dev` in a second terminal while developing.
- **SQLite connection or missing database:** confirm `DB_CONNECTION=sqlite`
  in `.env`, ensure `database\database.sqlite` exists, and run
  `php artisan migrate`. Do not use a destructive fresh migration on existing
  data.
- **API responds `401`:** use a valid Sanctum personal access token and send
  `Accept: application/json`.
- **API responds `403`:** the token belongs to a staff user; only admins can
  perform API writes.
- **A PHP namespace error occurs at file start:** check that no blank lines,
  whitespace, or output precede `<?php` in the affected PHP file.

## Screenshots

Twenty genuine screenshots are present in `screenshots/`. They show VS Code,
terminal, Git, setup, route, test, and build activity. They do not show a
running SIMS page or an API-client exchange, so they are not evidence of those
workflows. See the [screenshot index](docs/evidence/SCREENSHOT_INDEX.md) for
the contents, dates, and recommended use of every image. Capture current
application and API screens separately if required by the instructor.

## Project structure

| Location | Responsibility |
|---|---|
| `app\Models` | Eloquent models and relationships |
| `app\Http\Controllers` | Web and API request coordination |
| `app\Http\Requests` | Shared student validation |
| `app\Http\Resources` | JSON response representation |
| `routes\web.php`, `routes\api.php` | Web and REST route definitions |
| `resources\views` | Blade presentation |
| `database\migrations` | Schema and migration history |
| `tests\Feature` | End-to-end feature and authorization tests |
| `docs` | API, rubric, AI workflow, and reflection source documents |
| `database\export` | Schema-only SQLite SQL export; no local rows |

## Project and assessment documents

- [Rubric compliance audit](docs/FINAL_RUBRIC_AUDIT.md)
- [Local installation guide](docs/INSTALLATION.md)
- [Application architecture](docs/ARCHITECTURE.md)
- [Database schema](docs/DATABASE_SCHEMA.md)
- [Role permissions](docs/ROLE_PERMISSIONS.md)
- [Testing and verification record](docs/TESTING_AND_VERIFICATION.md)
- [Git and AI reflection](docs/GIT_AND_AI_REFLECTION.md)
- [AI engineering portfolio source](docs/AI_PORTFOLIO.md)
- [Agent design source](docs/AGENT_DESIGN.md)
- [Personal reflection draft](docs/REFLECTION.md)
- [Screenshot evidence index](docs/evidence/SCREENSHOT_INDEX.md)
- [Final submission status](docs/FINAL_SUBMISSION_STATUS.md)
- [Final submission checklist](docs/submission-checklist.md)
- [Oral-defense notes](docs/oral-defense.md)
- [OpenAPI specification](docs/openapi.yaml)

Draft PDF exports are stored in `submission/` as `AI_PORTFOLIO.pdf`,
`AGENT_DESIGN.pdf`, and `REFLECTION.pdf`. The AI Portfolio and Reflection
remain drafts because screenshot evidence, student details, and first-person
answers are incomplete. Do not submit these drafts as final documents.

## Git development history

The repository history records the incremental development of authentication,
Student, Subject, Enrollment, API, catalog, test, and documentation work. At
the November 10, 2026 verification point, `git rev-list --count HEAD` reported
56 commits. The source-only submission ZIP excludes `.git/`; use the repository
history for commit-level review.

## Academic disclaimer

This website is an unofficial student project developed solely for academic purposes and project submission. It is not an official website of Northeastern Mindanao State University (NEMSU) – Tandag Campus and is not affiliated with or endorsed by the university.
