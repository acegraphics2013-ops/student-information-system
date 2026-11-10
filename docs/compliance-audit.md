# SIMS rubric compliance audit

This audit is based on the checklist pasted into the project conversation,
current source, and the verification record in
[TESTING_AND_VERIFICATION.md](TESTING_AND_VERIFICATION.md). The files
`FinalProjectHandbook.pdf` and `FinalProjectRubric.pdf` were not found in the
workspace, so this is not a claim that those PDFs were reviewed or that an
instructor grade is predicted.

| Area | Weight | Status | Evidence and remaining work |
|---|---:|---|---|
| A1 Authentication | 5% | Implemented / tested | Breeze registration and session auth are present. Registration cannot assign Administrator; automated authentication tests pass. Student must capture genuine screenshots if required. |
| A2 Authorization | 5% | Implemented / tested | Web role middleware and API Admin-only write middleware enforce access server-side. Last-Administrator demotion and account deletion are protected. |
| Administration | Application | Implemented / tested | Admin dashboard and account management are role-protected; profile deletion removes related sessions, tokens, and reset credentials after validation. |
| A3 CRUD | 10% | Implemented / tested | Student, Subject, and Enrollment workflows are covered by feature tests. Enrollment load limit is enforced in application validation, not a database constraint. |
| A4 Database / Eloquent | 10% | Implemented / tested | Migrations, foreign keys, relationships, and uniqueness constraints are present. Academic catalog uses separate College → Department → Program tables. |
| A5 REST API | 10% | Implemented / tested | Sanctum Student resource supports list/show/create/update/delete; Staff read only, Administrator read/write. API contract and OpenAPI docs describe current rules. |
| B1 MVC | 5% | Implemented | Controllers coordinate Eloquent models, shared requests validate inputs, resources shape API JSON, and Blade renders web pages. |
| B2 Code quality | 5% | Partially verified | Changed-file Pint check passes after formatting the changed PHP files. Repository-wide Pint still identifies pre-existing style issues across unrelated files; no broad formatting was applied. |
| B3 Structure | 5% | Implemented | Laravel application, migration, test, API, and documentation responsibilities remain organized by their conventional directories. |
| C1 Prompt engineering | 10% | Partially evidenced | Portfolio retains genuine prompts available in history. Model metadata, complete prompt captures, screenshots, and student evaluation remain unavailable or student-owned. |
| C2 Agent design | 10% | Proposed, not executed | Agent roles and handoffs are documented as a design. No independent agents or handoffs were used for the work recorded here. |
| C3 Context management | 5% | Partially evidenced | Work used bounded file/test scope and carried-forward project context. No token telemetry is available; no measured token savings are claimed. |
| D1 Validation / errors | 5% | Implemented / tested | Required, unique, relationship, duplicate, missing-record, authorization, and API error paths are covered in feature tests. |
| D2 AI output verification | 5% | Evidenced | Test failures and route inspection informed corrections; current complete suite passes. See portfolio and test record for current command results. |
| E1 Technical documentation | 5% | Implemented; evidence pending | Installation, architecture, schema, role policy, API, testing, and rubric sources are documented. Genuine screenshots and any required exported PDFs remain outstanding. |
| E2 Reflection | 5% | Draft only | Factual notes are provided; first-person personal reflections and answers must be written by the student. |

The pasted checklist describes up to 10 bonus points for agent architecture,
cross-model comparison, API documentation, automated tests, and deployment.
API documentation and automated tests have repository evidence. No
cross-model comparison, independent agent execution, or deployment evidence
is claimed.

## Database and safe-seeding notes

- Migration status reports all listed migrations as applied in this local
  environment. No destructive reset was used for this task.
- Academic-program records are separate from Subjects and Enrollments.
- `students.course` remains a string for legacy compatibility; newly selected
  programs are checked against the installed catalog.
- Enrollment prevents duplicate Student/Subject pairs in the database.
  The application enforces the maximum 10-unit load.
- `SubjectCatalogSeeder` is optional demonstration data and now preserves an
  existing subject record for a duplicate code. `DatabaseSeeder` does not
  automatically seed demonstration subjects or create a demo user.
- Dashboard recent lists use record timestamps and are not an audit log.
- The schema-only SQL export is intended to contain DDL only; it is not a
  backup of the local database.

## Current verification and submission gaps

The current full test result is 93 passed tests and 633 assertions. Production
frontend build and Composer validation passed. The repository-wide Pint check
reported formatting issues; the changed-file check passed after formatting.
See [TESTING_AND_VERIFICATION.md](TESTING_AND_VERIFICATION.md) for exact
commands and caveats.

No separate external penetration test, deployment test, genuine screenshots,
completed personal reflection, or PDF export is claimed. Complete the
student-owned evidence and review the final source before submission.
