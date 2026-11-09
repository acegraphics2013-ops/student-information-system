<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class EnrollmentController extends Controller
{
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
        ]);
        $search = trim($validated['search'] ?? '');
        $enrollments = Enrollment::query()
            ->with(['student', 'subject'])
            ->when($search !== '', function ($query) use ($search): void {
                $like = '%'.$search.'%';

                $query->where(function ($query) use ($like, $search): void {
                    $query->where('status', 'like', $like)
                        ->orWhereHas('student', function ($studentQuery) use ($like, $search): void {
                            $terms = preg_split('/\s+/', $search, -1, PREG_SPLIT_NO_EMPTY);

                            $studentQuery->where(function ($studentQuery) use ($like, $terms): void {
                                $studentQuery->where('student_number', 'like', $like)
                                    ->orWhere('email', 'like', $like)
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
                        })
                        ->orWhereHas('subject', function ($subjectQuery) use ($like): void {
                            $subjectQuery->where('subject_code', 'like', $like)
                                ->orWhere('subject_name', 'like', $like);
                        });
                });
            })
            ->latest()
            ->paginate(10);

        return view('enrollments.index', [
            'enrollments' => $enrollments->withQueryString(),
            'search' => $search,
        ]);
    }

    public function create(): View
    {
        return view('enrollments.create', $this->formOptions());
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateEnrollment($request);

        Enrollment::create($validated);

        return redirect()->route('enrollments.index')
            ->with('success', 'Student enrolled successfully.');
    }

    public function show(Enrollment $enrollment): View
    {
        $enrollment->load(['student', 'subject']);

        return view('enrollments.show', compact('enrollment'));
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

        $enrollment->update($validated);

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
        return [
            'students' => Student::orderBy('last_name')->orderBy('first_name')->get(),
            'subjects' => Subject::orderBy('subject_code')->get(),
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
        ], [
            'subject_id.unique' => 'This student is already enrolled in that subject.',
        ]);
    }
}
