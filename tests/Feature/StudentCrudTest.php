<?php

namespace Tests\Feature;

use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_view_update_and_delete_students(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));

        $this->get(route('students.index'))->assertOk();

        $this->post(route('students.store'), $this->studentData())
            ->assertRedirect(route('students.index'));

        $student = Student::query()->firstOrFail();

        $this->get(route('students.show', $student))->assertOk();
        $this->get(route('students.edit', $student))->assertOk();

        $this->put(route('students.update', $student), $this->studentData([
            'first_name' => 'Updated',
            'email' => 'updated@example.com',
        ]))->assertRedirect(route('students.index'));

        $this->assertDatabaseHas('students', [
            'id' => $student->id,
            'first_name' => 'Updated',
            'email' => 'updated@example.com',
        ]);

        $this->delete(route('students.destroy', $student))
            ->assertRedirect(route('students.index'));

        $this->assertDatabaseMissing('students', ['id' => $student->id]);
    }

    public function test_staff_can_read_students_but_cannot_modify_them(): void
    {
        $student = Student::factory()->create();

        $this->actingAs(User::factory()->create(['role' => 'staff']));

        $this->get(route('students.index'))->assertOk();
        $this->get(route('students.show', $student))->assertOk();

        $this->get(route('students.create'))->assertForbidden();
        $this->get(route('students.edit', $student))->assertForbidden();
        $this->post(route('students.store'), $this->studentData())
            ->assertForbidden();
        $this->put(route('students.update', $student), $this->studentData())
            ->assertForbidden();
        $this->delete(route('students.destroy', $student))->assertForbidden();
    }

    public function test_student_creation_validates_required_unique_and_bounded_fields(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));
        $existing = Student::factory()->create();

        $this->from(route('students.create'))
            ->post(route('students.store'), [
                'student_number' => $existing->student_number,
                'first_name' => str_repeat('A', 101),
                'last_name' => '',
                'email' => 'not-an-email',
                'course' => str_repeat('C', 151),
                'year_level' => 7,
            ])
            ->assertRedirect(route('students.create'))
            ->assertSessionHasErrors([
                'student_number',
                'first_name',
                'last_name',
                'email',
                'course',
                'year_level',
            ]);

        $this->assertDatabaseCount('students', 1);
    }

    /**
     * @return array<string, mixed>
     */
    private function studentData(array $overrides = []): array
    {
        return array_merge([
            'student_number' => 'SIMS-1001',
            'first_name' => 'Alex',
            'last_name' => 'Rivera',
            'email' => 'alex.rivera@example.com',
            'course' => 'Computer Science',
            'year_level' => 2,
        ], $overrides);
    }
}
