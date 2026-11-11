<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class EnrollmentController extends Controller
{
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
        ]);
        $search = trim($validated['search'] ?? '');
        $students = Student::query()
            ->whereHas('enrollments')
            ->when($search !== '', function ($query) use ($search): void {
                $like = '%'.$search.'%';
                $terms = preg_split('/\s+/', $search, -1, PREG_SPLIT_NO_EMPTY);

                $query->where(function ($query) use ($like, $terms): void {
                    $query->where('student_number', 'like', $like)
                        ->orWhere('email', 'like', $like)
                        ->orWhere(function ($nameQuery) use ($terms): void {
                            foreach ($terms as $term) {
                                $termLike = '%'.$term.'%';
                                $nameQuery->where(function ($termQuery) use ($termLike): void {
                                    $termQuery->where('first_name', 'like', $termLike)
                                        ->orWhere('last_name', 'like', $termLike);
                                });
                            }
                        })
                        ->orWhereHas('enrollments', function ($enrollmentQuery) use ($like): void {
                            $enrollmentQuery->where('status', 'like', $like)
                                ->orWhere('semester', 'like', $like)
                                ->orWhereHas('subject', function ($subjectQuery) use ($like): void {
                                    $subjectQuery->where('subject_code', 'like', $like)
                                        ->orWhere('subject_name', 'like', $like);
                                });
                        });
                });
            })
            ->with(['enrollments.subject'])
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->paginate(10);

        return view('enrollments.index', [
            'students' => $students->withQueryString(),
            'search' => $search,
        ]);
    }

    public function create(Request $request): View
    {
        $validated = $request->validate([
            'student_id' => ['nullable', 'integer', Rule::exists('students', 'id')],
        ]);

        return view('enrollments.create', [
            ...$this->formOptions(),
            'initialStudentId' => $validated['student_id'] ?? '',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'student_id' => ['required', 'integer', Rule::exists('students', 'id')],
            'subject_ids' => ['required', 'array', 'min:1'],
            'subject_ids.*' => ['required', 'integer', 'distinct', Rule::exists('subjects', 'id')],
            'status' => ['required', Rule::in(['enrolled', 'completed', 'dropped'])],
            'semester' => ['required', Rule::in(Enrollment::SEMESTERS)],
            'year_level' => ['nullable', 'integer', 'between:1,5'],
        ]);

        DB::transaction(function () use ($validated): void {
            $student = Student::query()
                ->lockForUpdate()
                ->findOrFail($validated['student_id']);
            $subjectIds = array_map('intval', $validated['subject_ids']);
            $alreadyEnrolled = Enrollment::query()
                ->where('student_id', $student->id)
                ->whereIn('subject_id', $subjectIds)
                ->exists();

            if ($alreadyEnrolled) {
                throw ValidationException::withMessages([
                    'subject_ids' => 'The student is already enrolled in one or more selected subjects.',
                ]);
            }

            $selectedUnits = Subject::query()
                ->whereIn('id', $subjectIds)
                ->sum('units');

            if ($selectedUnits > 10) {
                throw ValidationException::withMessages([
                    'subject_ids' => 'The selected subjects cannot exceed 10 units in total.',
                ]);
            }

            if ($validated['status'] === 'enrolled') {
                $currentUnits = Enrollment::query()
                    ->join('subjects', 'enrollments.subject_id', '=', 'subjects.id')
                    ->where('enrollments.student_id', $student->id)
                    ->where('enrollments.status', 'enrolled')
                    ->where('enrollments.semester', $validated['semester'])
                    ->sum('subjects.units');

                if ($currentUnits + $selectedUnits > 10) {
                    throw ValidationException::withMessages([
                        'subject_ids' => 'A student may not exceed 10 enrolled units per semester. This student currently has '.$currentUnits.' units in '.$validated['semester'].'.',
                    ]);
                }
            }

            foreach ($subjectIds as $subjectId) {
                Enrollment::create([
                    'student_id' => $student->id,
                    'subject_id' => $subjectId,
                    'status' => $validated['status'],
                    'semester' => $validated['semester'],
                    'year_level' => $validated['year_level'] ?? $student->year_level,
                ]);
            }
        });

        return redirect()->route('enrollments.index')
            ->with('success', 'Student enrolled in the selected subjects successfully.');
    }

    public function show(Enrollment $enrollment): View
    {
        $student = Student::query()
            ->with(['enrollments.subject'])
            ->findOrFail($enrollment->student_id);

        return view('enrollments.show', compact('student'));
    }

    public function edit(Enrollment $enrollment): View
    {
        return view('enrollments.edit', [
            'enrollment' => $enrollment,
            ...$this->formOptions(),
        ]);
    }

    public function update(Request $request, Enrollment $enrollment): RedirectResponse
    {
        $validated = $this->validateEnrollment($request, $enrollment);

        DB::transaction(function () use ($validated, $enrollment): void {
            $student = Student::query()
                ->lockForUpdate()
                ->findOrFail($validated['student_id']);

            if ($validated['status'] === 'enrolled') {
                $currentUnits = Enrollment::query()
                    ->join('subjects', 'enrollments.subject_id', '=', 'subjects.id')
                    ->where('enrollments.student_id', $student->id)
                    ->where('enrollments.status', 'enrolled')
                    ->where('enrollments.semester', $validated['semester'])
                    ->when(
                        (int) $enrollment->student_id === (int) $student->id
                            && $enrollment->status === 'enrolled'
                            && $enrollment->semester === $validated['semester'],
                        fn ($query) => $query->where('enrollments.id', '!=', $enrollment->id)
                    )
                    ->sum('subjects.units');
                $selectedUnits = Subject::query()
                    ->whereKey($validated['subject_id'])
                    ->value('units');

                if ($currentUnits + $selectedUnits > 10) {
                    throw ValidationException::withMessages([
                        'subject_id' => 'A student may not exceed 10 enrolled units per semester. This student currently has '.$currentUnits.' units in '.$validated['semester'].'.',
                    ]);
                }
            }

            // Keep the existing year when the form leaves it blank.
            $validated['year_level'] ??= $student->year_level;

            $enrollment->update($validated);
        });

        return redirect()->route('enrollments.index')
            ->with('success', 'Enrollment updated successfully.');
    }

    public function destroy(Enrollment $enrollment): RedirectResponse
    {
        $enrollment->delete();

        return redirect()->route('enrollments.index')
            ->with('success', 'Enrollment removed successfully.');
    }

    private function formOptions(): array
    {
        $students = Student::query()
            ->with(['enrollments.subject'])
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        return [
            'students' => $students,
            'enrollmentStudents' => $students->map(fn (Student $student): array => [
                'id' => $student->id,
                'student_number' => $student->student_number,
                'first_name' => $student->first_name,
                'last_name' => $student->last_name,
                'email' => $student->email,
                'year_level' => $student->year_level,
                'course' => $student->course,
                'semesters' => $student->enrollments
                    ->pluck('semester')
                    ->filter()
                    ->unique()
                    ->values()
                    ->all(),
                'subject_count' => $student->enrollments->count(),
                'units_by_semester' => collect(Enrollment::SEMESTERS)
                    ->mapWithKeys(fn (string $semester): array => [
                        $semester => (int) $student->enrollments
                            ->where('status', 'enrolled')
                            ->where('semester', $semester)
                            ->sum(fn (Enrollment $enrollment): int => (int) ($enrollment->subject?->units ?? 0)),
                    ])
                    ->all(),
                'existing_subject_ids' => $student->enrollments
                    ->pluck('subject_id')
                    ->map(fn ($id): string => (string) $id)
                    ->values(),
            ])->values(),
            'subjects' => Subject::orderBy('subject_code')->get(),
            'semesters' => Enrollment::SEMESTERS,
            'yearLevels' => Enrollment::YEAR_LEVELS,
        ];
    }

    private function validateEnrollment(Request $request, ?Enrollment $enrollment = null): array
    {
        $subjectUnique = Rule::unique('enrollments', 'subject_id')
            ->where(fn ($query) => $query->where('student_id', $request->input('student_id')));

        if ($enrollment) {
            $subjectUnique->ignore($enrollment->id);
        }

        return $request->validate([
            'student_id' => ['required', 'integer', Rule::exists('students', 'id')],
            'subject_id' => [
                'required',
                'integer',
                Rule::exists('subjects', 'id'),
                $subjectUnique,
            ],
            'status' => ['required', Rule::in(['enrolled', 'completed', 'dropped'])],
            'semester' => ['required', Rule::in(Enrollment::SEMESTERS)],
            'year_level' => ['nullable', 'integer', 'between:1,5'],
        ], [
            'subject_id.unique' => 'This student is already enrolled in that subject.',
        ]);
    }
}
