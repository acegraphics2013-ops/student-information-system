# AI agent design and actual workflow

## Intended specialized workflow

The following is the proposed architecture for future work. A handoff is a
reviewable artifact with explicit inputs, outputs, and an acceptance gate;
agents must not independently alter unrelated project areas.

| Role | Responsibility | Handoff and acceptance gate |
|---|---|---|
| Planner Agent | Map handbook/rubric requirements to existing files, status, risks, and a prioritized task list. | Requirement-to-file checklist; human confirms scope before edits. |
| Database Agent | Inspect migrations, schema, constraints, relationships, and migration history without resetting or deleting data. | Schema findings and migration plan; verify every proposed migration is additive or explicitly approved. |
| Laravel Development Agent | Implement routes, controllers, validation, authorization, views, and REST endpoints using existing project conventions. | Small, reviewable diff; route list, request/response contract, and affected files handed to Testing Agent. |
| Testing and Review Agent | Add/run focused tests, inspect authorization and validation paths, and identify regressions. | Reproducible test output and unresolved findings; reject changes that fail required assertions. |
| Documentation Agent | Update setup, API, compliance, portfolio, and evidence organization. | Documentation checked against current code and real test output; mark student-owned evidence gaps explicitly. |

The human student owns decisions, credential handling, final review,
screenshots, deployment, and personal reflection. No agent should fabricate
evidence or apply a destructive database command.

## Workflow actually performed for this change

No separate agents were used in the project work recorded in this conversation.
The actual workflow was a single Copilot SDK assistant session with sequential
phases:

1. Reviewed the existing application and supplied requirements before editing.
2. Inspected Sanctum setup and previewed the pending migration; did not reset
   the database.
3. Implemented the API, shared validation, response resource, and tests.
4. Used route inspection and focused tests to catch and correct a naming
   collision and an assumed factory.
5. Prepared documentation from available conversation and repository evidence.
6. Ran the full suite (49 tests / 212 assertions), production asset build,
   Composer validation, targeted Pint check, and migration status check.
   The token migration applied and the existing three SIMS table counts
   remained unchanged.

The proposed role descriptions above are not claims of actual independent
agent execution or handoffs.

## Context and token management

- Work was decomposed by deliverable: code audit, API implementation, focused
  tests, documentation, and final validation.
- The carried-forward conversation summary preserved requirements and current
  project state when older turns were summarized.
- Changes were validated with targeted tests before widening to the full suite.
- Existing schema and authentication behavior were inspected and reused rather
  than repeating setup or generating a new application.
- No token counters, model version, or comparative token measurements were
  captured. Add factual measurements from the student's tool history if
  available; do not estimate them.
