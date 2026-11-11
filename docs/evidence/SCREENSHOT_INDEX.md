# Screenshot Evidence Index

**Inventory checked:** November 10, 2026  
**Source folder:** `screenshots/`  
**Count:** 20 images

The listed dates are filesystem modification dates, not independently verified
capture timestamps. All screenshots are authentic files in the workspace.
They show development/setup activity; none shows a live SIMS page or an
API-client request and response. Four development captures are selected for
the draft Portfolio PDF and DRAFT ZIP. Other images are omitted where they
show unrelated accounts, older results, or project setup outside this repo.

| Filename | What it shows | Date | Rubric relevance | Recommended use / package status |
|---|---|---|---|---|
| `1. Open your Laravel project.JPG` | VS Code with an older PHPUnit run showing 34 tests and 101 assertions. | 2026-11-09 | D1 testing | Historical only; omit from package because it is not the current result. |
| `11092026423PM.JPG` | VS Code project with an older PHPUnit run showing 34 tests and 101 assertions. | 2026-11-09 | D1 testing | Historical only; omit from package. |
| `11092026440PM.JPG` | PHP namespace declaration failure and the related test-file correction. | 2026-11-09 | C1 prompt case 1; D1 debugging | Portfolio Case 1. Include as `screenshots/11092026440PM.JPG`. |
| `11092026450PM.JPG` | Public GitHub repository page with account/profile controls visible. | 2026-11-09 | B3 structure; Git | Omit; account UI is unnecessary. |
| `11092026501PM.JPG` | Subject routes and a targeted Subject test run: 3 passed, 17 assertions. | 2026-11-09 | A3 CRUD; D1 testing | Historical module evidence; omit. |
| `11092026512PM.JPG` | Enrollment routes and targeted Enrollment test run: 4 passed, 22 assertions. | 2026-11-09 | A3 CRUD; D1 testing | Historical module evidence; omit. |
| `11092026514PM.JPG` | Enrollment feature diff, targeted tests, Git status and commit commands. | 2026-11-09 | A3 CRUD; B2 review; Git | Historical development evidence; omit. |
| `11092026518PM.JPG` | Enrollment Blade diff with Enrollment route listing. | 2026-11-09 | A3 CRUD; B2 review | Historical development evidence; omit. |
| `11092026720PM.JPG` | Historical Laravel route list showing 51 routes, including Student API routes. | 2026-11-09 | A5 API; D1 route review | Older route count; omit. |
| `11092026730PM.JPG` | Verbose Student API routes with Sanctum authentication and Administrator middleware on writes. | 2026-11-09 | A2 authorization; A5 API | Portfolio API case study. Include as `screenshots/11092026730PM.JPG`. |
| `11092026731PM.JPG` | Partial PHPUnit output with passing test groups; final summary is not visible. | 2026-11-09 | D1 testing | Process evidence only; omit. |
| `11092026732PM.JPG` | Historical Vite production build and Composer validation output. | 2026-11-09 | B2 quality checks | Historical build evidence; omit. |
| `111020260251PM.JPG` | Partial PHPUnit output in VS Code; final summary is not visible. | 2026-11-10 | D1 testing; D2 verification | Portfolio verification case. Include as `screenshots/111020260251PM.JPG`. |
| `111020260252PM.JPG` | Historical Vite warning, Pint output, and a Git count of 41. | 2026-11-10 | B2 checks; Git | Portfolio verification case; label 41 as historical. Include as `screenshots/111020260252PM.JPG`. |
| `111020260253PM.JPG` | VS Code Source Control showing staged/unstaged file names and an in-progress commit message. | 2026-11-10 | Git; B3 structure | Historical status, not proof of final state; omit. |
| `111020261049AM.JPG` | VS Code request log and route-list output; no rendered application page is visible. | 2026-11-10 | A5 API/routes; D1 runtime | Not live-UI evidence; omit. |
| `2. Install Laravel Breeze using Composer.JPG` | Composer package discovery and Breeze's interactive stack selection. | 2026-11-09 | A1 setup; B3 structure | Setup activity does not prove installation completed; omit. |
| `7. Save your Git progress.JPG` | Enrollment migration/model code and the initial schema commit. | 2026-11-09 | A4 database/Eloquent; Git | Historical schema work; omit. |
| `Step 1 - Prepare your development environment.JPG` | PHP/Composer/Node/npm/Git versions in a terminal path named `student_system_cabuga`, not this repo. | 2026-11-09 | Not verified for this repository | Do not use as current environment evidence; omit. |
| `Step 2 - Create the Laravel project.JPG` | `composer create-project` attempt in the separate `student_system_cabuga` path, ending with a disk-space warning. | 2026-11-09 | Not relevant to existing-project work | Do not imply this SIMS was rebuilt; omit. |

## Screenshots Still Needed

Capture authentic, current screenshots if the instructor requires visual
evidence:

- Login and registration screens, with passwords and personal data hidden.
- Administrator dashboard and user-management role restrictions.
- Student list, create/edit workflow, and a validation error state.
- Subject list and create/edit workflow.
- Enrollment list and create/update workflow.
- Academic catalog showing Colleges, Departments, and Programs.
- Sanctum API Admin success and Staff `403` response, with tokens redacted.
- A fresh test summary and current production-build output.

Do not treat a generated mockup, a historical terminal capture, or a route list
as proof that the corresponding application page or API exchange was running.