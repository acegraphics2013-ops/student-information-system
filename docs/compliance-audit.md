# SIMS rubric compliance audit

This assessment uses the supplied `FinalProjectRubric.pdf` and
`FinalProjectHandbook.pdf`, together with the application source and focused
test results. Status describes repository evidence, not a predicted grade.

| Rubric area | Weight | Status | Evidence / remaining work |
|---|---:|---|---|
| A1 Authentication | 5% | Implemented / tested | Existing Breeze registration, login, logout, session and protected routes; registration test verifies public role input cannot grant admin. |
| A2 Authorization | 5% | Implemented / tested | Existing role middleware protects web writes; API reads require Sanctum and writes require admin; Student web/API tests cover staff restrictions. |
| Administration | Included in application / authorization criteria | Implemented / tested | Admin-only database-driven dashboard and user directory; server-validated role-only updates; self-demotion blocked; transaction rollback protects the last-admin invariant; profile role-tampering regression test. |
| A3 CRUD implementation | 10% | Implemented / tested | Student, Subject, and Enrollment web CRUD are present. Student create/edit now select a catalog program through College → Department → Program selectors; catalog selection does not modify subject enrollments. Operation and authorization tests pass for each module. |
| A4 Database design and Eloquent | 10% | Implemented / verified | Existing migrations, constraints, and Student → Enrollment → Subject relationships are retained. Three additive catalog migrations create 7 colleges, 13 departments, and 22 programs; the idempotent seeder ran without changing existing Student, Subject, and Enrollment counts (one each). |
| A5 REST API implementation | 10% | Implemented / tested | Five required Student API endpoints, JSON resource, validation, token authentication, role checks, and response codes are implemented. New API writes validate catalog program names while unchanged legacy courses remain update-compatible. |
| B1 MVC compliance | 5% | Implemented | API controller, shared FormRequest, JSON resource, Eloquent model and routes remain separated. |
| B2 Code quality | 5% | Verified for changed files | Laravel Pint passes on touched PHP files; Composer validation, full tests, and diff whitespace checks pass. This does not claim that every pre-existing file was refactored. |
| B3 Project structure | 5% | Implemented | API and academic-program controllers, shared request validation, Eloquent catalog models, routes, feature tests, and `docs/` are organized by responsibility. |
| C1 Prompt engineering | 10% | Partially evidenced | The portfolio records real prompt excerpts and results available from this conversation. Student-owned screenshots, model metadata, and fuller original prompt captures are still required. |
| C2 Agent design | 10% | Partially evidenced | A specialized multi-agent workflow is proposed in `ai-agent-design.md`. This work was actually performed in one assistant session, not by independent agents; the rubric's multi-agent evidence is not yet demonstrated. |
| C3 Token and context management | 5% | Partially evidenced | Work was divided into inspect, implement, focused-test, and document phases with a carried-forward context summary. No token usage measurements or student reflection are available. |
| D1 Validation and error handling | 5% | Implemented / tested | Required fields, email, unique values, bounds, invalid catalog/program relationships, legacy course preservation, invalid relationship IDs/statuses, duplicate enrollments, missing resources, role violations, and API `401`/`403`/`404`/`422` responses are covered. Student number format itself has no project-defined pattern; blank, duplicate, and overlong values are tested. |
| D2 AI output verification | 5% | Evidenced; student reflection pending | Route-name collision, missing Student factory, invalid PATCH rule construction, and misplaced test assertion were exposed by test/route output, corrected, and reverified. Personal evaluation remains the student's responsibility. |
| E1 Technical documentation | 5% | Partially implemented | Local setup, API guide, OpenAPI spec, compliance report, and schema export are provided. Genuine app/API screenshots are still required. |
| E2 Reflection | 5% | Evidence-backed draft; student completion required | Verified challenges and corrections are recorded in the reflection draft. First-person lessons and judgments are left for the student. |

The rubric also awards up to 10 bonus points for multi-agent architecture,
cross-model comparison, API documentation, automated tests, and deployment.
API documentation and automated tests now have repository evidence. No
cross-model comparison or deployment evidence is claimed; agent bonus evidence
requires actual handoffs.

## Audit notes

- The later duplicate role migration is an already-applied no-op. It is
  retained to preserve migration history and compatibility with installations
  that have recorded it.
- The Sanctum migration and three academic-catalog migrations are additive.
  The catalog tables did not exist before migration. After migration/seeding,
  catalog totals were 7 colleges, 13 departments, and 22 programs; existing
  student, subject, and enrollment counts remained one each.
- The catalog seed is repeatable and inserts missing records without deleting
  SIMS records. It explicitly stops rather than overwriting a college when a
  catalog abbreviation is already assigned to a different name.
- `students.course` remains a string for compatibility. New web/API entries
  must use a catalog program name; an unchanged legacy value can be preserved
  on update. Degree programs do not populate `subjects` or `enrollments`.
- Student and Subject deletion behavior must continue to follow the existing
  foreign-key cascade rules. Enrollment's unique student/subject constraint
  prevents duplicate enrollments at the database layer.
- User registration is not allowed to assign a role; the application assigns
  its default staff role. Promote admin accounts explicitly through a trusted
  local administrative workflow.
- Administration routes require authentication and the `admin` role. User
  detail pages expose only name, email, role, registration time, and
  verification state. The user-management resource has no delete action.
  Recent dashboard records are database timestamps, not a fabricated audit
  trail.
- Final validation after the Administration upgrade passed: 70 tests / 374
  assertions, production asset build, Composer validation from the preceding
  catalog phase, Pint on changed PHP files, and migration status from the
  preceding catalog phase. No migration or destructive database command was
  run during the Administration upgrade. The
  schema-only SQL export imported successfully into a fresh in-memory SQLite
  database and contains no row inserts. Student-provided screenshots,
  personal reflection, PDF exports, and any deployment evidence remain
  outstanding. No separate penetration test or external deployment test was
  performed.
