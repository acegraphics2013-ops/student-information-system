<?php

namespace Tests\Feature;

use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TmpDumpTest extends TestCase
{
    use RefreshDatabase;

    public function test_dump_create_page(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));

        Student::create([
            'student_number' => '2026-001',
            'first_name' => 'Maria',
            'last_name' => 'Santos',
            'email' => 'maria@example.com',
            'course' => 'BSIT',
            'year_level' => 2,
        ]);

        Subject::create([
            'subject_code' => 'IT101',
            'subject_name' => 'Introduction to Computing',
            'units' => 3,
        ]);

        file_put_contents(base_path('create-dump.html'), $this->get('/enrollments/create')->getContent());

        $this->assertTrue(true);
    }
}
