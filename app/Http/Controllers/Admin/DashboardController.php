<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\College;
use App\Models\Department;
use App\Models\Enrollment;
use App\Models\Program;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $catalog = College::query()
            ->with(['departments.programs'])
            ->orderBy('name')
            ->get();

        $courseCounts = Student::query()
            ->select('course', DB::raw('COUNT(*) as total'))
            ->groupBy('course')
            ->get()
            ->keyBy('course');

        $programsByName = Program::query()
            ->with('department.college')
            ->get()
            ->groupBy('name');

        $studentsByProgram = collect();
        $studentsByCollege = collect();
        $unmatchedStudentCourseCount = 0;

        foreach ($courseCounts as $course => $record) {
            $matchingPrograms = $programsByName->get($course, collect());

            if ($matchingPrograms->count() !== 1) {
                $unmatchedStudentCourseCount += (int) $record->total;

                continue;
            }

            $program = $matchingPrograms->first();
            $studentsByProgram->push([
                'name' => $program->name,
                'college' => $program->department->college->name,
                'count' => (int) $record->total,
            ]);

            $collegeName = $program->department->college->name;
            $studentsByCollege->put(
                $collegeName,
                ($studentsByCollege->get($collegeName, 0) + (int) $record->total)
            );
        }

        return view('admin.index', [
            'statistics' => [
                ['label' => 'Registered Users', 'count' => User::count()],
                ['label' => 'Administrators', 'count' => User::where('role', 'admin')->count()],
                ['label' => 'Staff Accounts', 'count' => User::where('role', 'staff')->count()],
                ['label' => 'Students', 'count' => Student::count()],
                ['label' => 'Subjects', 'count' => Subject::count()],
                ['label' => 'Enrollments', 'count' => Enrollment::count()],
                ['label' => 'Colleges', 'count' => College::count()],
                ['label' => 'Departments', 'count' => Department::count()],
                ['label' => 'Degree Programs', 'count' => Program::count()],
            ],
            'catalog' => $catalog,
            'programCountsByCollege' => $catalog->map(fn (College $college): array => [
                'college' => $college,
                'program_count' => $college->departments->sum(
                    fn ($department): int => $department->programs->count()
                ),
            ]),
            'recentStudents' => Student::query()
                ->latest('created_at')
                ->latest('id')
                ->limit(5)
                ->get(),
            'recentEnrollments' => Enrollment::query()
                ->with(['student', 'subject'])
                ->latest('created_at')
                ->latest('id')
                ->limit(5)
                ->get(),
            'recentUsers' => User::query()
                ->latest('created_at')
                ->latest('id')
                ->limit(5)
                ->get(),
            'studentsByYearLevel' => Student::query()
                ->select('year_level', DB::raw('COUNT(*) as total'))
                ->groupBy('year_level')
                ->orderBy('year_level')
                ->get(),
            'enrollmentsByStatus' => Enrollment::query()
                ->select('status', DB::raw('COUNT(*) as total'))
                ->groupBy('status')
                ->orderBy('status')
                ->get(),
            'studentsByProgram' => $studentsByProgram
                ->sortByDesc('count')
                ->take(8)
                ->values(),
            'studentsByCollege' => $studentsByCollege
                ->map(fn (int $count, string $college): array => [
                    'college' => $college,
                    'count' => $count,
                ])
                ->sortByDesc('count')
                ->values(),
            'unmatchedStudentCourseCount' => $unmatchedStudentCourseCount,
            'campus' => config('academic_catalog.campus'),
            'schoolYear' => config('academic_catalog.school_year'),
        ]);
    }
}
