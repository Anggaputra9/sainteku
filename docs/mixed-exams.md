# QUIZ and Mixed Exam Packages

## Data and Grading

- `exam_type` accepts `UTS`, `UAS`, and `QUIZ`. Question type is independent of exam type.
- Each question is `essay` or `multiple_choice`. MC has two to five nonempty options keyed A-E and one `correct_option`.
- Existing questions default to `essay`; existing answer text, scores, and grading metadata are not rewritten by the migrations.
- MC selections use nullable `selected_option`. Null means unanswered. Essay answers continue using `answer_text`.
- On submission, MC receives its full question weight for a correct selection and zero otherwise, including unanswered questions. No AI configuration is required.
- Manual and AI grading skip MC. Single-answer AI requests for MC are rejected. Mixed-package totals stay null until all questions have scores, so pending essays remain eligible for batch grading.
- The student work payload includes options but never the correct option. Authoring bank access requires create or update permission in module 3.
- Packages referenced by an exam room cannot be edited, because the previous edit implementation replaced question IDs. Create a new package instead.

## Local Verification

Run `vendor/bin/phpunit tests/Unit/QuestionValidationTest.php tests/Unit/MixedExamTest.php` with Composer dependencies installed and `pdo_sqlite` enabled. Tests use isolated in-memory tables, not the configured application database.

Run `node --test tests/exam-work.test.mjs` for dependency-free tests of the actual student-work JavaScript: mixed payloads, save ordering, clearing a selection, submit flushing, and offline retry.

Apply both new module migrations before serving the changed code. The migration widens the existing exam and grading enums to strings; validation restricts their accepted values. Rollback drops the new type/choice fields and therefore loses MC metadata. It deliberately does not narrow strings back to enums, which would invalidate persisted QUIZ or automatic-grade values. Back up before migration or rollback.

The routed authoring UI is the Tashih workspace modal; legacy standalone create/edit views are redirected to that workspace. Expiry finalization remains request-driven, including the student's finished-page request. This change does not introduce a scheduler for abandoned sessions.

## Alternate AI Service Path

`AiGradingService::gradeExamAttempt()` rejects missing or unfinished attempts before querying answers or grading. It grades MC automatically, sends only ungraded, answered essays belonging to the package to AI, and returns `total_score` from the persisted weighted recalculation. This includes existing grades and MC scores; pending essays yield null, including when no new essay is graded. Unanswered essays remain pending in this service path. `graded_count` counts only essays newly graded by AI. The unused raw-percentage `average_score` response field was removed; no repository callers of this method were found.

`tests/Unit/AiGradingAttemptTest.php` uses isolated mocks without SQLite to check the guard, essay selection, weighted conversion, metadata, AI failure, and no-op totals. Database-backed grading and migration tests still require SQLite. Cross-tab save ordering, room-close/new-attempt races, reset/in-flight AI races, and scheduling abandoned-attempt finalization remain outside this change.
