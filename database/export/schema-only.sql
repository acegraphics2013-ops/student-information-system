-- SQLite schema-only export generated from the existing SIMS database.
-- No user, student, subject, enrollment, token, or other row data is included.
PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE "cache" ("key" varchar not null, "value" text not null, "expiration" integer not null, primary key ("key"));

CREATE TABLE "cache_locks" ("key" varchar not null, "owner" varchar not null, "expiration" integer not null, primary key ("key"));

CREATE TABLE "colleges" ("id" integer primary key autoincrement not null, "name" varchar not null, "abbreviation" varchar not null, "created_at" datetime, "updated_at" datetime);

CREATE TABLE "departments" ("id" integer primary key autoincrement not null, "college_id" integer not null, "name" varchar not null, "created_at" datetime, "updated_at" datetime, foreign key("college_id") references "colleges"("id") on delete cascade);

CREATE TABLE "enrollments" ("id" integer primary key autoincrement not null, "student_id" integer not null, "subject_id" integer not null, "status" varchar not null default 'enrolled', "created_at" datetime, "updated_at" datetime, foreign key("student_id") references "students"("id") on delete cascade, foreign key("subject_id") references "subjects"("id") on delete cascade);

CREATE TABLE "failed_jobs" ("id" integer primary key autoincrement not null, "uuid" varchar not null, "connection" text not null, "queue" text not null, "payload" text not null, "exception" text not null, "failed_at" datetime not null default CURRENT_TIMESTAMP);

CREATE TABLE "job_batches" ("id" varchar not null, "name" varchar not null, "total_jobs" integer not null, "pending_jobs" integer not null, "failed_jobs" integer not null, "failed_job_ids" text not null, "options" text, "cancelled_at" integer, "created_at" integer not null, "finished_at" integer, primary key ("id"));

CREATE TABLE "jobs" ("id" integer primary key autoincrement not null, "queue" varchar not null, "payload" text not null, "attempts" integer not null, "reserved_at" integer, "available_at" integer not null, "created_at" integer not null);

CREATE TABLE "migrations" ("id" integer primary key autoincrement not null, "migration" varchar not null, "batch" integer not null);

CREATE TABLE "password_reset_tokens" ("email" varchar not null, "token" varchar not null, "created_at" datetime, primary key ("email"));

CREATE TABLE "programs" ("id" integer primary key autoincrement not null, "department_id" integer not null, "name" varchar not null, "created_at" datetime, "updated_at" datetime, foreign key("department_id") references "departments"("id") on delete cascade);

CREATE TABLE "personal_access_tokens" ("id" integer primary key autoincrement not null, "tokenable_type" varchar not null, "tokenable_id" integer not null, "name" text not null, "token" varchar not null, "abilities" text, "last_used_at" datetime, "expires_at" datetime, "created_at" datetime, "updated_at" datetime);

CREATE TABLE "sessions" ("id" varchar not null, "user_id" integer, "ip_address" varchar, "user_agent" text, "payload" text not null, "last_activity" integer not null, primary key ("id"));

CREATE TABLE "students" ("id" integer primary key autoincrement not null, "student_number" varchar not null, "first_name" varchar not null, "last_name" varchar not null, "email" varchar not null, "course" varchar not null, "year_level" integer not null, "created_at" datetime, "updated_at" datetime);

CREATE TABLE "subjects" ("id" integer primary key autoincrement not null, "subject_code" varchar not null, "subject_name" varchar not null, "units" integer not null, "created_at" datetime, "updated_at" datetime);

CREATE TABLE "users" ("id" integer primary key autoincrement not null, "name" varchar not null, "email" varchar not null, "email_verified_at" datetime, "password" varchar not null, "remember_token" varchar, "created_at" datetime, "updated_at" datetime, "role" varchar not null default 'staff');

CREATE UNIQUE INDEX "enrollments_student_id_subject_id_unique" on "enrollments" ("student_id", "subject_id");

CREATE UNIQUE INDEX "colleges_abbreviation_unique" on "colleges" ("abbreviation");

CREATE UNIQUE INDEX "colleges_name_unique" on "colleges" ("name");

CREATE UNIQUE INDEX "departments_college_id_name_unique" on "departments" ("college_id", "name");

CREATE UNIQUE INDEX "failed_jobs_uuid_unique" on "failed_jobs" ("uuid");

CREATE INDEX "jobs_queue_index" on "jobs" ("queue");

CREATE INDEX "personal_access_tokens_expires_at_index" on "personal_access_tokens" ("expires_at");

CREATE UNIQUE INDEX "personal_access_tokens_token_unique" on "personal_access_tokens" ("token");

CREATE INDEX "personal_access_tokens_tokenable_type_tokenable_id_index" on "personal_access_tokens" ("tokenable_type", "tokenable_id");

CREATE UNIQUE INDEX "programs_department_id_name_unique" on "programs" ("department_id", "name");

CREATE INDEX "sessions_last_activity_index" on "sessions" ("last_activity");

CREATE INDEX "sessions_user_id_index" on "sessions" ("user_id");

CREATE UNIQUE INDEX "students_email_unique" on "students" ("email");

CREATE UNIQUE INDEX "students_student_number_unique" on "students" ("student_number");

CREATE UNIQUE INDEX "subjects_subject_code_unique" on "subjects" ("subject_code");

CREATE UNIQUE INDEX "users_email_unique" on "users" ("email");

COMMIT;
PRAGMA foreign_keys=ON;
