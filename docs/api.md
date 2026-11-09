# Student REST API

The API uses the existing `students` table and JSON resources. Requests are
authenticated with Laravel Sanctum personal access tokens; staff can read, and
administrators can read and write.

## Authentication

Create a token for an administrator from local Tinker after assigning the
account its admin role:

```powershell
php artisan tinker
```

```php
$admin = App\Models\User::where('email', 'admin@example.com')->firstOrFail();
$token = $admin->createToken('local-sims-client')->plainTextToken;
```

Copy the token when it is returned. Sanctum stores only a hash, so the plain
token cannot be retrieved later. Do not commit or share tokens. Send it as:

```http
Authorization: Bearer <token>
Accept: application/json
```

Tokens inherit the account's role-based permissions. Revoke a token by deleting
it in Tinker:

```php
$admin->tokens()->where('name', 'local-sims-client')->delete();
```

## Endpoints

| Method | Path | Access | Success |
|---|---|---|---|
| `GET` | `/api/students` | Authenticated staff or admin | `200`, paginated JSON |
| `GET` | `/api/students/{id}` | Authenticated staff or admin | `200`, one student |
| `POST` | `/api/students` | Admin | `201`, created student |
| `PUT` / `PATCH` | `/api/students/{id}` | Admin | `200`, updated student |
| `DELETE` | `/api/students/{id}` | Admin | `204`, empty body |

The list is ordered by last name and first name and paginated at 10 students
per page. Use `?page=2` for subsequent pages.

### Student fields

The create and full-update payload requires all of these fields:

```json
{
  "student_number": "SIMS-2001",
  "first_name": "Jordan",
  "last_name": "Lee",
  "email": "jordan.lee@example.com",
  "course": "Information Technology",
  "year_level": 1
}
```

`student_number` is unique and limited to 30 characters. Names are required
strings up to 100 characters; email is required, valid, unique, and up to 255
characters; course is required and up to 150 characters; `year_level` must be
an integer from 1 through 6. A partial `PATCH` may include only fields being
changed, but each supplied field must be valid.

### Response envelope

A successful single-student response has a `data` object:

```json
{
  "data": {
    "id": 1,
    "student_number": "SIMS-2001",
    "first_name": "Jordan",
    "last_name": "Lee",
    "email": "jordan.lee@example.com",
    "course": "Information Technology",
    "year_level": 1
  }
}
```

The collection response uses Laravel's resource pagination shape with `data`,
`links`, and `meta`.

## Errors

| Status | Meaning |
|---|---|
| `401` | Missing or invalid bearer token |
| `403` | Authenticated staff attempted an admin-only write |
| `404` | Student ID does not exist |
| `422` | Validation failed; the JSON body includes `message` and field `errors` |

Validation errors and unknown students are returned as JSON by Laravel's API
exception handling.

## Schema and migration

Sanctum adds a `personal_access_tokens` table through an additive migration.
Apply it with `php artisan migrate`; it does not recreate or delete student,
subject, enrollment, or user records.
