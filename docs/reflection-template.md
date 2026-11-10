# AI-assisted development reflection — evidence-based draft

This Markdown document is the source for the requested `REFLECTION.pdf`.
The PDF export remains to be done after student review.

The factual notes below are based on the inspected repository and executed
checks. They are not claims about the student's feelings or opinions. Complete
the explicitly marked personal sections in your own words.

## AI tools and model

- **Tool available and used in this workflow:** Copilot SDK in VS Code for
  workspace inspection, code changes, commands, tests, and documentation.
- **Model/version:** not recorded in the available evidence.
- **Other platforms or models compared:** none documented. Do not claim a
  comparison without actual runs.

## Prompt strategy

- A real initial debugging prompt reported the PHP namespace fatal error in
  `tests/Feature/SubjectCrudTest.php`.
- Later prompts supplied project constraints, specific route/view edits, and
  rubric-based requirements. Actual excerpts are recorded in the AI
  engineering portfolio; they are not recreated as historical prompts.
- The work was decomposed into inspection, API implementation, focused tests,
  documentation, and final verification.
- Context was reused through the carried-forward project summary; relevant
  feature files and test suites were read instead of re-auditing every file
  for each change.

## Agent strategy

- Proposed specialist roles: Planner, Database, Laravel Development, Testing
  and Review, Documentation.
- Agents actually used and handoffs: no independent agents were used in this
  workflow; one assistant session performed sequential phases.
- The specialized workflow in `ai-agent-design.md` is a proposal, not a record
  of agent execution.

## Challenges and corrections

Verified examples include:

- The first API route setup collided with existing web route names. Route
  inspection and a failing web CRUD test exposed that `students.index`
  resolved to the API route. API route names were prefixed `api.`; route
  inspection and the tests then passed.
- An initial test referenced a nonexistent `Student::factory()`. The runner
  reported `Call to undefined method App\Models\Student::factory()`. Tests
  were changed to create explicit Student records.
- Pint reported style fixes on changed PHP files. Pint was run to apply and
  then verify the formatter output.
- The Sanctum token migration was reviewed as additive. It was applied
  without resetting the database; the existing student, subject, and
  enrollment counts remained one each.

The completed AI portfolio documents each example as AI-assisted suggestion,
problem found, correction, and verification.

## Lessons learned

- Latest verified checks: 93 tests and 633 assertions passed; Composer
  validation, route listing, migration status, and the frontend production
  build completed successfully. The targeted Pint check for changed files
  passed after formatting; the repository-wide Pint check still reports
  existing formatting issues. Review `TESTING_AND_VERIFICATION.md` for exact
  output and caveats. The student should still review these results and
  capture genuine screenshots before exporting.
- **Student's personal lessons and evaluation:** [complete in first person;
  explain what you learned and why your engineering choices were appropriate.]

## My personal reflection — required student completion

Do not leave these prompts as claims written by an assistant. Add specific,
first-person examples and explain the engineering decision you made:

1. Which AI suggestion did I decide not to accept as-is, and what evidence
   influenced my decision?
2. What did I personally inspect or run before trusting the API and database
   changes?
3. Which failure taught me the most about Laravel, HTTP behavior, validation,
   or testing, and why?
4. How would I improve the prompt or divide the work if I repeated this
   project?
5. What work remains for screenshots, deployment, agent handoffs, or other
   assessment evidence?

## Oral-defense preparation — factual starting points

The concise codebase-based answers are in `oral-defense.md`. Review them,
then complete these personal answers:

- What is the most challenging problem I personally faced, and why?
  [first-person answer based on your experience]
- What would I prioritize with two more weeks, and what measurable outcome
  would I expect? [first-person answer]

## Git and AI reflection — student completion

- How did commits help me track implementation milestones? [personal account]
- How did I review AI-assisted edits before committing them? [personal
  account]
- Did Git help me isolate a regression or compare versions? Give a real
  example or state that it did not occur. [personal account]
- What would I change about my commit grouping or messages next time?
  [personal account]

## Evidence checklist

- [ ] Attach genuine screenshots from the local application and API client.
- [ ] Include final test/build output.
- [ ] Verify prompt excerpts against the complete development chat history.
- [ ] Complete the personal reflection and Git/AI reflection in first person.
- [ ] Link genuine screenshots and update final test results.
- [ ] Export this completed reflection to `REFLECTION.pdf`.
