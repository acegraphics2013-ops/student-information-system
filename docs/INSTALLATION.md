# Local installation

These steps apply to the existing Laravel 12 SIMS checkout on Windows with
Laragon. They preserve the local database and `.env`; do not run
`migrate:fresh` or `db:wipe`.

## Requirements

- PHP 8.2 or newer with PDO SQLite enabled
- Composer
- Node.js and npm
- Laragon or another local PHP environment

## Setup

Open PowerShell:

```powershell
cd C:\laragon\www\student-information-system
composer install
npm ci
npm run build
```

For a new checkout only, create `.env` if it is absent and generate an
application key only if one is not already configured:

```powershell
if (-not (Test-Path .env)) { Copy-Item .env.example .env }
if (-not (Select-String -Path .env -Pattern '^APP_KEY=base64:' -Quiet)) { php artisan key:generate }
```

The project is configured for SQLite. Create the database file only if it does
not exist, then apply outstanding additive migrations:

```powershell
if (-not (Test-Path database\database.sqlite)) { New-Item database\database.sqlite -ItemType File }
php artisan migrate
php artisan db:seed --class=NemsuAcademicCatalogSeeder
php artisan storage:link
```

Back up an existing SQLite file before migration. The academic-catalog seeder
adds missing catalog records and stops on conflicting college abbreviations;
it does not delete or rewrite student, subject, or enrollment rows. The
user-supplied subject list is demonstration data, not a verified curriculum.
It is not seeded by default. Review local subjects before optionally adding
missing sample codes with `php artisan db:seed --class=SubjectCatalogSeeder`.
The default `php artisan db:seed` does not create demo users or seed sample
subjects.

## First administrator and server

Register through the application; public registration creates a Staff
account. Assign the Administrator role only to a trusted local account using
Tinker:

```powershell
php artisan tinker
```

```php
App\Models\User::where('email', 'your-registered-email@example.com')
    ->update(['role' => 'admin']);
```

Run the local server:

```powershell
php artisan serve --host=127.0.0.1 --port=8000
```

Open [http://127.0.0.1:8000](http://127.0.0.1:8000) and sign in. Stop the
server with `Ctrl+C`.

## API

Provision a local Sanctum token from Tinker only when needed. Keep the token
out of source control and use the request examples in [api.md](api.md). API
read access is available to Staff and Administrators; API writes require an
Administrator.

## Troubleshooting

- Missing Vite manifest: run `npm ci` and `npm run build`.
- SQLite connection failure: confirm the local DB configuration and file,
  then run `php artisan migrate`; never reset a database containing records.
- `401` from the API: provide a valid bearer token and JSON `Accept` header.
- `403` from an API write: the authenticated user must have the Administrator
  role.
- PHP namespace fatal at file start: ensure no whitespace or output precedes
  `<?php` in the affected file.
