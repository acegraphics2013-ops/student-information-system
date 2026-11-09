# Oral defense preparation

These concise answers describe the inspected implementation and completed
engineering decisions. Personal opinions are marked for the student to fill in.

## Explain the MVC implementation

Eloquent models represent `Student`, `Subject`, `Enrollment`, and `User`.
Web controllers coordinate CRUD requests and return Blade views or redirects.
The API has a separate Student controller and JSON resource; student input
rules are shared through a FormRequest. Blade templates render the UI rather
than containing database workflows. Routes apply authentication and
role-based middleware.

## Why was the database designed this way?

Students and subjects relate through enrollments, which store foreign keys
and status. Unique student numbers, emails, subject codes, and student-subject
enrollment pairs enforce data integrity. Foreign keys maintain referential
integrity and existing cascade behavior. A separate Sanctum token table was
added for personal access tokens; the existing SIMS data schema was not
recreated.

## How does route model binding work?

Resource routes use `{student}`, `{subject}`, and `{enrollment}` parameters
that Laravel resolves to Eloquent models through controller type hints.
Existing records are injected into `show`, `edit`, `update`, and `destroy`;
unknown identifiers return 404. API student routes use the same binding and
return JSON-formatted errors for unknown IDs.

## Why was this AI platform selected?

The recorded environment was Copilot SDK in VS Code, allowing the assistant
to inspect project files, make focused edits, and run tests/build commands in
the existing workspace. The exact model/version and comparisons with other
platforms are not in the evidence; do not claim a comparative selection.

## How did prompts evolve?

The available history includes specific debugging requests (such as the PHP
namespace fatal error), bounded UI requests (navigation and enrollment save
button), and later rubric-based requirements that named constraints and
acceptance checks. The carried-forward summary was used to retain earlier
decisions. Use full genuine conversation captures if asked to demonstrate
iterations; do not invent missing prompt text.

## How were AI agents organized?

The intended design has Planner, Database, Laravel Development, Testing and
Review, and Documentation roles, with explicit artifact handoffs and human
approval gates. In the actual work recorded here, no independent agents were
used: a single assistant session proceeded through those phases. The proposed
design must not be presented as actual multi-agent execution.

## What AI-generated code was rejected or corrected?

An API route setup initially reused the web route names, and a web test
revealed `students.index` pointed to the API route. The API routes were given
an `api.` name prefix and then inspected and tested. An initial test also
assumed a Student factory that did not exist; explicit Student records were
used instead. These are verified corrections, not hypothetical examples.

## What was the most challenging technical problem?

The evidence shows a route-name collision and a missing test factory as
concrete failures, but it does not establish which one the student personally
found most challenging. Select and explain the one that best reflects your
own experience: [student's first-person answer].

## What would improve with two additional weeks?

Suggested priorities, not claims of work already done: capture real UI/API
screenshots; complete the student's first-person reflection and PDF exports;
run a browser-level manual walkthrough; add API tests for any remaining
contract edge cases; and, only if approved, build real agent handoffs or a
deployment. The student should state their own sequence and measurable goals:
[student's answer].
