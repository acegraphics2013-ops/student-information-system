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
        $student = Student::create($this->studentData([
            'student_number' => 'SIMS-1002',
            'email' => 'staff.read@example.com',
        ]));

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
        $existing = Student::create($this->studentData([
            'student_number' => 'SIMS-1002',
            'email' => 'existing@example.com',
        ]));

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

        $this->from(route('students.create'))
            ->post(route('students.store'), $this->studentData([
                'student_number' => 'SIMS-1003',
                'email' => $existing->email,
            ]))
            ->assertRedirect(route('students.create'))
            ->assertSessionHasErrors('email');

        $this->from(route('students.create'))
            ->post(route('students.store'), $this->studentData([
                'student_number' => str_repeat('S', 31),
                'email' => 'too.long.number@example.com',
            ]))
            ->assertRedirect(route('students.create'))
            ->assertSessionHasErrors('student_number');

        $this->from(route('students.create'))
            ->post(route('students.store'), $this->studentData([
                'student_number' => '',
            ]))
            ->assertRedirect(route('students.create'))
            ->assertSessionHasErrors('student_number');
    }

    public function test_student_updates_cannot_reuse_another_records_number_or_email(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));
        $first = Student::create($this->studentData([
            'student_number' => 'SIMS-1002',
            'email' => 'first.student@example.com',
        ]));
        $second = Student::create($this->studentData([
            'student_number' => 'SIMS-1003',
            'email' => 'second.student@example.com',
        ]));

        $this->from(route('students.edit', $second))
            ->put(route('students.update', $second), $this->studentData([
                'student_number' => $first->student_number,
                'email' => $first->email,
            ]))
            ->assertRedirect(route('students.edit', $second))
            ->assertSessionHasErrors(['student_number', 'email']);

        $this->assertDatabaseHas('students', [
            'id' => $second->id,
            'student_number' => 'SIMS-1003',
            'email' => 'second.student@example.com',
        ]);

    }

    public function test_missing_student_pages_return_not_found(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));

        $this->get('/students/999999')->assertNotFound();
        $this->get('/students/999999/edit')->assertNotFound();
        $this->put('/students/999999', $this->studentData())
            ->assertNotFound();
        $this->delete('/students/999999')->assertNotFound();
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
