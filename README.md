# Student Information Management System (SIMS)

Laravel 12 student information system using Breeze authentication, Blade, Tailwind,
Alpine.js, Eloquent, SQLite, and a Sanctum-protected Student REST API.

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
```

Migrations update the existing schema and do not reset records. Back up the
SQLite file before applying migrations to an existing installation. Never use
`migrate:fresh` or `db:wipe` on data you want to keep.

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

- **Admin:** read and manage students, subjects, and enrollments.
- **Staff:** read students, subjects, and enrollments; management writes are
  denied by server-side middleware.

The role is enforced on web and API routes. Hiding a button in a view is not
the authorization boundary.

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

## Project and assessment documents

- [Rubric compliance audit](docs/compliance-audit.md)
- [AI engineering portfolio (evidence-backed draft)](docs/ai-engineering-portfolio.md)
- [Agent design and actual workflow](docs/ai-agent-design.md)
- [Reflection template](docs/reflection-template.md)

The handbook and rubric supplied for the assessment are not redistributed in
this repository. Screenshots, personal reflection, and deployment evidence
must be supplied by the student; none are fabricated here.
