<?php

namespace Tests\Feature;

use App\Models\College;
use App\Models\Department;
use App\Models\Enrollment;
use App\Models\Program;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use Database\Seeders\NemsuAcademicCatalogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_shows_database_statistics_and_system_summaries(): void
    {
        $this->seed(NemsuAcademicCatalogSeeder::class);
        $admin = User::factory()->create(['role' => 'admin']);
        User::factory()->create(['role' => 'staff']);
        User::factory()->create(['role' => 'staff']);
        $student = Student::create([
            'student_number' => 'ADM-001',
            'first_name' => 'Taylor',
            'last_name' => 'Student',
            'email' => 'taylor@example.com',
            'course' => 'Bachelor of Science in Computer Science',
            'year_level' => 2,
        ]);
        $subject = Subject::create([
            'subject_code' => 'ADM101',
            'subject_name' => 'Administration Test Subject',
            'units' => 3,
        ]);
        Enrollment::create([
            'student_id' => $student->id,
            'subject_id' => $subject->id,
            'status' => 'enrolled',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.index'))
            ->assertOk()
            ->assertViewHas('statistics', function (array $statistics): bool {
                $counts = collect($statistics)->pluck('count', 'label');

                return $counts->get('Registered Users') === 3
                    && $counts->get('Administrators') === 1
                    && $counts->get('Staff Accounts') === 2
                    && $counts->get('Students') === 1
                    && $counts->get('Subjects') === 1
                    && $counts->get('Enrollments') === 1
                    && $counts->get('Colleges') === 7
                    && $counts->get('Departments') === 13
                    && $counts->get('Degree Programs') === 22;
            })
            ->assertViewHas('programCountsByCollege', function ($rows): bool {
                return $rows->sum('program_count') === Program::count()
                    && College::count() === 7
                    && Department::count() === 13;
            })
            ->assertViewHas('studentsByProgram', fn ($rows): bool => $rows->contains(
                fn (array $row): bool => $row['name'] === 'Bachelor of Science in Computer Science'
                    && $row['count'] === 1
            ))
            ->assertViewHas('studentsByYearLevel', fn ($rows): bool => (int) $rows->first()->total === 1)
            ->assertViewHas('enrollmentsByStatus', fn ($rows): bool => $rows->first()->status === 'enrolled')
            ->assertSee('NEMSU Tandag Campus')
            ->assertSee('School Year 2026–2027')
            ->assertSee('Taylor Student')
            ->assertSee('Administration dashboard')
            ->assertSee('Recent records');
    }

    public function test_staff_and_guests_cannot_access_administration_dashboard(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'staff']))
            ->get(route('admin.index'))
            ->assertForbidden();

        auth()->logout();

        $this->get(route('admin.index'))
            ->assertRedirect(route('login'));
    }
}
