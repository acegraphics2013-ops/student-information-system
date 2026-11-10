# Application architecture

SIMS is a Laravel 12 server-rendered application using Breeze authentication,
Blade, Tailwind, Alpine.js, Eloquent, SQLite locally, and Sanctum for the
Student JSON API.

## Request flow

1. Web routes in `routes/web.php` apply authentication and role middleware
   before controllers run. Breeze routes in `routes/auth.php` handle
   registration and session authentication.
2. Controllers coordinate the request with Eloquent models and return Blade
   views or redirects. Student request validation is shared through
   `StudentDataRequest`; the API serializes records through
   `StudentResource`.
3. Blade templates provide student, subject, enrollment, catalog, dashboard,
   and account-management screens. Breeze layouts and components provide
   shared navigation and authentication UI.
4. `routes/api.php` exposes the Student resource behind
   `auth:sanctum`. Role middleware restricts API writes to Administrators.

## Main components

| Area | Responsibility |
|---|---|
| `app/Http/Controllers` | Web/API workflows and presentation responses |
| `app/Http/Requests` | Shared validation, including Student API/web rules |
| `app/Http/Resources` | Stable JSON representation of Students |
| `app/Http/Middleware/EnsureRole.php` | Server-side role authorization |
| `app/Models` | Eloquent records and relationships |
| `routes` | Web, auth, and REST endpoint definitions |
| `resources/views` | Blade pages and reusable UI components |
| `database/migrations` | Additive schema history |
| `database/seeders` | Explicit academic catalog and optional demo subject data |
| `tests/Feature` | HTTP, authorization, validation, and persistence checks |

## Design boundaries

- The academic catalog hierarchy is College → Department → Program.
- `students.course` remains a string for compatibility with existing records;
  new student data is checked against the installed degree-program catalog.
- Degree programs are not subjects and do not create enrollments.
- Enrollments connect Students and Subjects; unit-cap checks are enforced by
  application validation.
- The subject catalog seeder contains demonstration data supplied during
  development and is not an authoritative or automatically seeded curriculum.
- There is no separate audit-event log. Dashboard “recent” lists use record
  creation timestamps and must not be interpreted as a history of actions.
