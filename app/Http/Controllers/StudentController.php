<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentDataRequest;
use App\Models\College;
use App\Models\Program;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class StudentController extends Controller
{
    // Display all students
    public function index(): View
    {
        $students = Student::orderBy('last_name')
            ->orderBy('first_name')
            ->paginate(10);

        return view('students.index', compact('students'));
    }

    // Show the student creation form
    public function create(): View
    {
        return view('students.create', $this->studentFormOptions());
    }

    // Save a new student
    public function store(StudentDataRequest $request): RedirectResponse
    {
        Student::create(
            $request->safe()->only($this->studentFields())
        );

        return redirect()
            ->route('students.index')
            ->with('success', 'Student added successfully!');
    }

    // Display one student
    public function show(Student $student): View
    {
        return view('students.show', compact('student'));
    }

    // Show the edit form
    public function edit(Student $student): View
    {
        return view('students.edit', [
            'student' => $student,
            ...$this->studentFormOptions($student),
        ]);
    }

    // Update an existing student
    public function update(
        StudentDataRequest $request,
        Student $student
    ): RedirectResponse {
        $student->update(
            $request->safe()->only($this->studentFields())
        );

        return redirect()
            ->route('students.index')
            ->with('success', 'Student updated successfully!');
    }

    // Delete a student
    public function destroy(Student $student): RedirectResponse
    {
        $student->delete();

        return redirect()
            ->route('students.index')
            ->with('success', 'Student deleted successfully!');
    }

    /**
     * @return array<string, mixed>
     */
    private function studentFormOptions(?Student $student = null): array
    {
        $colleges = College::query()
            ->with(['departments.programs'])
            ->orderBy('name')
            ->get()
            ->map(fn (College $college): array => [
                'id' => $college->id,
                'name' => $college->name,
                'abbreviation' => $college->abbreviation,
                'departments' => $college->departments
                    ->sortBy('name')
                    ->values()
                    ->map(fn ($department): array => [
                        'id' => $department->id,
                        'name' => $department->name,
                        'programs' => $department->programs
                            ->sortBy('name')
                            ->values()
                            ->map(fn (Program $program): array => [
                                'id' => $program->id,
                                'name' => $program->name,
                            ]),
                    ]),
            ])
            ->values();

        $selectedProgram = $student
            ? Program::query()
                ->where('name', $student->course)
                ->with('department')
                ->first()
            : null;

        $legacyCourse = $student
            && ! Program::query()->where('name', $student->course)->exists()
            && old('course', $student->course) === $student->course
                ? $student->course
                : null;

        return [
            'academicCatalog' => $colleges,
            'selectedCollegeId' => old(
                'college_id',
                $selectedProgram?->department?->college_id
            ),
            'selectedDepartmentId' => old(
                'department_id',
                $selectedProgram?->department_id
            ),
            'legacyCourse' => $legacyCourse,
        ];
    }

    /**
     * @return array<int, string>
     */
    private function studentFields(): array
    {
        return [
            'student_number',
            'first_name',
            'last_name',
            'email',
            'course',
            'year_level',
        ];
    }
}
