# Personal Reflection Report — Student Information Management System (SIMS)

**Northeastern Mindanao State University (NEMSU) — Tandag Campus**  
**School Year:** 2026–2027  
**Document status:** **DRAFT — student-authored answers required before PDF submission**  
**Source snapshot:** Current workspace, verified November 10, 2026

| Student information | Value |
|---|---|
| Student name | **[STUDENT INPUT REQUIRED]** |
| Degree program / section | **[STUDENT INPUT REQUIRED]** |
| Course / subject | **[STUDENT INPUT REQUIRED]** |
| Instructor | **[STUDENT INPUT REQUIRED]** |
| Date submitted | **[STUDENT INPUT REQUIRED]** |

> **Academic integrity:** This document is an evidence-based framework, **not** a completed first-person personal reflection. Sections titled *Evidence from project* summarize source-code or documented history. Sections titled *My reflection* require your actual experiences, judgments, and words. Do not submit the file with these placeholders as a finished reflection.

## 1. Project development experience

### Evidence from the project

The Student Information Management System (SIMS) is a Laravel 12 application developed for a student academic project. It includes Laravel Breeze authentication, Administrator and Staff roles, Student/Subject/Enrollment CRUD modules, a Student REST API authenticated with Laravel Sanctum, an academic catalog organized by college/department/degree program, and an Administration Dashboard. It uses Blade, Tailwind CSS, Alpine.js, and SQLite. This summary describes the inspected application, **not** the student's individual contribution.

### My reflection — student to complete

**[STUDENT INPUT REQUIRED — 150–250 words]** Describe your own responsibilities, which features you personally worked on, the purpose of your project, and how the final application differed from your original plan. Only describe actions you genuinely performed.

## 2. Experience using AI

### Evidence from the project

The AI Portfolio records an AI-assisted implementation and debugging workflow in VS Code and task-specific prompts for Laravel code, tests, navigation, and documentation. GitHub Copilot in VS Code is the assistant evidenced by the current workflow; the precise model/version and token accounting were not captured. Historical screenshots show other AI interfaces, but do not by themselves prove their use. No independently executed specialist-agent workflow is documented.

The project contains evidence of a successful debugging cycle: a route-name collision between web and Student API routes was detected by tests and route inspection, then corrected by using the `api.` prefix for API route names. Tests also surfaced an assumption that a `Student::factory()` method existed; explicit model creation replaced that assumption.

### My reflection — student to complete

**[STUDENT INPUT REQUIRED — 150–250 words]** Which AI tools did you actually use? What tasks did they help you finish? What suggestions worked well? Which generated outputs did you check before accepting? If you know a model name or version, give only the one you can verify from your own history/settings.

## 3. Prompt engineering

### Evidence from the project

The Portfolio contains examples of a PHP namespace-error prompt, navigation instructions, existing-project preservation constraints, Student API implementation, validation and testing prompts, and an academic-catalog task. Across these cases, the requests became more specific about technologies, file scope, permissions, data preservation, and required tests.

The prompts repeatedly emphasized that SIMS was an **existing** Laravel project, that Breeze and existing CRUD modules should be retained, and that database resets or fabricated test results were unacceptable. These constraints are documented examples of deliberate prompting; they do not establish the student's personal opinion about their effectiveness.

### My reflection — student to complete

**[STUDENT INPUT REQUIRED — 150–250 words]** Choose one real initial prompt and its refinement. Explain why you revised it, what happened after the revision, and how you personally judged the result. Refer to genuine prompt captures in `AI_PORTFOLIO.pdf` where available.

## 4. AI agent strategy

### Evidence from the project

The Agent Design describes a **proposed** division of responsibilities among Planner, Database, Laravel Development, Testing/Review, and Documentation agents. Its actual-workflow section states that the recorded development was carried out through a **single assistant workflow with sequential phases**, not independently executing specialist agents. The proposed multi-agent diagram is a design artifact, not execution evidence.

### My reflection — student to complete

**[STUDENT INPUT REQUIRED — 100–200 words]** Explain how you actually organized AI assistance and human review. If you used one AI assistant for different tasks, say so. What would be the benefit—and the limitation—of a real multi-agent workflow? Do not claim independent agents ran unless you have genuine logs.

## 5. Challenges encountered

### Evidence from the project

The existing AI Portfolio describes several technical problems and subsequent corrections:

| Observed issue in documented development | Recorded correction or verification |
|---|---|
| API routes initially collided with Student web route names | Named API routes under `api.`; checked route list and web/API tests |
| A generated test assumed `Student::factory()` existed | Replaced the factory call with explicit Student record creation |
| A validation rule was written incorrectly as `sometimes\|required` | Used separate Laravel validator rule entries `['sometimes', 'required']` |
| A test asserted against an undefined `$existing` variable | Moved the assertion into the correct test scope |
| Schema-only SQL export had table/index ordering problems | Exported table definitions before indexes and tested SQLite import |
| An earlier role/API policy differed from the documentation | Later implementation aligned Student API writes with Administrator authorization |

These events come from project documentation. They should be used as personal reflection examples **only if you remember participating in or reviewing them**.

### My reflection — student to complete

**[STUDENT INPUT REQUIRED — 150–250 words]** Describe one or two problems you actually encountered. What failed? How did you diagnose the issue? Which suggestion was wrong or incomplete? What did you change yourself or verify? What did you learn from the failure?

## 6. Human verification of AI output

### Evidence from the project

