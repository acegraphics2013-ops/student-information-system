<?php

namespace Tests\Feature;

use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentApiTest extends TestCase
{
    use RefreshDatabase;

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

        $this->deleteJson("/api/students/{$studentId}")
            ->assertNoContent();

        $this->assertDatabaseMissing('students', ['id' => $studentId]);
    }

    public function test_student_api_requires_authentication(): void
    {
        $this->getJson('/api/students')->assertUnauthorized();
        $this->postJson('/api/students', $this->studentData())
            ->assertUnauthorized();
    }

    public function test_staff_can_read_but_cannot_modify_students_via_api(): void
    {
        $this->withApiToken(User::factory()->create(['role' => 'staff']));
        $student = Student::create($this->studentData([
            'student_number' => 'SIMS-2002',
            'email' => 'staff.api@example.com',
        ]));

        $this->getJson('/api/students')->assertOk();
        $this->getJson("/api/students/{$student->id}")->assertOk();

        $this->postJson('/api/students', $this->studentData())
            ->assertForbidden();
        $this->putJson("/api/students/{$student->id}", $this->studentData())
            ->assertForbidden();
        $this->deleteJson("/api/students/{$student->id}")
            ->assertForbidden();

        $this->assertDatabaseHas('students', ['id' => $student->id]);
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
        $this->assertDatabaseCount('students', 0);
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
            'course' => 'Information Technology',
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
