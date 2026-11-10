# Final submission checklist

## Laravel project ZIP

The prepared source ZIP should contain the Laravel source, `README.md`,
Composer/npm lock files, `.env.example`, migrations, tests, documentation,
and `database/export/schema-only.sql`.

The ZIP must not contain `.git/`, `vendor/`, `node_modules/`, `.env`, local
SQLite data files, logs, caches, or credentials. The schema-only SQL is
included as a database export without copying potentially personal student or
user records. If the instructor specifically requires actual sample rows,
prepare an approved, anonymized database export separately.

Git history remains in the repository/remote and is not copied into the source
ZIP. The current uncommitted changes are included in the prepared ZIP; no
commit, push, or history rewrite is performed by packaging.

## Assessment documents

- [x] Local installation, architecture, schema, role-permission, testing,
  and rubric audit Markdown sources are present in `docs/`.
- [x] The handbook and rubric PDFs were not available in the workspace, so
  the audit uses the supplied rubric checklist and verified repository evidence.
- [x] Draft exports are generated in `submission/` with the required names:
  `AI_PORTFOLIO.pdf`, `AGENT_DESIGN.pdf`, and `REFLECTION.pdf`.
- [ ] Complete the student-owned portfolio evaluation and reflection before
  presenting those drafts as final deliverables.
- [x] Twenty genuine development screenshots are indexed in
  `docs/evidence/SCREENSHOT_INDEX.md`.
- [ ] Capture real SIMS pages and API request/response screens; no such images
  were present in the workspace at verification time.
- [x] Student API reference and OpenAPI 3 specification are included.
- [x] Oral-defense factual answer notes are included in `docs/oral-defense.md`.
- [x] Git history and AI workflow evidence are documented in
  `docs/GIT_AND_AI_REFLECTION.md`.
- [ ] Complete `docs/GIT_AND_AI_REFLECTION.md` with the student's own
  first-person reflection and genuine supporting evidence if required by the
  instructor.

The PDFs in `submission/` are generated from Markdown sources and explicitly
marked as drafts where student input or real application evidence is missing.
The older project-root PDFs were preserved and are not included in the final
package.

## Final safety checks before submission

- [ ] Review the ZIP listing and verify no `.env`, `.git`, `vendor`,
  `node_modules`, local SQLite files, token, or credentials are present.
- [ ] Confirm `.env.example` is present and contains no live secrets.
- [ ] Run `php artisan test`, `composer validate --no-check-publish`, and
  `npm run build` against the final extracted source tree.
- [ ] Ensure the local database has a separate backup before any migration;
  never use `migrate:fresh` or `db:wipe` on the working database.