The current Windows/Laragon run passed **93 tests with 633 assertions**, with no failures or skips. This task also inspected the current route list, migration status, SQLite integrity and foreign keys, Composer validation, Vite build, and repository-wide Pint result. The Vite build warned that `/images/bg-login.jpg` was not bundled; the referenced public file exists, but browser rendering was not tested. See `TESTING_AND_VERIFICATION.md` for exact current results.

The current SQLite integrity check returned `ok`, and the foreign-key check returned no violations. These checks do not replace manual browser review.

### My reflection — student to complete

**[STUDENT INPUT REQUIRED — 100–200 words]** What checks did *you* personally run? How did you decide an AI-generated change was safe enough to keep? Describe a particular test, browser check, route inspection, or Git diff you remember using.

## 7. Testing and debugging

### Evidence from the project

The fresh `php artisan test` run passed 93 tests and 633 assertions with no failures or skips. `php artisan route:list` showed 61 routes; all 13 migrations were applied. `npm run build`, `composer validate --no-check-publish`, and `vendor\bin\pint --test` passed. No destructive migration command was run. Existing screenshots show older test/build output and are not proof of this fresh run.

### My reflection — student to complete

**[STUDENT INPUT REQUIRED — 100–200 words]** Which test or debugging step did you personally run? What did its result tell you, and how did you decide whether the fix was correct? Distinguish your own checks from results recorded by another person or assistant.

## 8. Git and version control

### Evidence from the project

The current repository has **46 commits** at the November 10, 2026 verification point. History includes Student, Subject, Enrollment, API, test, and documentation milestones. A commit count alone does not prove commit quality or the student's personal review process.

Documentation changes from this audit are uncommitted. No new commit or push was made. Check `git status` again after the student completes their review.

### My reflection — student to complete

**[STUDENT INPUT REQUIRED — 120–200 words]** Explain a genuine example of using Git to track or review changes. Which commit or diff mattered to you? How did you decide when to commit? If Git never helped you recover from a failure, say that rather than inventing a story.

## 9. Ethical and responsible AI use

### Evidence from the project

Project notes prohibit invented test results, unsupported agent claims, fabricated screenshots, disclosure of credentials, and AI-written personal reflection. The submission ZIP excludes `.env`, the populated SQLite database, profile uploads, dependency folders, and Git internals. No model comparison or measured token savings are claimed.

### My reflection — student to complete

**[STUDENT INPUT REQUIRED — 100–150 words]** How did you disclose AI assistance, protect project and user data, and ensure the submitted work reflects your own learning? Describe one specific decision you personally made.

## 10. Skills learned

### My reflection — student to complete

**[STUDENT INPUT REQUIRED — 150–250 words]** Explain what you learned about Laravel MVC, database relationships, authentication/authorization, REST APIs, automated testing, prompt engineering, and responsible use of AI. Focus on two or three personal insights and examples rather than listing technologies.

## 11. Token and context management questions

Four context-management questions were retained in previous project notes, but the official handbook PDF was unavailable to verify their wording. Compare them with the actual handbook before submitting; answer only from your own experience.

### Q1. How did you reduce unnecessary AI context?

**Project evidence:** Existing documentation describes supplying only relevant controller, route, request, migration, and test files for each feature, rather than the entire project for every question.

**My answer:** **[STUDENT INPUT REQUIRED — identify an example you actually used.]**

### Q2. How did you avoid prompt repetition?

**Project evidence:** The development record describes reusing existing constraints and a conversation summary while narrowing subsequent prompts to specific fixes or modules.

**My answer:** **[STUDENT INPUT REQUIRED — explain your actual prompt workflow.]**

### Q3. How did you verify generated outputs?

**Project evidence:** Documented checks include route inspection, PHPUnit, database integrity, schema import, frontend build, and formatting checks; several generated-code defects were detected and corrected.

**My answer:** **[STUDENT INPUT REQUIRED — name tests and checks you personally verified.]**

### Q4. How did you balance token cost and quality?

**Project evidence:** The Agent Design describes focused prompts, task decomposition, incremental code changes, and targeted testing. **No measured token-use or cost data** are available, so numerical savings must not be claimed.

**My answer:** **[STUDENT INPUT REQUIRED — discuss your own trade-offs without inventing token measurements.]**

## 12. Personal conclusion

### Current remaining work

- The three PDFs in `submission/` are drafts; this Reflection still contains student-input placeholders.
- The 20 indexed screenshots show development activity but no running SIMS page or API-client exchange.
- The handbook/rubric PDFs were not available for direct comparison.
- The submission ZIP must be inspected after creation and remains a draft while personal answers or required visual evidence are missing.

**[STUDENT INPUT REQUIRED — 120–180 words]** Write your final first-person summary of how the project influenced your software engineering skills, your confidence in reviewing AI-generated code, and your approach to future development.

---

## Final evidence and submission checklist

- [ ] Student name, course/section, instructor, and submission date completed.
- [ ] All **My reflection** answers are written in the student's own words.
- [ ] Actual AI tools/models identified without guessing.
- [ ] Git commit count verified again on the final local project.
- [ ] Original prompt and test screenshots included or cross-referenced.
- [ ] No false claims about independent agents, tests, or personal experiences.
- [ ] No `[STUDENT INPUT REQUIRED]` placeholders remain in the document marked FINAL.
- [ ] Compare the context questions with the official handbook and answer any required questions.
- [ ] Review the DRAFT export at `submission/REFLECTION.pdf`; do not submit it as final while placeholders remain.

**Current status:** Draft for student completion. **Do not label or submit as final until the personal sections are genuinely completed.**
