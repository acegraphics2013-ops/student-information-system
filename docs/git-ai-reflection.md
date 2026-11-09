# Git and AI development reflection

## Repository evidence

At the time this note was prepared, `git rev-list --count HEAD` reported 22
commits on the current branch. The visible history includes incremental
milestones such as student CRUD and tests, subject CRUD, navigation,
enrollment CRUD, the enrollment save-button test, the Student API, test fixes,
and API/documentation changes. Several commits have meaningful feature/fix
subjects; one visible commit combines multiple subjects in a single message,
so commit count alone does not prove every commit is well-scoped.

The test-coverage additions and documentation changes currently in the
worktree are uncommitted changes. No commit was created for this report, and
the existing commit history was not rewritten.

## Evidence-based assessment

- **Repository maintained:** yes; a Git repository and remote-tracking branch
  are present.
- **Commit count:** 22 at inspection time, exceeding the separate note's
  15-commit Excellent-tier description numerically. The instructor's
  qualitative evaluation of meaningful progression remains theirs.
- **Incremental development:** evidenced by feature-specific commits covering
  students, subjects, enrollment, navigation, API, tests, and docs.
- **AI review:** evidenced by tests and route inspection identifying the
  Student route-name collision and nonexistent Student factory assumption;
  corrections were made and verified.
- **Git debugging outcome:** the current evidence includes a real PHPUnit
  failure from the API route-name collision and subsequent correction. Keep
  the student-visible terminal/chat evidence if screenshots are required.

## Student's personal Git + AI reflection

Complete these statements in first person, using only events you remember or
can verify from Git/chat history:

- Git helped me track development by [specific example].
- Before committing AI-assisted changes, I reviewed [specific files/tests].
- Git helped me find or recover from [real bug or say it did not].
- A commit I would improve is [real commit subject] because [reason].
- Combining Git and AI taught me [personal lesson].

## Bonus-cap ambiguity

The two supplied PDFs specify a maximum of +10 bonus points. A separate
instructor note adds a Professional Development Practices bonus of up to +5
but does not state whether it raises that maximum. This report does not assume
a +15 total; ask the instructor how the two bonus schemes are combined.

## Safe commit recommendations

Do not fabricate or backdate commits, create empty commits, or rewrite
history. If committing the current genuine work, use separate, descriptive
commits only when the changes form real milestones, for example:

1. `test: cover CRUD validation and missing resources`
2. `docs: add AI workflow and submission guidance`

These are recommendations, not commits already made.
