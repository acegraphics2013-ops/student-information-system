<?php

namespace Tests\Feature;

use App\Models\Enrollment;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use Database\Seeders\NemsuAcademicCatalogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(NemsuAcademicCatalogSeeder::class);
    }

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

        $user = User::factory()->create([
            'profile_photo_path' => 'profile-photos/dashboard-user.png',
        ]);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertViewHas('studentCount', 1)
            ->assertViewHas('subjectCount', 1)
            ->assertViewHas('enrollmentCount', 1)
            ->assertViewHas('collegeCount', 7)
            ->assertViewHas('departmentCount', 13)
            ->assertViewHas('programCount', 22)
            ->assertSee('NEMSU official logo')
            ->assertSee('images/nemsu-logo.png')
            ->assertSee('<title>NEMSU Tandag | Student Information System</title>', false)
            ->assertSee('Welcome back')
            ->assertSee('src="'.asset('storage/'.$user->profile_photo_path).'"', false)
            ->assertSee($user->name.' profile photo')
            ->assertSee('System Overview')
            ->assertSee('Students')
            ->assertSee('Subjects')
            ->assertSee('Enrollments')
            ->assertSee('Academic Program Catalog')
            ->assertSee('Browse programs')
            ->assertSee('Profile settings')
            ->assertSee('Toggle account options')
            ->assertSee('Account options')
            ->assertSee('Log Out');
    }

    public function test_sidebar_shows_administration_links_only_to_admins(): void
    {
        $staffResponse = $this->actingAs(User::factory()->create(['role' => 'staff']))
            ->get(route('dashboard'));

        $staffResponse->assertOk()
            ->assertSee('Main sidebar')
            ->assertSee('Students')
            ->assertSee('Enrollments')
            ->assertDontSee('User Accounts');

        $adminResponse = $this->actingAs(User::factory()->create(['role' => 'admin']))
            ->get(route('dashboard'));

        $adminResponse->assertOk()
            ->assertSee('Administration')
            ->assertSee('User Accounts');
    }
}
