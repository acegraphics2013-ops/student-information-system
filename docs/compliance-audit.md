# SIMS rubric compliance audit

This assessment uses the supplied `FinalProjectRubric.pdf` and
`FinalProjectHandbook.pdf`, together with the application source and focused
test results. Status describes repository evidence, not a predicted grade.

| Rubric area | Weight | Status | Evidence / remaining work |
|---|---:|---|---|
| A1 Authentication | 5% | Implemented / tested | Existing Breeze registration, login, logout, session and protected routes; registration test verifies public role input cannot grant admin. |
| A2 Authorization | 5% | Implemented / tested | Existing role middleware protects web writes; API reads require Sanctum and writes require admin; Student web/API tests cover staff restrictions. |
| A3 CRUD implementation | 10% | Implemented / tested | Student, Subject, and Enrollment web CRUD are present. Operation and authorization tests pass for each module in the full suite. |
| A4 Database design and Eloquent | 10% | Implemented / verified | Existing migrations, unique constraints, foreign keys, relationships, and Eloquent usage are retained. The additive Sanctum migration ran; student, subject, and enrollment counts remained 1 each before and after. |
| A5 REST API implementation | 10% | Implemented / tested | Five required Student API endpoints, JSON resource, validation, token authentication, role checks, and response codes are implemented. API feature tests pass. |
| B1 MVC compliance | 5% | Implemented | API controller, shared FormRequest, JSON resource, Eloquent model and routes remain separated. |
| B2 Code quality | 5% | Verified for changed files | Laravel Pint passes on touched PHP files; Composer validation, full tests, and diff whitespace checks pass. This does not claim that every pre-existing file was refactored. |
| B3 Project structure | 5% | Implemented | API controller, request, resource, routes, feature tests, and `docs/` are organized by responsibility. |
| C1 Prompt engineering | 10% | Partially evidenced | The portfolio records real prompt excerpts and results available from this conversation. Student-owned screenshots, model metadata, and fuller original prompt captures are still required. |
| C2 Agent design | 10% | Partially evidenced | A specialized multi-agent workflow is proposed in `ai-agent-design.md`. This work was actually performed in one assistant session, not by independent agents; the rubric's multi-agent evidence is not yet demonstrated. |
| C3 Token and context management | 5% | Partially evidenced | Work was divided into inspect, implement, focused-test, and document phases with a carried-forward context summary. No token usage measurements or student reflection are available. |
| D1 Validation and error handling | 5% | Implemented / focused tests passed | Shared student validation plus tested API `401`, `403`, `404`, and `422` behavior; web CRUD tests include validation and authorization. |
| D2 AI output verification | 5% | Evidenced; student reflection pending | A route-name collision and missing Student factory assumption were caught by tests and corrected. The full suite passes after correction; the student still needs to provide their own evaluation. |
| E1 Technical documentation | 5% | Partially implemented | Local setup and API documentation are provided. Add screenshots of the student's running application as required by the rubric. |
| E2 Reflection | 5% | Student completion required | A blank reflection template is provided. Personal experience and lessons are intentionally not invented. |

The rubric also awards up to 10 bonus points for multi-agent architecture,
cross-model comparison, API documentation, automated tests, and deployment.
API documentation and automated tests now have repository evidence. No
cross-model comparison or deployment evidence is claimed; agent bonus evidence
requires actual handoffs.

## Audit notes

- The later duplicate role migration is an already-applied no-op. It is
  retained to preserve migration history and compatibility with installations
  that have recorded it.
- The new Sanctum migration only creates `personal_access_tokens`. It ran
  successfully, and existing student, subject, and enrollment row counts were
  unchanged (one each before and after).
- Student and Subject deletion behavior must continue to follow the existing
  foreign-key cascade rules. Enrollment's unique student/subject constraint
  prevents duplicate enrollments at the database layer.
- User registration is not allowed to assign a role; the application assigns
  its default staff role. Promote admin accounts explicitly through a trusted
  local administrative workflow.
- Final validation passed: 49 tests / 212 assertions, production asset build,
  Composer validation, Pint on changed PHP files, and migration status.
  Student-provided screenshots, personal reflection, and any deployment
  evidence remain outstanding.
