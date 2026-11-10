# Personal Reflection Report — Student Information Management System (SIMS)

**Northeastern Mindanao State University (NEMSU) — Tandag Campus**  
**School Year:** 2026–2027  
**Document status:** **DRAFT — student-authored answers required before PDF submission**  
**Source snapshot:** `student-information-system(7).zip`, inspected November 10, 2026

| Student information | Value |
|---|---|
| Student name | **[STUDENT INPUT REQUIRED]** |
| Degree program / section | **[STUDENT INPUT REQUIRED]** |
| Course / subject | **[STUDENT INPUT REQUIRED]** |
| Instructor | **[STUDENT INPUT REQUIRED]** |
| Date submitted | **[STUDENT INPUT REQUIRED]** |

> **Academic integrity:** This document is an evidence-based framework, **not** a completed first-person personal reflection. Sections titled *Evidence from project* summarize source-code or documented history. Sections titled *My reflection* require your actual experiences, judgments, and words. Do not submit the file with these placeholders as a finished reflection.

## 1. Project background and my contribution

### Evidence from the project

The Student Information Management System (SIMS) is a Laravel 12 application developed for a student academic project. It includes Laravel Breeze authentication, Administrator and Staff roles, Student/Subject/Enrollment CRUD modules, a Student REST API authenticated with Laravel Sanctum, an academic catalog organized by college/department/degree program, and an Administration Dashboard. It uses Blade, Tailwind CSS, Alpine.js, and SQLite. This summary describes the inspected application, **not** the student's individual contribution.

### My reflection — student to complete

**[STUDENT INPUT REQUIRED — 150–250 words]** Describe your own responsibilities, which features you personally worked on, the purpose of your project, and how the final application differed from your original plan. Only describe actions you genuinely performed.

## 2. AI tools used and what worked

### Evidence from the project

The AI Portfolio records an AI-assisted implementation and debugging workflow in VS Code and the use of task-specific prompts for Laravel code, tests, navigation, and documentation. This conversation also includes ChatGPT-assisted planning, reviews, and prompt drafting. The precise model/version and token accounting were not captured in the existing Portfolio. No independently executed specialist-agent workflow was documented.

The project contains evidence of a successful debugging cycle: a route-name collision between web and Student API routes was detected by tests and route inspection, then corrected by using the `api.` prefix for API route names. Tests also surfaced an assumption that a `Student::factory()` method existed; explicit model creation replaced that assumption.

### My reflection — student to complete

**[STUDENT INPUT REQUIRED — 150–250 words]** Which AI tools did you actually use? What tasks did they help you finish? What suggestions worked well? Which generated outputs did you check before accepting? If you know a model name or version, give only the one you can verify from your own history/settings.

## 3. My prompt engineering strategy

### Evidence from the project

The Portfolio contains examples of a PHP namespace-error prompt, navigation instructions, existing-project preservation constraints, Student API implementation, validation and testing prompts, and an academic-catalog task. Across these cases, the requests became more specific about technologies, file scope, permissions, data preservation, and required tests.

The prompts repeatedly emphasized that SIMS was an **existing** Laravel project, that Breeze and existing CRUD modules should be retained, and that database resets or fabricated test results were unacceptable. These constraints are documented examples of deliberate prompting; they do not establish the student's personal opinion about their effectiveness.

### My reflection — student to complete

**[STUDENT INPUT REQUIRED — 150–250 words]** Choose one real initial prompt and its refinement. Explain why you revised it, what happened after the revision, and how you personally judged the result. Refer to genuine prompt captures in `AI_PORTFOLIO.pdf` where available.

## 4. My agent strategy and human oversight

### Evidence from the project

The Agent Design describes a **proposed** division of responsibilities among Planner, Database, Laravel Development, Testing/Review, and Documentation agents. Its actual-workflow section states that the recorded development was carried out through a **single assistant workflow with sequential phases**, not independently executing specialist agents. The proposed multi-agent diagram is a design artifact, not execution evidence.

### My reflection — student to complete

**[STUDENT INPUT REQUIRED — 100–200 words]** Explain how you actually organized AI assistance and human review. If you used one AI assistant for different tasks, say so. What would be the benefit—and the limitation—of a real multi-agent workflow? Do not claim independent agents ran unless you have genuine logs.

## 5. Challenges, failures, and AI mistakes

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

## 6. Human corrections and verification before accepting AI output

### Evidence from the project

The Version 7 audit counted **93 PHPUnit test methods** in source files. A separate Windows/Laragon testing record reports **93 tests passed with 633 assertions**. The independent audit did **not** rerun the full PHPUnit suite because the review environment lacked compatible PHP/SQLite and Node dependencies. The local verification document also reports a successful frontend build with a Vite warning about `/images/bg-login.jpg` and an unsuccessful repository-wide Pint check due to formatting differences in 11 existing PHP files.

The Version 7 archive passed independent read-only SQLite integrity and foreign-key checks. Its schema-only SQL export was successfully imported into an in-memory database. These are meaningful technical checks but are not substitutes for a new end-to-end local test run.

### My reflection — student to complete

**[STUDENT INPUT REQUIRED — 100–200 words]** What checks did *you* personally run? How did you decide an AI-generated change was safe enough to keep? Describe a particular test, browser check, route inspection, or Git diff you remember using.

## 7. Git and version control

### Evidence from the project

The independent inspection of **Version 7** found **41 commits** in the archived Git history, compared with 30 reported by an earlier reflection draft. History includes milestones in Student CRUD, Subject CRUD, Enrollment CRUD, the Student API, tests, and documentation. A commit count alone does not prove commit quality or the student's personal review process.

The Version 7 audit also found uncommitted file differences in the archived working tree, so the final local `git status` should be checked before submission.

### My reflection — student to complete

**[STUDENT INPUT REQUIRED — 120–200 words]** Explain a genuine example of using Git to track or review changes. Which commit or diff mattered to you? How did you decide when to commit? If Git never helped you recover from a failure, say that rather than inventing a story.

## 8. Lessons learned and engineering judgment

### My reflection — student to complete

**[STUDENT INPUT REQUIRED — 150–250 words]** Explain what you learned about Laravel MVC, database relationships, authentication/authorization, REST APIs, automated testing, prompt engineering, and responsible use of AI. Focus on two or three personal insights and examples rather than listing technologies.

## 9. Required context and token-management reflection questions

These **four questions appear in the Final Project Handbook**. Draft notes below are source-derived guidance; rewrite the answers in your own voice where the instructor expects personal reflection.

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

## 10. What remains and what I would improve

### Evidence-based remaining work at the Version 7 snapshot

- The required three final PDFs were absent from the inspected ZIP.
- There were 17 JPG screenshots in the archive, but not all needed application, API, and authorization views were fully captioned or integrated into the AI Portfolio.
- Repository-wide Pint formatting and the Vite login-background image warning required attention.
- A clean source-only submission ZIP had not yet been prepared.

### My reflection — student to complete

**[STUDENT INPUT REQUIRED — 100–150 words]** Which unresolved tasks do you consider most important? What would you prioritize if you had more development time? How would you improve your use of AI and your review process?

## 11. Personal conclusion

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
- [ ] Exported, opened, and visually checked as `REFLECTION.pdf`.

**Current status:** Draft for student completion. **Do not label or submit as final until the personal sections are genuinely completed.**
