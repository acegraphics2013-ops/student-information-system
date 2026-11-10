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

The catalog seeder is safe to rerun: it adds missing catalog rows without
deleting records. It will stop with an explicit error if a catalog abbreviation
is already assigned to a different college name. The catalog is separate from
`subjects`; student course/program values do not create or alter enrollments.

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
- **Staff:** read students, subjects, and enrollments; management writes are
  allowed for student records and enrollments; subject-catalog changes and
  student deletion remain administrator-only.
- Each user can update their profile information and upload/remove a JPG, PNG,
  or WebP display photo up to 2 MB. Photos are stored on Laravel's public disk.

Public registration assigns Staff regardless of submitted role fields. An
administrator cannot change their own role, and role updates are validated
server-side so manually crafted requests cannot bypass the UI. Staff may add
and edit students, and add, update, or unload enrollments. A transaction
prevents demoting the last administrator. User-management pages expose no
password hashes, reset credentials, sessions, or access tokens. Administrator
account deletion/suspension is not provided; it would need a separate policy
and approval. Existing self-service Breeze profile/account features remain
unchanged.

The Administration dashboard links to the existing Student, Subject,
Enrollment, User, and Academic Programs routes rather than duplicating CRUD.
Recent panels report records ordered by their creation timestamps; they are
not an audit trail. The student program report matches stored course names to
one catalog program exactly and flags unmatched legacy values rather than
misclassifying them.

## Student API

All endpoints require a Sanctum personal access token in the
`Authorization: Bearer <token>` header. Read [docs/api.md](docs/api.md) for
endpoints, response shapes, status codes, and local token provisioning.

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

The assessment requires genuine screenshots of the running application.
Capture the dashboard, student/subject/enrollment workflows, and API requests
from the local environment, then place the real images under
`docs\screenshots\` and link them here. No sample or fabricated screenshots
are included.

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

- [Rubric compliance audit](docs/compliance-audit.md)
- [AI engineering portfolio / AI_PORTFOLIO Markdown source](docs/ai-engineering-portfolio.md)
- [Agent design / AGENT_DESIGN Markdown source](docs/ai-agent-design.md)
- [Reflection / REFLECTION evidence-based draft](docs/reflection-template.md)
- [Git and AI reflection](docs/git-ai-reflection.md)
- [Final submission checklist](docs/submission-checklist.md)
- [Oral-defense notes](docs/oral-defense.md)
- [OpenAPI specification](docs/openapi.yaml)

The handbook and rubric supplied for the assessment are not redistributed in
this repository. Screenshots, personal reflection, and deployment evidence
must be supplied by the student; none are fabricated here.
