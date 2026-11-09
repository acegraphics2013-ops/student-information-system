<?php

namespace Tests\Feature;

use App\Models\Program;
use App\Models\Student;
use App\Models\User;
use Database\Seeders\NemsuAcademicCatalogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(NemsuAcademicCatalogSeeder::class);
    }

    public function test_admin_can_create_view_update_and_delete_students(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));

        $this->get(route('students.index'))->assertOk();
        $this->get(route('students.create'))
            ->assertOk()
            ->assertSee('College')
            ->assertSee('Department')
            ->assertSee('Degree Program')
            ->assertSee('College of Information Technology Education');

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
        $student = Student::create($this->studentRecordData([
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
        $existing = Student::create($this->studentRecordData([
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
        $first = Student::create($this->studentRecordData([
            'student_number' => 'SIMS-1002',
            'email' => 'first.student@example.com',
        ]));
        $second = Student::create($this->studentRecordData([
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

    public function test_admin_cannot_select_a_program_outside_the_department_or_college(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));
        $data = $this->studentData();
        $data['department_id'] = Program::query()
            ->where('name', 'Bachelor of Science in Biology')
            ->value('department_id');

        $this->from(route('students.create'))
            ->post(route('students.store'), $data)
            ->assertRedirect(route('students.create'))
            ->assertSessionHasErrors(['department_id']);

        $this->assertDatabaseCount('students', 0);
    }

    public function test_existing_legacy_course_can_be_preserved_without_catalog_selection(): void
    {
        $student = Student::create([
            'student_number' => 'SIMS-1099',
            'first_name' => 'Legacy',
            'last_name' => 'Student',
            'email' => 'legacy.student@example.com',
            'course' => 'Legacy degree name',
            'year_level' => 1,
        ]);
        $data = $this->studentData([
            'student_number' => $student->student_number,
            'email' => $student->email,
            'course' => $student->course,
            'first_name' => 'Updated',
        ]);
        unset($data['college_id'], $data['department_id']);

        $this->actingAs(User::factory()->create(['role' => 'admin']))
            ->put(route('students.update', $student), $data)
            ->assertRedirect(route('students.index'));

        $this->assertDatabaseHas('students', [
            'id' => $student->id,
            'course' => 'Legacy degree name',
            'first_name' => 'Updated',
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
        $program = Program::query()
            ->where('name', 'Bachelor of Science in Computer Science')
            ->with('department')
            ->firstOrFail();

        return array_merge([
            'student_number' => 'SIMS-1001',
            'first_name' => 'Alex',
            'last_name' => 'Rivera',
            'email' => 'alex.rivera@example.com',
            'course' => $program->name,
            'year_level' => 2,
            'college_id' => $program->department->college_id,
            'department_id' => $program->department_id,
        ], $overrides);
    }

    /**
     * @return array<string, mixed>
     */
    private function studentRecordData(array $overrides = []): array
    {
        return array_diff_key(
            $this->studentData($overrides),
            array_flip(['college_id', 'department_id'])
        );
    }
}
