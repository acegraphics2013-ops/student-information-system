# Final Project Rubric Audit

**Assessment date:** November 10, 2026
**Project:** NEMSU Tandag Campus Student Information Management System

The official `FinalProjectHandbook.pdf` and `FinalProjectRubric.pdf` were not
available in the workspace. This provisional audit uses the criteria and
weights supplied in the project brief and evidence checked in the current
workspace. It is not an instructor grade or grade prediction.

| Criterion | Maximum | Current Status | Evidence | Remaining Work |
|---|---:|---|---|---|
| A1 Authentication | 5 | PASS | Breeze registration, login, logout, password reset and verification routes; the fresh suite passes 93 tests / 633 assertions. Registration creates Staff accounts. | Add genuine browser evidence if the instructor requires it. |
| A2 Authorization | 5 | PASS | Role middleware protects Administration; API write middleware covers create, update and delete; tests verify Staff restrictions and last-Administrator safeguards. | Include current access-control screenshots if required. |
| A3 Student/Subject/Enrollment CRUD | 10 | PASS | All three web modules are registered and their feature tests pass. | Capture real screens of the workflows for assessment evidence. |
| A4 Database and Eloquent | 10 | PASS | All 13 migrations are applied; SQLite integrity check is `ok`, foreign-key check is empty; catalog counts are 7 colleges, 13 departments and 22 programs. | Compare catalog source data with the institution's authoritative source. |
| A5 Student REST API | 10 | PASS | Five Sanctum-protected Student endpoints are registered; tests verify Admin writes and Staff read-only access. API guide and OpenAPI specification describe current validation. | Capture genuine request/response evidence if required. |
| B1 MVC Architecture | 5 | PASS | Routes, controllers, requests, models, resources and Blade views use Laravel's established boundaries. | No additional implementation work identified. |
| B2 Code Quality | 5 | PASS | Full PHPUnit suite, Composer validation, production build and repository-wide Pint check pass. | Keep the current verification record with the submission. |
| B3 Project Structure | 5 | PASS | Existing Laravel layout includes application code, migrations, tests, frontend sources and technical docs. | Verify extracted ZIP contents before submission. |
| C1 Prompt Engineering | 10 | PARTIAL | Portfolio preserves prompt excerpts and documented iterations from project history. | Student must verify original prompts, identify actual AI tools, add genuine screenshots and write personal evaluations. |
| C2 Agent Design | 10 | PARTIAL | Agent Design describes the requested specialist roles, interfaces, handoffs, oversight and Mermaid workflow; it explicitly says the workflow is proposed, not executed. | Student should confirm the actual workflow from their own records; no independent agent execution is evidenced here. |
| C3 Token/Context Management | 5 | PARTIAL | Documents describe focused file reading, task decomposition and targeted checks; no token telemetry or measured savings are claimed. | Student must add a genuine personal example if required. |
| D1 Validation and Error Handling | 5 | PASS | Feature tests cover validation, duplicate data, missing resources, authorization and API errors; all 93 tests pass. | None identified in the tested scope. |
| D2 AI Output Verification | 5 | PARTIAL | Recorded AI-related defects have corrections; current route, migration, test, build and formatting checks were run. | Add student-authored acceptance notes and genuine evidence of manual review. |
| E1 Technical Documentation | 5 | PARTIAL | README and docs cover installation, roles, CRUD, API, architecture, schema and tests; three exported PDFs are drafts because required personal evidence remains incomplete. | Review handbook-specific requirements, complete evidence, and validate the submission ZIP. |
| E2 Reflection | 5 | PARTIAL | Reflection has evidence-based project facts and a structured questionnaire. | Student must supply their own first-person answers and personal details before it can be final. |

**Total available:** 100 points. No points are summed and no official grade is assigned.

## Evidence limits

- The handbook and rubric PDFs were unavailable, so their contents and any additional requirements could not be checked directly.
- Twenty screenshot files exist. They show development tools and terminal/Git evidence; no genuine running-app screen or API-client exchange was found.
- The repository is at 46 commits at the time of this audit. No commit or push was made for this work.
- The local database contains student, subject and enrollment rows. Only aggregate counts were read; the database and profile uploads are excluded from the submission package.
- The existing PDFs at the project root were left untouched. New draft exports are prepared separately under `submission/`.

See [TESTING_AND_VERIFICATION.md](TESTING_AND_VERIFICATION.md),
[evidence screenshot index](evidence/SCREENSHOT_INDEX.md), and
[FINAL_SUBMISSION_STATUS.md](FINAL_SUBMISSION_STATUS.md) for supporting detail.
