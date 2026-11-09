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
- Independent specialized agents: none were used for the work recorded here.

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

## Prompt 3 — implementing and validating the required Student API

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
`RegistrationTest` passed: 9 tests and 73 assertions. A full test run and local
migration verification are still required before this draft can claim full
project validation.

**Student evaluation:** Review the authorization policy, payload validation,
and response codes; document any changes you personally made or rejected.

## Evidence still to add

- Screenshots of the running dashboard, student/subject/enrollment pages, and
  API requests and responses.
- Exact model/version metadata and complete original prompt captures from the
  student's chat history.
- First-person evaluation of generated suggestions, corrections, and
  engineering trade-offs.
- A final PDF export after the student reviews and completes this draft.
