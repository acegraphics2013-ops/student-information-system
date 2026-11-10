# Final Project Rubric Audit (with initial gap snapshot)

**Assessment point:** Initial source inspection before rubric-driven fixes.  
**Project:** NEMSU Tandag Campus Student Information Management System  
**Working specification:** The pasted rubric checklist supplied with the task.  
**Authoritative PDFs:** `FinalProjectHandbook.pdf` and `FinalProjectRubric.pdf`
were not found in the repository. This audit therefore does not claim to have
read those documents or verify requirements beyond the supplied checklist.

Statuses describe repository evidence, not an instructor grade.

| Criterion | Weight | Exact rubric requirement (checklist) | Relevant implementation files | Existing status | Problems discovered | Changes needed | Verification method | Final evidence location |
|---|---:|---|---|---|---|---|---|---|
| A1 Authentication | 5% | Verify Breeze registration, login, logout, password hashing, sessions, CSRF, guest redirects, middleware, Remember Me, and password reset. Public registration must not assign Administrator; new accounts default to Staff. | `routes/auth.php`, `app/Http/Controllers/Auth/`, `database/migrations/`, `tests/Feature/AuthenticationTest.php` | PARTIAL | Breeze flows and role default are present; tests cover registration/login. Full requirement-level verification has not yet been rerun. | Run authentication and full test suites; inspect session/CSRF/reset/remember behavior and registration role tampering. | Focused and complete Laravel feature tests; route and middleware inspection. | Pending |
| A2 Authorization | 5% | Enforce Admin and Staff permissions on the server. Protect the last Administrator; block Staff user management and Administrator operations; prevent role escalation. | `app/Http/Middleware/EnsureRole.php`, `routes/web.php`, `routes/api.php`, `app/Http/Controllers/Admin/UserController.php`, `app/Http/Controllers/ProfileController.php`, tests | FAIL | API routes currently restrict only `destroy`; Staff can call API `store`, `update`, and `patch`. Profile account deletion does not protect the final Administrator. | Apply Administrator middleware to all API writes. Protect final-admin self-deletion transactionally and add regression tests. | Authenticated direct HTTP/API requests for Admin, Staff, and final Admin paths. | Pending |
| A3 CRUD Functionality | 10% | Verify list/view/create/update/delete for Students, Subjects, and Enrollments; uniqueness, required fields, valid units and relationships, allowed status, duplicate prevention, search/pagination where implemented, and access permissions. | `app/Http/Controllers/StudentController.php`, `SubjectController.php`, `EnrollmentController.php`, corresponding Blade views, feature tests | PARTIAL | CRUD code and focused tests exist, but no current full run or comprehensive operation-by-operation verification has been performed for this audit. | Run focused module suites and the complete suite; fix only demonstrated defects. | Laravel feature suites; direct route checks. | Pending |
| A4 Database and Eloquent | 10% | Verify migrations, foreign keys, unique constraints, Eloquent relationships, query logic, delete behavior, and preservation of existing data. Verify the 7/13/22 academic catalog and keep degree programs separate from subjects. | `app/Models/`, `database/migrations/`, `database/seeders/NemsuAcademicCatalogSeeder.php`, `database/export/` | PARTIAL | Core models and migrations are present. `SubjectCatalogSeeder` uses `updateOrCreate`, which can overwrite existing names/units. `DatabaseSeeder` invokes it and creates a demonstration user. | Change subject catalog seeding to preserve pre-existing rows and make it explicit rather than automatic. Verify migrations, relations, constraints, and catalog counts. | Migration status, schema inspection, tests, and read-only database counts before/after. | Pending |
| A5 Student REST API | 10% | Provide five Sanctum-protected Student endpoints. Admin may read/write; Staff may read only. Validate input, return appropriate JSON/status codes, and align API docs/OpenAPI with actual behavior. | `routes/api.php`, `app/Http/Controllers/Api/StudentController.php`, `app/Http/Requests/StudentDataRequest.php`, `app/Http/Resources/StudentResource.php`, `tests/Feature/StudentApiTest.php`, `docs/api.md`, `docs/openapi.yaml` | FAIL | Current route middleware permits Staff to POST/PUT/PATCH; docs say Admin-only writes. API docs describe year level 1–6, while current validation allows 1–5. | Protect every write route with Admin middleware; update tests and API docs/OpenAPI; verify all five endpoints and response codes. | API feature tests for Admin, Staff, guest, validation, and missing records; route list. | Pending |
| B1 MVC Architecture | 5% | Verify that models, controllers, views, routes, validation, and authorization follow Laravel responsibilities. | `app/`, `routes/`, `resources/views/` | PARTIAL | Existing MVC separation is visible; no complete architecture review has been recorded. | Document actual architecture and verify key request/response paths without unnecessary refactoring. | Source review and route/controller tests. | Pending |
| B2 Code Quality | 5% | Improve consistency and readability; run Pint if installed; avoid unrelated refactoring. | Laravel PHP source, tests, Composer configuration | NOT VERIFIED | Current PHP/Laravel runtime is available; formatter and complete baseline checks have not been run. | Run Composer validation and Pint check; resolve only relevant issues. | `vendor\\bin\\pint --test`, Composer validation, tests. | Pending |
| B3 Project Structure | 5% | Verify Laravel organization, tests, migrations, and documentation; retain files whose purpose is unclear. | Project tree, `app/`, `database/`, `resources/`, `tests/`, `docs/` | PARTIAL | Standard structure and documentation directory exist. Full inventory is not yet complete. | Inspect required folders and retain source/history. | File inventory and source inspection. | Pending |
| C1 Prompt Engineering | 10% | Document genuine initial/revised/debugging/testing/documentation prompts, outputs, human verification, iteration, limitations, and real screenshots. | `docs/ai-engineering-portfolio.md`, development conversation evidence | PARTIAL | An evidence-backed draft exists, but model metadata and screenshots are unavailable; personal evaluation remains student-owned. | Correct stale claims against current code; retain only verifiable excerpts; add screenshot checklist and student placeholders. | Compare draft claims with conversation/source/tests. | Pending |
| C2 Agent Design | 10% | Document specialist roles, inputs/outputs, handoffs, workflow diagram, actual versus proposed execution, risks, and lessons. | `docs/ai-agent-design.md` | PARTIAL | Draft explicitly says independent agents were not used; it describes a proposed workflow. | Keep that distinction, verify statements, and do not claim independent agent execution. | Compare to actual session/tool evidence. | Pending |
| C3 Token/Context Management | 5% | Explain context management techniques and separate techniques actually used from recommendations; do not invent token counts. | `docs/ai-engineering-portfolio.md`, `docs/ai-agent-design.md` | PARTIAL | Existing docs discuss context management and note that token counts are unavailable. | Verify examples against actual project/session evidence and maintain the actual/recommended distinction. | Source and history review. | Pending |
| D1 Validation and Error Handling | 5% | Verify required, unique, numeric, relationship, duplicate, missing-record, authorization, and JSON validation/errors. | FormRequests, controllers, middleware, feature tests | PARTIAL | Validation rules and tests are present, but complete cross-module verification is pending. | Run targeted and full tests; inspect API 401/403/404/422 and web error redirects. | Feature tests and route checks. | Pending |
| D2 AI Output Verification | 5% | Record actual environment, commands, results, bugs/fixes, regression results, limitations, test method counts, and real screenshot steps. | `docs/`, `phpunit.xml`, command output | PARTIAL | Existing compliance docs contain historical counts and claims that may now be stale. Current full test and environment record is missing. | Capture current environment/check results and update historical claims clearly. | Run required commands; count discovered test methods and actual results. | Pending |
| E1 Technical Documentation | 5% | Update README/API documentation and provide installation, architecture, schema, role-permission, verification, and rubric documents accurately. | `README.md`, `docs/` | PARTIAL | README and several drafts exist; exact required documents are missing and API role/year statements conflict with current code. | Create/update required docs from verified implementation; clearly label user-provided catalog and demonstration data. | Compare docs with routes, validation, tests, schema. | Pending |
| E2 Reflection Report | 5% | Preserve genuine reflection and answer the handbook questions; use `[STUDENT INPUT REQUIRED]` rather than inventing first-person experiences. | `docs/reflection-template.md` | PARTIAL | Reflection draft includes prompts but personal responses and handbook question text are unavailable. | Retain authentic notes, add explicit input placeholders, and do not mark complete. | Review source evidence and student-provided reflection. | Pending |

