# Roles and permissions

Authorization is enforced by Laravel middleware and controller validation;
navigation visibility is not the security boundary.

| Capability | Guest | Staff | Administrator |
|---|---:|---:|---:|
| Register/sign in and manage own profile | No | Yes | Yes |
| Browse Students, Subjects, Enrollments, and academic programs | No | Yes | Yes |
| Create/edit Students in the web application | No | Yes | Yes |
| Delete Students | No | No | Yes |
| Create/edit/delete Subjects | No | No | Yes |
| Create/update/delete Enrollments | No | Yes | Yes |
| Open Administration dashboard and manage user roles | No | No | Yes |
| Read Student REST API | No | Yes | Yes |
| Write Student REST API | No | No | Yes |

All API routes require a Sanctum token. API `POST`, `PUT`, `PATCH`, and
`DELETE` additionally require the Administrator role. A Staff token can use
only the Student `GET` endpoints.

Public registration always assigns Staff, regardless of submitted role data.
Administrators cannot change their own role through User Management, and the
last Administrator cannot be demoted or deleted. Profile/account deletion is
transactional and is allowed only if another Administrator remains. User
management does not expose password hashes, sessions, reset tokens, or API
tokens.

## Verification

Role behavior is exercised in `RoleAccessTest`, `AdminUserManagementTest`,
`StudentApiTest`, `ProfileTest`, and `AuthenticationTest`. Run the full suite
with `php artisan test`.
