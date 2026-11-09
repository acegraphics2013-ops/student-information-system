# Database export

`schema-only.sql` is an SQLite DDL export generated from the existing local
SIMS database. It includes table and index definitions and deliberately
contains no user, student, subject, enrollment, token, or other row data.

Import into an empty SQLite database for a schema reference or use the Laravel
migrations in `database/migrations` for normal setup. This export is not a
backup of local records and must not be used to replace an existing database.