## Initial findings and scope limitations

- The working tree was clean at initial inspection. The local `.env` is
  untracked and ignored; it was not read, modified, or included in this report.
- `FinalProjectHandbook.pdf` and `FinalProjectRubric.pdf` were not found in the
  workspace. The pasted checklist is the only rubric source available here.
- `routes/api.php` currently applies `role:admin` only to Student API deletion;
  this does not meet the supplied Admin-only write policy.
- `ProfileController::destroy` currently deletes the authenticated account
  without a final-Administrator safeguard.
- `SubjectCatalogSeeder` currently uses `updateOrCreate`; `DatabaseSeeder`
  automatically invokes it and creates a `Test User`. Existing local subject
  rows must be preserved while fixing this seeding behavior.
- `docs/api.md` currently states that API writes are Administrator-only, but
  this is not enforced for POST/PUT/PATCH. It also says year level 1–6, while
  the current shared request validates 1–5.
- The repository includes AI portfolio, agent design, reflection, API, and
  compliance drafts. They contain historical test counts that need replacement
  with current command results where appropriate.
- No evidence of independent specialized agent executions, AI model
  comparisons, deployment, screenshots, or completed personal reflection is
  available in the initial inspection.

## Final status after implementation and verification

The findings above are an initial snapshot and must not be read as the current
state. The following updates describe the final code checked during this task:

