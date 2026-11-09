# AI engineering portfolio — evidence-backed draft

This draft records only prompts and outcomes present in the supplied project
conversation and current repository work. It is not a substitute for the
rubric's requested portfolio PDF and screenshots. The student should verify
each entry against their full chat history, add screenshots, and write their
own evaluation before submission.

## Tools and model record

- Development environment: Copilot SDK in VS Code.
- Model/version: not recorded in the conversation evidence available for this
  draft.
- Purpose: inspect the existing Laravel workspace, make code changes, execute
  application commands/tests, and update project documentation.
- Observed strengths in this workflow: workspace file inspection, focused
  patches, route/test execution, and iterative corrections in one environment.
- Observed limitation: the session record does not identify a model version
  or provide token-cost telemetry.
- Independent specialized agents: none were used for the work recorded here.
- Other AI platforms tested for comparison: none documented. A comparative
  study would be dishonest without genuine side-by-side runs.
- PDF deliverable: this Markdown file is the source draft. It still needs
  student review, genuine screenshots, and export as `AI_PORTFOLIO.pdf`.

## Prompt 1 — debugging PHP namespace placement

**Objective:** Resolve a test-file fatal error preventing Laravel tests from
starting.

**Original prompt excerpt (user):**

> Fatal error: Namespace declaration statement has to be the very first
> statement or after any declare call in the script in
> C:\laragon\www\student-information-system\tests\Feature\SubjectCrudTest.php
> on line 4

**Problem identified:** PHP does not allow bytes or output before the opening
PHP tag when a namespace declaration follows.

**AI result / correction recorded:** The leading blank line before `<?php` was
removed in the affected test file. The same issue was checked in other touched
PHP files.

**Verification evidence:** The Subject CRUD test later passed, and route
loading was verified during local-run troubleshooting. Preserve the actual
terminal screenshot or test output from the student's environment if it is
required as submission evidence.

**Student evaluation:** Add a first-person assessment of whether this fix
addressed the reported failure and what was checked before changing the file.

## Prompt 2 — adding management navigation

**Objective:** Make the existing management module accessible from the
Breeze navigation.

**Original prompt excerpts (user):**

> move the manage students to nav bar

> `<x-nav-link :href="route('enrollments.index')"
> :active="request()->routeIs('enrollments.*')">Enrollments</x-nav-link>`

> `<x-responsive-nav-link :href="route('enrollments.index')"
> :active="request()->routeIs('enrollments.*')">Enrollments</x-responsive-nav-link>`

**AI result recorded:** Management links were added to Breeze desktop and
responsive navigation, preserving route-based active states.

**Verification evidence:** Navigation changes were made in the existing
Breeze layout; prior project work reports Blade and related feature checks.
Add screenshots showing desktop and mobile navigation if required.

**Student evaluation:** Describe whether the placement and responsive
behavior matched the intended navigation and any manual browser checks.

## Prompt 3 — refining the scope to the existing project

**Original prompt excerpt (user):**

> This is an existing project, NOT a new project.

> DO NOT recreate Laravel, overwrite working modules unnecessarily, replace
> Breeze authentication, reset the database, or destroy existing data.

**Refinement:** The prompt then supplied detailed Laravel, authorization,
CRUD, database, API, test, and documentation requirements and directed the
work to inspect before implementing. This bounded changes to the existing
architecture and data.

**Evaluation:** Existing routes, migrations, role rules, and tests were
inspected before adding the Student API. No new Laravel application was
created, and only the additive Sanctum token migration was applied.

## Prompt 4 — implementing and validating the required Student API

**Objective:** Implement all five required REST endpoints without replacing
existing authentication or schema.

**Original prompt excerpts (user):**

> THIS IS A HIGH-PRIORITY REQUIREMENT.

> Provide tests for all five mandatory endpoints.

> Use the existing Student database schema.

**AI result:** Added Sanctum bearer-token authentication, five Student API
routes, an API controller and resource, shared student validation, and
admin-only API writes. Kept the web CRUD controller and database schema.

**Problem found during validation:** The API resource initially reused the
web route names. Route inspection and a web CRUD test exposed that
`students.index` resolved to the API route. API route names were prefixed
`api.` so web route generation remains unchanged. Tests also showed there is
no Student factory; test data was changed to explicit model creation rather
than adding an unnecessary factory.

**Verification result:** `StudentApiTest`, `StudentCrudTest`, and
`RegistrationTest` passed: 9 tests and 73 assertions. The subsequent full
suite passed with 49 tests and 212 assertions. Composer validation, Pint on
changed PHP files, and the production asset build passed. The additive token
migration applied; local student, subject, and enrollment counts remained
unchanged at one each.

**Student evaluation:** Review the authorization policy, payload validation,
and response codes; document any changes you personally made or rejected.

## Prompt 5 — validation prompt and iterative failure correction

**Actual validation prompt excerpt (user):**

> Run:
>
> `php artisan test`
>
> Report:
>
> - Tests executed
> - Tests passed
> - Tests failed
> - Actual error messages
> - Fixes applied
>
> Do not claim that tests passed unless they actually ran successfully.

