<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentDataRequest;
use App\Models\College;
use App\Models\Program;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentController extends Controller
{
    // Display all students
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
        ]);
        $search = trim($validated['search'] ?? '');
        $studentsQuery = Student::query()
            ->when($search !== '', function ($query) use ($search): void {
                $terms = preg_split('/\s+/', $search, -1, PREG_SPLIT_NO_EMPTY);

                $query->where(function ($query) use ($search, $terms): void {
                    $like = '%'.$search.'%';

                    $query->where('student_number', 'like', $like)
                        ->orWhere('email', 'like', $like)
                        ->orWhere('course', 'like', $like)
                        ->orWhere(function ($nameQuery) use ($terms): void {
                            foreach ($terms as $term) {
                                $termLike = '%'.$term.'%';

                                $nameQuery->where(function ($termQuery) use ($termLike): void {
                                    $termQuery->where('first_name', 'like', $termLike)
                                        ->orWhere('last_name', 'like', $termLike);
                                });
                            }
                        });
                });
            });

        $students = $studentsQuery
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->paginate(10);

        return view('students.index', [
            'students' => $students->withQueryString(),
            'search' => $search,
        ]);
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
                'programs' => $college->departments
                    ->flatMap(fn ($department) => $department->programs)
                    ->sortBy('name')
                    ->values()
                    ->map(fn (Program $program): array => [
                        'id' => $program->id,
                        'name' => $program->name,
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
