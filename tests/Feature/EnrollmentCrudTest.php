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

    public function test_admin_can_create_update_and_delete_an_enrollment(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $student = $this->sampleStudent();
        $subject = $this->sampleSubject();
        $this->actingAs($admin);

        $this->post(route('enrollments.store'), [
            'student_id' => $student->id,
            'subject_id' => $subject->id,
            'status' => 'enrolled',
        ])->assertRedirect(route('enrollments.index'));

        $enrollment = Enrollment::firstOrFail();
        $this->get(route('enrollments.show', $enrollment))->assertOk();

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
        $this->delete(route('enrollments.destroy', $enrollment))->assertForbidden();
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

    public function test_invalid_foreign_keys_and_status_are_rejected(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->post(route('enrollments.store'), [
            'student_id' => 999999,
            'subject_id' => 999999,
            'status' => 'invalid',
        ])->assertSessionHasErrors(['student_id', 'subject_id', 'status']);
    }
}
