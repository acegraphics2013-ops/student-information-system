<?php

namespace Tests\Feature;

use App\Models\Enrollment;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
            ->assertSee('Save Enrollment')
            ->assertSee('type="submit"', false);
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
            'subject_id' => $subject->id,
            'status' => 'enrolled',
        ])->assertRedirect(route('enrollments.index'));

        $enrollment = Enrollment::firstOrFail();
        $this->get(route('enrollments.show', $enrollment))->assertOk();
        $this->get(route('enrollments.edit', $enrollment))->assertOk();

        $this->put(route('enrollments.update', $enrollment), [
            'student_id' => $student->id,
            'subject_id' => $subject->id,
            'status' => 'completed',
        ])->assertRedirect(route('enrollments.index'));

        $this->assertDatabaseHas('enrollments', [
            'id' => $enrollment->id,
            'status' => 'completed',
        ]);

        $this->delete(route('enrollments.destroy', $enrollment))
            ->assertRedirect(route('enrollments.index'));

        $this->assertDatabaseMissing('enrollments', ['id' => $enrollment->id]);
    }

    public function test_staff_can_view_but_cannot_change_enrollments(): void
    {
        $staff = User::factory()->create(['role' => 'staff']);
        $student = $this->sampleStudent();
        $subject = $this->sampleSubject();
        $enrollment = Enrollment::create([
            'student_id' => $student->id,
            'subject_id' => $subject->id,
            'status' => 'enrolled',
        ]);

        $this->actingAs($staff);
        $this->get(route('enrollments.index'))->assertOk();
        $this->get(route('enrollments.show', $enrollment))->assertOk();
        $this->get(route('enrollments.create'))->assertForbidden();
        $this->get(route('enrollments.edit', $enrollment))->assertForbidden();
        $this->post(route('enrollments.store'), [
            'student_id' => $student->id,
            'subject_id' => $subject->id,
            'status' => 'enrolled',
        ])->assertForbidden();
        $this->put(route('enrollments.update', $enrollment), [
            'student_id' => $student->id,
            'subject_id' => $subject->id,
            'status' => 'completed',
        ])->assertForbidden();
        $this->delete(route('enrollments.destroy', $enrollment))->assertForbidden();
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
        ]);

        $this->actingAs($admin);

        foreach (['2026-001', 'Maria Santos', 'maria@example.com', 'IT101', 'Introduction to Computing', 'completed'] as $search) {
            $this->get(route('enrollments.index', ['search' => $search]))
                ->assertOk()
                ->assertViewHas('enrollments', fn ($enrollments): bool => $enrollments->total() === 1
                    && $enrollments->first()->status === 'completed');
        }

        $this->get(route('enrollments.index', ['search' => 'no-such-record']))
            ->assertOk()
            ->assertSee('No matching enrollments');
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
        ]);

        $this->actingAs($admin)->post(route('enrollments.store'), [
            'student_id' => $student->id,
            'subject_id' => $subject->id,
            'status' => 'enrolled',
        ])->assertSessionHasErrors('subject_id');

        $this->assertDatabaseCount('enrollments', 1);
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
        ]);
        $enrollmentToUpdate = Enrollment::create([
            'student_id' => $student->id,
            'subject_id' => $secondSubject->id,
            'status' => 'enrolled',
        ]);

        $this->actingAs($admin)->put(
            route('enrollments.update', $enrollmentToUpdate),
            [
                'student_id' => $student->id,
                'subject_id' => $firstSubject->id,
                'status' => 'completed',
            ]
        )->assertSessionHasErrors('subject_id');

        $this->assertDatabaseHas('enrollments', [
            'id' => $enrollmentToUpdate->id,
            'subject_id' => $secondSubject->id,
            'status' => 'enrolled',
        ]);
    }

    public function test_invalid_foreign_keys_and_status_are_rejected(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->post(route('enrollments.store'), [
            'student_id' => 999999,
            'subject_id' => 999999,
            'status' => 'invalid',
        ])->assertSessionHasErrors(['student_id', 'subject_id', 'status']);
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
        ])->assertNotFound();
        $this->delete('/enrollments/999999')->assertNotFound();
    }
}
