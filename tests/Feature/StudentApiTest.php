<?php

namespace Tests\Feature;

use App\Models\Student;
use App\Models\User;
use Database\Seeders\NemsuAcademicCatalogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(NemsuAcademicCatalogSeeder::class);
    }

    public function test_admin_can_use_all_student_api_endpoints(): void
    {
        $this->withApiToken(User::factory()->create(['role' => 'admin']));
        $student = Student::create($this->studentData([
            'student_number' => 'SIMS-2002',
            'email' => 'existing.api@example.com',
        ]));

        $this->getJson('/api/students')
            ->assertOk()
            ->assertJsonPath('data.0.id', $student->id)
            ->assertJsonStructure(['data', 'links', 'meta']);

        $this->getJson("/api/students/{$student->id}")
            ->assertOk()
            ->assertJsonPath('data.id', $student->id);

        $created = $this->postJson('/api/students', $this->studentData())
            ->assertCreated()
            ->assertJsonPath('data.student_number', 'SIMS-2001')
            ->assertJsonStructure(['data' => [
                'id',
                'student_number',
                'first_name',
                'last_name',
                'email',
                'course',
                'year_level',
            ]]);

        $studentId = $created->json('data.id');

        $this->putJson("/api/students/{$studentId}", $this->studentData([
            'first_name' => 'Updated',
            'email' => 'updated.api@example.com',
        ]))
            ->assertOk()
            ->assertJsonPath('data.first_name', 'Updated');

        $this->patchJson("/api/students/{$studentId}", [
            'course' => 'Bachelor of Science in Computer Science',
        ])
            ->assertOk()
            ->assertJsonPath('data.course', 'Bachelor of Science in Computer Science')
            ->assertJsonPath('data.first_name', 'Updated');

        $this->deleteJson("/api/students/{$studentId}")
            ->assertNoContent();

        $this->assertDatabaseMissing('students', ['id' => $studentId]);
    }

    public function test_student_api_requires_authentication(): void
    {
        $this->getJson('/api/students')->assertUnauthorized();
        $this->postJson('/api/students', $this->studentData())
            ->assertUnauthorized();

        $this->withToken('invalid-token')
            ->getJson('/api/students')
            ->assertUnauthorized();
    }

    public function test_staff_can_create_and_update_but_cannot_delete_students_via_api(): void
    {
        $this->withApiToken(User::factory()->create(['role' => 'staff']));
        $student = Student::create($this->studentData([
            'student_number' => 'SIMS-2002',
            'email' => 'staff.api@example.com',
        ]));

        $this->getJson('/api/students')->assertOk();
        $this->getJson("/api/students/{$student->id}")->assertOk();

        $created = $this->postJson('/api/students', $this->studentData())
            ->assertCreated();
        $createdId = $created->json('data.id');

        $this->putJson("/api/students/{$student->id}", $this->studentData([
            'student_number' => $student->student_number,
            'email' => $student->email,
            'first_name' => 'Updated by Staff',
        ]))
            ->assertOk()
            ->assertJsonPath('data.first_name', 'Updated by Staff');
        $this->deleteJson("/api/students/{$student->id}")
            ->assertForbidden();

        $this->assertDatabaseHas('students', [
            'id' => $student->id,
            'first_name' => 'Updated by Staff',
        ]);
        $this->assertDatabaseHas('students', ['id' => $createdId]);
    }

    public function test_student_api_validates_input_and_returns_not_found(): void
    {
        $this->withApiToken(User::factory()->create(['role' => 'admin']));

        $this->postJson('/api/students', [
            'student_number' => '',
            'first_name' => '',
            'last_name' => '',
            'email' => 'invalid',
            'course' => '',
            'year_level' => 99,
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'student_number',
                'first_name',
                'last_name',
                'email',
                'course',
                'year_level',
            ]);

        $this->getJson('/api/students/999999')->assertNotFound();
        $this->putJson('/api/students/999999', $this->studentData())
            ->assertNotFound();
        $this->deleteJson('/api/students/999999')->assertNotFound();
        $this->assertDatabaseCount('students', 0);
    }

    public function test_student_api_rejects_duplicate_and_overlong_student_numbers(): void
    {
        $this->withApiToken(User::factory()->create(['role' => 'admin']));
        Student::create($this->studentData());

        $this->postJson('/api/students', $this->studentData([
            'email' => 'duplicate.number@example.com',
        ]))
            ->assertUnprocessable()
            ->assertJsonValidationErrors('student_number')
            ->assertJsonStructure(['message', 'errors' => ['student_number']]);

        $this->postJson('/api/students', $this->studentData([
            'student_number' => str_repeat('S', 31),
            'email' => 'overlong.number@example.com',
        ]))
            ->assertUnprocessable()
            ->assertJsonValidationErrors('student_number');

        $this->assertDatabaseCount('students', 1);
    }

    public function test_student_api_rejects_unknown_new_programs_but_preserves_legacy_values(): void
    {
        $this->withApiToken(User::factory()->create(['role' => 'admin']));

        $this->postJson('/api/students', $this->studentData([
            'course' => 'Unlisted degree program',
        ]))
            ->assertUnprocessable()
            ->assertJsonValidationErrors('course');

        $legacy = Student::create([
            'student_number' => 'SIMS-2099',
            'first_name' => 'Legacy',
            'last_name' => 'Student',
            'email' => 'legacy.api@example.com',
            'course' => 'Existing legacy program',
            'year_level' => 1,
        ]);

        $this->putJson("/api/students/{$legacy->id}", $this->studentData([
            'student_number' => $legacy->student_number,
            'email' => $legacy->email,
            'course' => $legacy->course,
        ]))
            ->assertOk()
            ->assertJsonPath('data.course', 'Existing legacy program');

        $this->assertDatabaseCount('students', 1);
    }

    /**
     * @return array<string, mixed>
     */
    private function studentData(array $overrides = []): array
    {
        return array_merge([
            'student_number' => 'SIMS-2001',
            'first_name' => 'Jordan',
            'last_name' => 'Lee',
            'email' => 'jordan.lee@example.com',
            'course' => 'Bachelor of Science in Computer Science',
            'year_level' => 1,
        ], $overrides);
    }

    private function withApiToken(User $user): static
    {
        return $this->withToken(
            $user->createToken('feature-test')->plainTextToken
        );
    }
}
