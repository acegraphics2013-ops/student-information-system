# Database schema

This describes the schema represented by Laravel migrations. The local
database is SQLite in the verified development environment. The
schema-only export in `database/export/schema-only.sql` contains DDL only;
it is not a data backup.

## Core tables

| Table | Important columns and constraints |
|---|---|
| `users` | `name`, unique `email`, password hash, optional verification timestamp, remember token; role and optional profile-photo path are added by later migrations |
| `students` | Unique `student_number` and `email`; `first_name`, `last_name`, `course`, unsigned tiny-integer `year_level`, timestamps |
| `subjects` | Unique `subject_code`, `subject_name`, unsigned tiny-integer `units`, timestamps |
| `enrollments` | `student_id`, `subject_id`, default `status=enrolled`, timestamps; unique student/subject pair |
| `personal_access_tokens` | Sanctum token records; tokens are stored hashed by Sanctum |
| `sessions`, `password_reset_tokens` | Laravel session and password-reset support |

## Relationships and delete behavior

- Student has many Enrollments.
- Subject has many Enrollments.
- Enrollment belongs to one Student and one Subject.
- Enrollment foreign keys cascade on Student or Subject deletion; its
  student/subject unique constraint prevents duplicate pairs.
- College has many Departments; Department belongs to a College and has many
  Programs; Program belongs to a Department. Catalog foreign keys cascade on
  parent deletion. Names are unique within their parent; College names and
  abbreviations are unique.

## Application-level constraints

- Student year level is validated from 1 through 5.
- Enrollment creation/update validates the selected Student and Subject and
  enforces a maximum total load of 10 units. This unit total is not a database
  check constraint.
- Subject unit range and enrollment status are validated by the application;
  inspect current form/controller validation for the exact accepted values.
- Student course/program selection is catalog-validated for new values while
  unchanged legacy course strings can be retained during update.

## Migrations and data preservation

Apply pending migrations with `php artisan migrate` after backing up an
existing database. Migrations are not a substitute for a backup. Never use
`migrate:fresh` or `db:wipe` when local data must be retained. Use
`php artisan migrate:status` to inspect applied migrations.

The catalog seeder is repeatable and inserts missing catalog rows. The
optional `SubjectCatalogSeeder` uses first-or-create semantics and will not
overwrite an existing subject code. `DatabaseSeeder` does not automatically
create a demo user or seed the sample subject catalog.