| Area | Final status | Evidence |
|---|---|---|
| A1 Authentication | Implemented / tested | Registration role-tampering regression test and complete test suite pass. |
| A2 Authorization | Implemented / tested | All Student API writes require Administrator; Staff read-only API tests pass; last-Administrator profile deletion is blocked. |
| A3 CRUD | Implemented / tested | Existing Student, Subject, and Enrollment workflows are covered by the complete suite. |
| A4 Database / seeding | Implemented / tested | Subject seeding preserves a pre-existing duplicate-code record; default seeding no longer creates demo users or subjects. Migrations remain applied. |
| A5 Student API | Implemented / tested | Read/write permissions, JSON validation and missing-resource responses are covered; API guide and OpenAPI maximum year level now match validation (1–5). |
| B1–B3 Architecture and structure | Implemented | Standard Laravel boundaries are documented in `ARCHITECTURE.md`; no unrelated restructuring was performed. |
| C1–C3 AI evidence | Partial / student-owned gaps | Real prompt examples and proposed agent design are recorded. No independent agents, cross-model comparison, model metadata, or token telemetry are claimed. |
| D1–D2 Validation and output verification | Implemented / tested | The complete suite passes 93 tests / 633 assertions; earlier route/test errors and their corrections remain documented. |
| E1 Documentation | Implemented; submission evidence pending | Installation, architecture, schema, role, testing, API, and audit sources are present. |
| E2 Reflection | Incomplete by design | First-person reflection, screenshots, and required PDF exports remain for the student. |

Final validation was recorded in `TESTING_AND_VERIFICATION.md`: full tests,
Composer validation, production asset build, route list (61 routes), and
migration status succeeded. Repository-wide Pint check reported formatting
issues; the targeted check for changed PHP files passed after formatting.
No destructive database command, `.env` edit, password change, commit, push,
or deployment was performed. See `compliance-audit.md` for remaining
limitations and student-owned evidence.