**AI-generated output:** Added a partial-update test for the Student API and
used `sometimes|required` as one string inside an array-based Laravel rule
set.

**Problem found:** The full test run reported:
`Method Illuminate\Validation\Validator::validateSometimes|required does not exist.`
The partial-update request returned HTTP 500 instead of the expected 200.

**Correction applied:** Changed the partial required rule to the two Laravel
rule entries `['sometimes', 'required']`, while preserving the existing full
PUT rules.

**Result validated:** The targeted Student API and CRUD tests passed after
the correction. Record the final complete test count below after the final
suite run.

## Token and context management — 5-point rubric evidence

The actual workflow used progressively broader context rather than copying the
entire repository into each decision. Examples below describe actions visible
in the development session; no token totals or model-specific token prices
were recorded.

| Technique | Genuine example from this work | Quality check |
|---|---|---|
| Single-file context | Inspected `StudentDataRequest.php` separately when reviewing the shared validation behavior. | Compared its rules against the student web/API tests and route binding. |
| Feature-based context | Read Student CRUD and Student API tests together while filling student validation, auth, and not-found gaps. | Ran the relevant test filter before the full suite. |
| Module-based context | Reviewed Subject, Enrollment, Dashboard, RoleAccess, and Breeze authentication tests as separate feature modules. | Added only module-relevant assertions and preserved existing role behavior. |
| Smaller task prompts | The actual requests “put save bbutton in enrollments” and “move the manage students to nav bar” addressed bounded UI changes; the current request separately specified validation, docs, and portfolio requirements. | Confirmed the corresponding Enrollment/navigation tests and later ran the full suite. |
| Task decomposition | Work proceeded through inspect, focused implementation, targeted tests, documentation, and full verification rather than a single broad code-generation step. | Each stage had observable tool output; the current test additions pass their targeted run. |
| Incremental generation | API implementation was followed by tests, then route naming was adjusted after a regression surfaced. | The route list and regression test confirmed web/API route names no longer collide. |
| Context reuse | A carried-forward conversation summary retained project constraints, prior decisions, and test results after earlier conversation turns were summarized. | The summary was checked against current files and commands before relying on it. |
| Agent specialization | Specialist responsibilities are documented as a proposed workflow. | No independent agents or specialist handoffs are claimed as actual work. |

### Handbook questions

1. **How did unnecessary AI context get reduced?** By reading likely relevant
   files in bounded groups (for example, student request + Student tests),
   checking the specific migration and route list rather than re-reading the
   entire application, and using the carried-forward summary to retain prior
   decisions. This describes context organization, not a measured token
   reduction.
2. **How was prompt repetition avoided?** The summarized history was reused
   for already-set project constraints; subsequent work focused on the
   remaining tests and documentation. Actual earlier prompts are quoted above
   rather than reconstructed as new historical prompts.
3. **How were AI-generated outputs verified?** With route inspection,
   targeted feature tests, full PHPUnit runs, Pint, Composer validation,
   production asset build, migration status, and before/after record counts.
   The route-name collision, missing factory assumption, and formatting
   changes are recorded with their corrections below.
4. **How were token cost and output quality balanced?** Tests were first run
   for affected feature modules, then the complete suite was run; this gives
   fast feedback without treating narrow success as final proof. Existing
   controllers, schema, and middleware were reused rather than regenerated.
   No token cost telemetry was captured, so no numerical savings are claimed.

### Critical verification examples

#### API route naming

- **AI-assisted implementation:** Added a resource API for the same Student
  model and resource names used by the web UI.
- **Problem found:** Laravel's shared route-name namespace caused
  `students.index` to resolve to the API route, breaking the web test's
  redirect expectation.
- **Correction applied:** Put API resource routes under the `api.` name
  prefix, preserving the existing web route names.
- **Result validated:** `route:list` shows `api.students.*` and `students.*`
  independently; the web and API tests pass.

#### Test fixture assumption

- **AI-generated test setup:** Initially called `Student::factory()` even
  though this project has no Student factory.
- **Problem found:** The test runner reported
  `Call to undefined method App\Models\Student::factory()`.
- **Correction applied:** Used explicit Student records in tests instead of
  adding an unnecessary factory solely for these cases.
- **Result validated:** Student CRUD and API tests pass using the actual
  project setup.

#### Formatting and migration safety

- **AI-assisted changes:** Added API classes and a Sanctum personal-token
  migration.
- **Problem found:** Pint reported formatting differences; the token migration
  was pending and needed to be checked before modifying the local DB.
- **Correction applied:** Ran Pint on changed PHP files, reviewed the migration
  as additive, then applied it without a reset.
- **Result validated:** Pint passed; migration status showed the migration
  applied; student, subject, and enrollment counts remained one each.

## Evidence still to add

- Screenshots of the running dashboard, student/subject/enrollment pages, and
  API requests and responses.
- Exact model/version metadata and complete original prompt captures from the
  student's chat history.
- First-person evaluation of generated suggestions, corrections, and
  engineering trade-offs.
- A final PDF export after the student reviews and completes this draft.
