# Testing and Verification Record

**Project:** Student Information Management System (SIMS), NEMSU Tandag Campus  
**Current verification date:** November 10, 2026
**Environment:** Windows / Laragon, PHP 8.2.12, Laravel 12.71.0, Composer 2.10.3, SQLite

## Current workspace run

| Command | Result | Notes |
|---|---|---|
| `php -v` | Passed | PHP 8.2.12 (CLI). |
| `php artisan about` | Passed | Laravel 12.71.0; local environment; SQLite driver. |
| `php artisan route:list` | Passed | 61 registered routes, including all five Student API operations. |
| `php artisan migrate:status` | Passed | All 13 listed migrations are applied; this command was read-only. |
| `php artisan test` | **93 passed, 0 failed, 0 skipped; 633 assertions** | Duration 26.69 seconds. PHPUnit uses in-memory SQLite as configured in `phpunit.xml`; project database rows were not used by the test database. |
| `npm run build` | Passed | Vite 6.4.5 built 62 modules. It warned that `/images/bg-login.jpg` was not bundled; `public/images/bg-login.jpg` exists for runtime URL resolution. Browser rendering was not tested in this run. |
| `composer validate --no-check-publish` | Passed | `composer.json` is valid. |
| `vendor\bin\pint --test` | Passed | Repository-wide check returned passed. |
| `git diff --check` | Passed after documentation correction | Git emitted only a CRLF-to-LF normalization warning. |
| SQLite integrity checks | Passed | `PRAGMA integrity_check` returned `ok`; `PRAGMA foreign_key_check` returned no rows. Catalog totals: 7 colleges, 13 departments, 22 programs. No record values were read. |
| Git history | Verified | 56 commits at the latest check; history advanced during review. No push or deployment command was issued in this session. |

The screenshot files are historical evidence and are not substitutes for this
fresh command output. The current build warning is non-blocking for the build;
the referenced file exists, but an actual browser check remains advisable.

## Historical Version 7 record

The following archived results describe the earlier Version 7 snapshot only.
They are retained to explain the old screenshots and are superseded by the
current run above.

## 1. Development environment recorded by project

- Windows / Laragon
- PHP 8.2.12; Laravel 12.71.0; Composer 2.10.3
- SQLite; PHPUnit configured with in-memory SQLite
- Laravel Breeze, Laravel Sanctum, Blade, Tailwind CSS, Alpine.js

Versions above are taken from the uploaded `TESTING_AND_VERIFICATION.md`; rerun `php artisan about` and `php -v` on the final local project before treating them as current.

## 2. Local Windows/Laragon results — reported by project

| Command | Recorded result | Qualification |
|---|---|---|
| `php artisan test` | **93 tests passed; 633 assertions** | Reported by the original verification record; attach unedited terminal output/screenshot |
| `npm run build` | Passed | Vite warned that `/images/bg-login.jpg` was unresolved at build time, left for runtime resolution |
| `composer validate --no-check-publish` | Passed | Reported `composer.json` valid |
| `php artisan route:list` | Passed; 61 routes | Includes separate web `students.*` and API `api.students.*` route names |
| `php artisan migrate:status` | Passed | All listed migrations recorded as ran at the time |
| `php artisan about` | Completed | Local environment details recorded |
| `git diff --check` | Passed | Some line-ending conversion warnings reported |
| `vendor\\bin\\pint --test` | **Not clean** | Eleven pre-existing PHP files with reported formatting issues; targeted changed-file check passed |

The original record says its verification did not reset the project database, change credentials, or run seed commands. PHPUnit used an in-memory database.

## 3. Independent Version 7 archive verification

| Check | Result | Boundary of claim |
|---|---|---|
| Archive test source | **93 PHP test methods found** | Source count only; does not prove test execution |
| PHP syntax | **90 ordinary PHP files passed** `php -l` | Another 50 Blade files were linted as PHP, but this does not verify Blade compilation |
| SQLite integrity | `ok` | Based on the archived local database |
| SQLite foreign keys | **Zero violations** | Based on archived database constraints |
| Schema export | Successfully imported; **16 application tables** | Schema import only, not application runtime proof |
| Git history | **41 commits** | Archived snapshot; may differ from current local HEAD |
| Screenshots | **17 JPG files** in archive | They need actual review, captions, and references |
| Runtime PHPUnit | **Not independently rerun** | Linux environment lacked compatible `pdo_sqlite` and Windows-generated dependencies |
| Frontend runtime build | **Not independently reproduced** | Archived Node binaries were Windows-targeted |

The inability to run archived Windows dependencies under Linux is not proof of a failure in the student's Laragon installation.

## 4. Code and API review observations

- Student CRUD, Subject CRUD, and Enrollment CRUD have routes/controllers and feature tests in Version 7.
- All five mandatory Student REST endpoints are present.
- Student API `store`, `update`, and `destroy` require Administrator authorization under Sanctum; Staff can read through the API under the documented role policy.
- Last-Administrator safeguards are present in the profile and administration controllers.
- Database integrity and relationships were checked independently on the archive.

**These are implementation observations, not proof that every route has been manually exercised in a browser.**

## 5. Outstanding verification work

- [ ] Visually verify login background rendering in a browser; `public/images/bg-login.jpg` exists and the production build passes.
- [ ] Capture current login, Administrator/Staff, CRUD, catalog, and API screenshots, with tokens and personal data redacted.
- [ ] Compare the four context questions in the reflection with the actual handbook; the handbook PDF was unavailable here.
- [ ] Complete the student's own cover details, portfolio evaluations, and first-person reflection.
- [ ] Inspect the final source ZIP and confirm excluded files and sensitive records are absent.

## 6. Reproducible local commands (PowerShell)

```powershell
cd C:\laragon\www\student-information-system

php -v
php -m | findstr /I "sqlite"
php artisan about
php artisan route:list
php artisan migrate:status
php artisan test
npm run build
vendor\bin\pint --test
composer validate --no-check-publish
git diff --check
git rev-list --count HEAD
git status
```

Avoid `migrate:fresh`, `db:wipe`, and destructive test/reset commands against the development database.

## 7. Current sign-off

| Verification | Status |
|---|---|
| Fresh local PHPUnit run | **Passed: 93 tests / 633 assertions, no failures or skips** |
| Route and migration checks | **Passed: 61 routes; 13 migrations applied** |
| SQLite integrity and foreign keys | **Passed: integrity `ok`, no foreign-key violations** |
| Production asset build | **Passed with runtime image-resolution warning; browser not checked** |
| Composer validation and Pint | **Passed** |
| Final source-only submission ZIP | **Pending creation and archive inspection** |

**Status:** Fresh technical verification is complete. Manual UI evidence, student-authored reflection, and final submission review remain outstanding.
