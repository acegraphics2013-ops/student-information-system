<?php

namespace Tests\Feature;

use App\Models\Enrollment;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_uses_breeze_layout_and_shows_record_totals(): void
    {
        $student = Student::create([
            'student_number' => 'STU-001',
            'first_name' => 'Jamie',
            'last_name' => 'Student',
            'email' => 'jamie@example.com',
            'course' => 'Computer Science',
            'year_level' => 1,
        ]);
        $subject = Subject::create([
            'subject_code' => 'CS101',
            'subject_name' => 'Introduction to Computing',
            'units' => 3,
        ]);
        Enrollment::create([
            'student_id' => $student->id,
            'subject_id' => $subject->id,
        ]);

        $this->actingAs(User::factory()->create())
            ->get(route('dashboard'))
            ->assertOk()
            ->assertViewHas('studentCount', 1)
            ->assertViewHas('subjectCount', 1)
            ->assertViewHas('enrollmentCount', 1)
            ->assertSee('Welcome back')
            ->assertSee('System overview')
            ->assertSee('Students')
            ->assertSee('Subjects')
            ->assertSee('Enrollments')
            ->assertSee('Profile')
            ->assertSee('Log Out');
    }
}
