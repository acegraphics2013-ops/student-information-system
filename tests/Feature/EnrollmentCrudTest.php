<?php

namespace Tests\Feature;

use App\Models\Enrollment;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class EnrollmentCrudTest extends TestCase
{
    use RefreshDatabase;

    private function sampleStudent(): Student
    {
        return Student::create([
            'student_number' => '2026-001',
            'first_name' => 'Maria',
            'last_name' => 'Santos',
            'email' => 'maria@example.com',
            'course' => 'BSIT',
            'year_level' => 2,
        ]);
    }

    private function sampleSubject(): Subject
    {
        return Subject::create([
            'subject_code' => 'IT101',
            'subject_name' => 'Introduction to Computing',
            'units' => 3,
        ]);
    }

    public function test_admin_enrollment_form_shows_save_button_when_options_exist(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));
        $this->sampleStudent();
        $this->sampleSubject();

        $this->get(route('enrollments.create'))
            ->assertOk()
            ->assertSee('Search by student number, name, or email.')
            ->assertSee('Choose subjects')
            ->assertSee('Select multiple subjects, up to 10 units per semester.')
            ->assertSee('Enroll in Selected Subjects')
            ->assertSee('type="submit"', false);
    }

    public function test_enrollment_form_notifies_when_student_already_has_subjects_loaded(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $student = $this->sampleStudent();
        $subject = $this->sampleSubject();
        Enrollment::create([
            'student_id' => $student->id,
            'subject_id' => $subject->id,
            'status' => 'enrolled',
            'semester' => '1st Semester',
        ]);

        $this->actingAs($admin)
            ->get(route('enrollments.create'))
            ->assertOk()
            ->assertSee('loaded on their record')
            ->assertSee('cannot be enrolled again')
            ->assertSee('Already enrolled — cannot be enrolled again');
    }

    public function test_enrollment_semester_is_saved_displayed_and_validated(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $student = $this->sampleStudent();
        $subject = $this->sampleSubject();
        $this->actingAs($admin);

        $this->get(route('enrollments.create'))
            ->assertOk()
            ->assertSee('1st Semester')
            ->assertSee('2nd Semester')
            ->assertSee('Summer Semester');

        $this->post(route('enrollments.store'), [
            'student_id' => $student->id,
            'subject_ids' => [$subject->id],
            'status' => 'enrolled',
            'semester' => 'Summer Semester',
        ])->assertRedirect(route('enrollments.index'));

        $enrollment = Enrollment::firstOrFail();
        $this->assertSame('Summer Semester', $enrollment->semester);

        $this->get(route('enrollments.index'))
            ->assertOk()
            ->assertSee('Summer Semester')
            ->assertSee('1 semester')
            ->assertSee('Semesters Enrolled');

        $this->get(route('enrollments.show', $enrollment))
            ->assertOk()
            ->assertSee('Summer Semester')
            ->assertSee('Semesters enrolled')
            ->assertSee('1 semester');

        $this->get(route('enrollments.edit', $enrollment))
            ->assertOk()
            ->assertSee('Summer Semester');

        $this->put(route('enrollments.update', $enrollment), [
            'student_id' => $student->id,
            'subject_id' => $subject->id,
            'status' => 'enrolled',
            'semester' => '2nd Semester',
        ])->assertRedirect(route('enrollments.index'));

        $this->assertDatabaseHas('enrollments', [
            'id' => $enrollment->id,
            'semester' => '2nd Semester',
        ]);
    }

    public function test_invalid_semester_is_rejected(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $student = $this->sampleStudent();
        $subject = $this->sampleSubject();

        $this->actingAs($admin)->post(route('enrollments.store'), [
            'student_id' => $student->id,
            'subject_ids' => [$subject->id],
            'status' => 'enrolled',
            'semester' => 'Invalid Semester',
        ])->assertSessionHasErrors('semester');

        $this->assertDatabaseCount('enrollments', 0);
    }

    public function test_enrollment_year_level_is_saved_displayed_and_validated(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $student = $this->sampleStudent();
        $subject = $this->sampleSubject();
        $this->actingAs($admin);

        $this->get(route('enrollments.create'))
            ->assertOk()
            ->assertSee('Year Level')
            ->assertSee('Year 1')
            ->assertSee('Year 5');

        $this->post(route('enrollments.store'), [
            'student_id' => $student->id,
            'subject_ids' => [$subject->id],
            'status' => 'enrolled',
            'semester' => '1st Semester',
            'year_level' => 3,
        ])->assertRedirect(route('enrollments.index'));

        $enrollment = Enrollment::firstOrFail();
        $this->assertSame(3, (int) $enrollment->year_level);

        $this->get(route('enrollments.edit', $enrollment))
            ->assertOk()
            ->assertSee('Year Level');

        $this->get(route('enrollments.show', $enrollment))
            ->assertOk()
            ->assertSee('3rd Year');

        $this->put(route('enrollments.update', $enrollment), [
            'student_id' => $student->id,
            'subject_id' => $subject->id,
            'status' => 'enrolled',
            'semester' => '1st Semester',
            'year_level' => 4,
        ])->assertRedirect(route('enrollments.index'));

        $this->assertDatabaseHas('enrollments', [
            'id' => $enrollment->id,
            'year_level' => 4,
        ]);
    }

    public function test_invalid_enrollment_year_level_is_rejected(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $student = $this->sampleStudent();
        $subject = $this->sampleSubject();

        $this->actingAs($admin)->post(route('enrollments.store'), [
            'student_id' => $student->id,
            'subject_ids' => [$subject->id],
            'status' => 'enrolled',
            'semester' => '1st Semester',
            'year_level' => 9,
        ])->assertSessionHasErrors('year_level');

        $this->assertDatabaseCount('enrollments', 0);
    }

    public function test_enrollment_year_level_falls_back_to_student_year_when_omitted(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $student = $this->sampleStudent();
        $subject = $this->sampleSubject();

        $this->actingAs($admin)->post(route('enrollments.store'), [
            'student_id' => $student->id,
            'subject_ids' => [$subject->id],
            'status' => 'enrolled',
            'semester' => '1st Semester',
        ])->assertRedirect(route('enrollments.index'));

        $this->assertSame(2, (int) Enrollment::firstOrFail()->year_level);
    }

    public function test_enrollment_list_counts_distinct_semesters_per_student(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $student = $this->sampleStudent();
        $firstSubject = Subject::create([
            'subject_code' => 'IT101',
            'subject_name' => 'Introduction to Computing',
            'units' => 3,
        ]);
        $secondSubject = Subject::create([
            'subject_code' => 'IT102',
            'subject_name' => 'Programming 1',
            'units' => 3,
        ]);

        Enrollment::create([
            'student_id' => $student->id,
            'subject_id' => $firstSubject->id,
            'status' => 'enrolled',
            'semester' => '1st Semester',
        ]);
        Enrollment::create([
            'student_id' => $student->id,
            'subject_id' => $secondSubject->id,
            'status' => 'enrolled',
            'semester' => '2nd Semester',
        ]);

        $this->actingAs($admin)
            ->get(route('enrollments.index'))
            ->assertOk()
            ->assertSee('2 semesters')
            ->assertSee('1st Semester')
            ->assertSee('2nd Semester');

        $this->actingAs($admin)
            ->get(route('enrollments.show', $student->enrollments->first()))
            ->assertOk()
            ->assertSee('2 semesters');
    }

    public function test_enrollment_list_shows_units_per_semester_not_a_cross_semester_total(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $student = $this->sampleStudent();
        $firstSemesterSubject = Subject::create([
            'subject_code' => 'IT101',
            'subject_name' => 'Introduction to Computing',
            'units' => 6,
        ]);
        $secondSemesterSubject = Subject::create([
            'subject_code' => 'IT102',
            'subject_name' => 'Programming',
            'units' => 6,
        ]);
        Enrollment::create([
            'student_id' => $student->id,
            'subject_id' => $firstSemesterSubject->id,
            'status' => 'enrolled',
            'semester' => '1st Semester',
        ]);
        Enrollment::create([
            'student_id' => $student->id,
            'subject_id' => $secondSemesterSubject->id,
            'status' => 'enrolled',
            'semester' => '2nd Semester',
        ]);

        $this->actingAs($admin)
            ->get(route('enrollments.index'))
            ->assertOk()
            ->assertSee('Enrolled Units (per semester)')
            ->assertSee('1st Semester:')
            ->assertSee('2nd Semester:')
            // The cap is 10 per semester, so each semester shows 6 — never a 12 total.
            ->assertSee('<span class="font-semibold text-gray-900">6</span>', false)
            ->assertDontSee('<span class="font-semibold text-gray-900">12</span>', false);
    }

    public function test_admin_can_create_update_and_delete_an_enrollment(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $student = $this->sampleStudent();
        $subject = $this->sampleSubject();
        $this->actingAs($admin);
        $this->get(route('enrollments.index'))
            ->assertOk()
            ->assertSee('Search enrollments')
            ->assertSee('Enrollment records');

        $this->post(route('enrollments.store'), [
            'student_id' => $student->id,
            'subject_ids' => [$subject->id],
            'status' => 'enrolled',
            'semester' => '1st Semester',
        ])->assertRedirect(route('enrollments.index'));

        $enrollment = Enrollment::firstOrFail();
        $this->get(route('enrollments.index'))
            ->assertOk()
            ->assertSee('View subjects');
        $this->get(route('enrollments.show', $enrollment))
            ->assertOk()
            ->assertSee('All Subjects Loaded')
            ->assertSee('Introduction to Computing');
        $this->get(route('enrollments.edit', $enrollment))->assertOk();

        $this->put(route('enrollments.update', $enrollment), [
            'student_id' => $student->id,
            'subject_id' => $subject->id,
            'status' => 'completed',
            'semester' => '1st Semester',
        ])->assertRedirect(route('enrollments.index'));

        $this->assertDatabaseHas('enrollments', [
            'id' => $enrollment->id,
            'status' => 'completed',
            'semester' => '1st Semester',
        ]);

        $this->delete(route('enrollments.destroy', $enrollment))
            ->assertRedirect(route('enrollments.index'));

        $this->assertDatabaseMissing('enrollments', ['id' => $enrollment->id]);
    }

    public function test_staff_can_enroll_update_and_unload_subjects(): void
    {
        $staff = User::factory()->create(['role' => 'staff']);
        $student = $this->sampleStudent();
        $subject = $this->sampleSubject();
        $additionalSubject = Subject::create([
            'subject_code' => 'IT102',
            'subject_name' => 'Programming',
            'units' => 3,
        ]);
        $enrollment = Enrollment::create([
            'student_id' => $student->id,
            'subject_id' => $subject->id,
            'status' => 'enrolled',
            'semester' => '1st Semester',
        ]);

        $this->actingAs($staff);
        $this->get(route('enrollments.index'))
            ->assertOk()
            ->assertSee('Enroll student')
            ->assertSee('View subjects')
            ->assertDontSee('Unload');
        $this->get(route('enrollments.show', $enrollment))
            ->assertOk()
            ->assertSee('Unload')
            ->assertSee('Introduction to Computing');
        $this->get(route('enrollments.create'))->assertOk();
        $this->get(route('enrollments.edit', $enrollment))->assertOk();

        $this->post(route('enrollments.store'), [
            'student_id' => $student->id,
            'subject_ids' => [$additionalSubject->id],
            'status' => 'dropped',
            'semester' => '1st Semester',
        ])->assertRedirect(route('enrollments.index'));
        $addedEnrollment = Enrollment::query()
            ->where('status', 'dropped')
            ->firstOrFail();

        $this->put(route('enrollments.update', $enrollment), [
            'student_id' => $student->id,
            'subject_id' => $subject->id,
            'status' => 'dropped',
            'semester' => '1st Semester',
        ])->assertRedirect(route('enrollments.index'));
        $this->assertDatabaseHas('enrollments', [
            'id' => $enrollment->id,
            'status' => 'dropped',
            'semester' => '1st Semester',
        ]);

        $this->delete(route('enrollments.destroy', $addedEnrollment))
            ->assertRedirect(route('enrollments.index'));
        $this->assertDatabaseMissing('enrollments', ['id' => $addedEnrollment->id]);
    }

    public function test_enrollment_search_matches_student_subject_and_status(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $student = $this->sampleStudent();
        $subject = $this->sampleSubject();
        Enrollment::create([
            'student_id' => $student->id,
            'subject_id' => $subject->id,
            'status' => 'completed',
            'semester' => '1st Semester',
        ]);

        $this->actingAs($admin);

        foreach (['2026-001', 'Maria Santos', 'maria@example.com', 'IT101', 'Introduction to Computing', 'completed'] as $search) {
            $this->get(route('enrollments.index', ['search' => $search]))
                ->assertOk()
                ->assertViewHas('students', fn ($students): bool => $students->total() === 1
                    && $students->first()->student_number === '2026-001');
        }

        $this->get(route('enrollments.index', ['search' => 'no-such-record']))
            ->assertOk()
            ->assertSee('No matching students');
    }

    public function test_enrollment_list_shows_each_student_once_and_view_shows_all_subjects(): void
    {
        $student = $this->sampleStudent();
        $firstSubject = $this->sampleSubject();
        $secondSubject = Subject::create([
            'subject_code' => 'IT102',
            'subject_name' => 'Programming',
            'units' => 4,
        ]);
        Enrollment::create([
            'student_id' => $student->id,
            'subject_id' => $firstSubject->id,
            'status' => 'enrolled',
            'semester' => '1st Semester',
        ]);
        $secondEnrollment = Enrollment::create([
            'student_id' => $student->id,
            'subject_id' => $secondSubject->id,
            'status' => 'completed',
            'semester' => '1st Semester',
        ]);

        $this->actingAs(User::factory()->create(['role' => 'staff']))
            ->get(route('enrollments.index'))
            ->assertOk()
            ->assertViewHas('students', fn ($students): bool => $students->total() === 1
                && $students->first()->enrollments->count() === 2)
            ->assertSee('2026-001')
            ->assertSee('View subjects');

        $this->get(route('enrollments.show', $secondEnrollment))
            ->assertOk()
            ->assertSee('Introduction to Computing')
            ->assertSee('Programming')
            ->assertSee('All Subjects Loaded')
            ->assertSee(route('enrollments.create', ['student_id' => $student->id]), false);

        $this->get(route('enrollments.create', ['student_id' => $student->id]))
            ->assertOk()
            ->assertSee('Add Subjects to Student')
            ->assertViewHas('initialStudentId', (string) $student->id);
    }

    public function test_enrollment_search_rejects_overlong_search_terms(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']))
            ->from(route('enrollments.index'))
            ->get(route('enrollments.index', ['search' => str_repeat('x', 101)]))
            ->assertRedirect(route('enrollments.index'))
            ->assertSessionHasErrors('search');
    }

    public function test_duplicate_student_subject_enrollment_is_rejected(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $student = $this->sampleStudent();
        $subject = $this->sampleSubject();
        Enrollment::create([
            'student_id' => $student->id,
            'subject_id' => $subject->id,
            'status' => 'enrolled',
            'semester' => '1st Semester',
        ]);

        $this->actingAs($admin)->post(route('enrollments.store'), [
            'student_id' => $student->id,
            'subject_ids' => [$subject->id],
            'status' => 'enrolled',
            'semester' => '1st Semester',
        ])->assertSessionHasErrors('subject_ids');

        $this->assertDatabaseCount('enrollments', 1);
    }

    public function test_student_can_be_enrolled_in_multiple_subjects_up_to_ten_units(): void
    {
        $student = $this->sampleStudent();
        $firstSubject = $this->sampleSubject();
        $secondSubject = Subject::create([
            'subject_code' => 'IT102',
            'subject_name' => 'Programming',
            'units' => 4,
        ]);
        $thirdSubject = Subject::create([
            'subject_code' => 'IT103',
            'subject_name' => 'Data Fundamentals',
            'units' => 3,
        ]);

        $this->actingAs(User::factory()->create(['role' => 'staff']))
            ->post(route('enrollments.store'), [
                'student_id' => $student->id,
                'subject_ids' => [$firstSubject->id, $secondSubject->id, $thirdSubject->id],
                'status' => 'enrolled',
                'semester' => '1st Semester',
            ])
            ->assertRedirect(route('enrollments.index'));

        $this->assertDatabaseCount('enrollments', 3);
        $this->assertSame(10, (int) DB::table('enrollments')
            ->join('subjects', 'enrollments.subject_id', '=', 'subjects.id')
            ->where('enrollments.student_id', $student->id)
            ->where('enrollments.status', 'enrolled')
            ->sum('subjects.units'));
    }

    public function test_enrollment_cannot_exceed_ten_units_including_current_load(): void
    {
        $student = $this->sampleStudent();
        $existingSubject = $this->sampleSubject();
        $firstNewSubject = Subject::create([
            'subject_code' => 'IT102',
            'subject_name' => 'Programming',
            'units' => 4,
        ]);
        $secondNewSubject = Subject::create([
            'subject_code' => 'IT103',
            'subject_name' => 'Data Fundamentals',
            'units' => 4,
        ]);
        Enrollment::create([
            'student_id' => $student->id,
            'subject_id' => $existingSubject->id,
            'status' => 'enrolled',
            'semester' => '1st Semester',
        ]);

        $this->actingAs(User::factory()->create(['role' => 'staff']))
            ->post(route('enrollments.store'), [
                'student_id' => $student->id,
                'subject_ids' => [$firstNewSubject->id, $secondNewSubject->id],
                'status' => 'enrolled',
                'semester' => '1st Semester',
            ])
            ->assertSessionHasErrors('subject_ids');

        $this->assertDatabaseCount('enrollments', 1);
    }

    public function test_student_at_unit_cap_can_enroll_other_subjects_next_semester(): void
    {
        $student = $this->sampleStudent();
        $firstSemesterSubject = Subject::create([
            'subject_code' => 'IT101',
            'subject_name' => 'Introduction to Computing',
            'units' => 6,
        ]);
        $secondSemesterSubject = Subject::create([
            'subject_code' => 'IT102',
            'subject_name' => 'Programming',
            'units' => 6,
        ]);
        Enrollment::create([
            'student_id' => $student->id,
            'subject_id' => $firstSemesterSubject->id,
            'status' => 'enrolled',
            'semester' => '1st Semester',
        ]);

        $this->actingAs(User::factory()->create(['role' => 'staff']))
            ->post(route('enrollments.store'), [
                'student_id' => $student->id,
                'subject_ids' => [$secondSemesterSubject->id],
                'status' => 'enrolled',
                'semester' => '2nd Semester',
            ])
            ->assertRedirect(route('enrollments.index'));

        $this->assertDatabaseCount('enrollments', 2);
        $this->assertDatabaseHas('enrollments', [
            'student_id' => $student->id,
            'subject_id' => $secondSemesterSubject->id,
            'status' => 'enrolled',
            'semester' => '2nd Semester',
        ]);
    }

    public function test_unit_cap_still_applies_within_the_same_semester(): void
    {
        $student = $this->sampleStudent();
        $firstSubject = Subject::create([
            'subject_code' => 'IT101',
            'subject_name' => 'Introduction to Computing',
            'units' => 6,
        ]);
        $secondSubject = Subject::create([
            'subject_code' => 'IT102',
            'subject_name' => 'Programming',
            'units' => 6,
        ]);
        Enrollment::create([
            'student_id' => $student->id,
            'subject_id' => $firstSubject->id,
            'status' => 'enrolled',
            'semester' => '1st Semester',
        ]);

        $this->actingAs(User::factory()->create(['role' => 'staff']))
            ->post(route('enrollments.store'), [
                'student_id' => $student->id,
                'subject_ids' => [$secondSubject->id],
                'status' => 'enrolled',
                'semester' => '1st Semester',
            ])
            ->assertSessionHasErrors('subject_ids');

        $this->assertDatabaseCount('enrollments', 1);
    }

    public function test_updating_an_enrollment_cannot_exceed_ten_active_units(): void
    {
        $student = $this->sampleStudent();
        $eightUnitSubject = Subject::create([
            'subject_code' => 'IT108',
            'subject_name' => 'Advanced Computing',
            'units' => 8,
        ]);
        $threeUnitSubject = $this->sampleSubject();
        Enrollment::create([
            'student_id' => $student->id,
            'subject_id' => $eightUnitSubject->id,
            'status' => 'enrolled',
            'semester' => '1st Semester',
        ]);
        $enrollmentToActivate = Enrollment::create([
            'student_id' => $student->id,
            'subject_id' => $threeUnitSubject->id,
            'status' => 'dropped',
            'semester' => '1st Semester',
        ]);

        $this->actingAs(User::factory()->create(['role' => 'staff']))
            ->put(route('enrollments.update', $enrollmentToActivate), [
                'student_id' => $student->id,
                'subject_id' => $threeUnitSubject->id,
                'status' => 'enrolled',
                'semester' => '1st Semester',
            ])
            ->assertSessionHasErrors('subject_id');

        $this->assertDatabaseHas('enrollments', [
            'id' => $enrollmentToActivate->id,
            'status' => 'dropped',
            'semester' => '1st Semester',
        ]);
    }

    public function test_duplicate_student_subject_pair_is_rejected_on_update(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $student = $this->sampleStudent();
        $firstSubject = $this->sampleSubject();
        $secondSubject = Subject::create([
            'subject_code' => 'IT102',
            'subject_name' => 'Programming',
            'units' => 3,
        ]);
        Enrollment::create([
            'student_id' => $student->id,
            'subject_id' => $firstSubject->id,
            'status' => 'enrolled',
            'semester' => '1st Semester',
        ]);
        $enrollmentToUpdate = Enrollment::create([
            'student_id' => $student->id,
            'subject_id' => $secondSubject->id,
            'status' => 'enrolled',
            'semester' => '1st Semester',
        ]);

        $this->actingAs($admin)->put(
            route('enrollments.update', $enrollmentToUpdate),
            [
                'student_id' => $student->id,
                'subject_id' => $firstSubject->id,
                'status' => 'completed',
                'semester' => '1st Semester',
            ]
        )->assertSessionHasErrors('subject_id');

        $this->assertDatabaseHas('enrollments', [
            'id' => $enrollmentToUpdate->id,
            'subject_id' => $secondSubject->id,
            'status' => 'enrolled',
            'semester' => '1st Semester',
        ]);
    }

    public function test_invalid_foreign_keys_and_status_are_rejected(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->post(route('enrollments.store'), [
            'student_id' => 999999,
            'subject_ids' => [999999],
            'status' => 'invalid',
            'semester' => '1st Semester',
        ])->assertSessionHasErrors(['student_id', 'subject_ids.0', 'status']);
    }

    public function test_missing_enrollment_pages_return_not_found(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));

        $this->get('/enrollments/999999')->assertNotFound();
        $this->get('/enrollments/999999/edit')->assertNotFound();
        $this->put('/enrollments/999999', [
            'student_id' => 1,
            'subject_id' => 1,
            'status' => 'enrolled',
            'semester' => '1st Semester',
        ])->assertNotFound();
        $this->delete('/enrollments/999999')->assertNotFound();
    }
}
