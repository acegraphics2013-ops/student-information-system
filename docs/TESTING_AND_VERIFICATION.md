# Testing and Verification Record — SIMS Version 7

**Project:** Student Information Management System (SIMS), NEMSU Tandag Campus  
**Evidence date:** Version 7 archive inspected November 10, 2026  
**Important distinction:** The original Windows/Laragon report describes a real local testing session, while the independent archive audit performed **static and database checks** and did **not** reproduce the full application test suite.

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

- [ ] Run a fresh `php artisan test` on Windows/Laragon and save full terminal output.
- [ ] Run `npm run build`; verify that the actual login background image loads in the browser on a clean installation.
- [ ] Re-run `vendor\\bin\\pint --test`; resolve or document the 11-file formatting issue.
- [ ] Test login, Administrator/Staff access, and protected CRUD actions manually.
- [ ] Test Student API GET/POST/PUT/DELETE requests with valid and invalid tokens/roles.
- [ ] Check Blade compilation with `php artisan view:cache` (and `php artisan view:clear` afterward), if safe in the local environment.
- [ ] Verify screenshots correspond to real application behavior and capture missing evidence.
- [ ] Check the final Git working tree and commit count.
- [ ] Do not include `.env`, a personal SQLite database, `vendor`, `node_modules`, or `.git` in the final source-only ZIP.

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

## 7. Sign-off

| Verification | Status |
|---|---|
| Original documented local PHPUnit run | **Reported passed (93 / 633)** |
| Independent PHP syntax/database archive checks | **Passed** |
| Independent full PHPUnit rerun | **Not verified** |
| Repository-wide formatting | **Not passing in last recorded run** |
| Login image warning resolved | **Not verified** |
| Final source-only submission ZIP | **Not yet verified** |

**Status:** Evidence-based updated Markdown record, not a claim that Version 7 is fully submission-ready.
