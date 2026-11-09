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

- [ ] Export `docs/ai-engineering-portfolio.md` as `AI_PORTFOLIO.pdf` after
  adding genuine screenshots and confirming the prompt evidence.
- [ ] Export `docs/ai-agent-design.md` as `AGENT_DESIGN.pdf`.
- [ ] Complete personal sections in `docs/reflection-template.md` and export
  as `REFLECTION.pdf`.
- [ ] Capture actual screenshots and link them in the README/portfolio.
- [x] Student API reference and OpenAPI 3 specification are included.
- [x] Oral-defense factual answer notes are included in `docs/oral-defense.md`.
- [x] Git history and bonus-cap ambiguity are documented in
  `docs/git-ai-reflection.md`.

No PDF export utility was available in the workspace at preparation time, so
the Markdown sources are present but the three PDF deliverables are not yet
created. Do not present the source ZIP as a complete final assessment package
until those exports and genuine screenshots are added.

## Final safety checks before submission

- [ ] Review the ZIP listing and verify no `.env`, `.git`, `vendor`,
  `node_modules`, local SQLite files, token, or credentials are present.
- [ ] Confirm `.env.example` is present and contains no live secrets.
- [ ] Run `php artisan test`, `composer validate --no-check-publish`, and
  `npm run build` against the final extracted source tree.
- [ ] Ensure the local database has a separate backup before any migration;
  never use `migrate:fresh` or `db:wipe` on the working database.
