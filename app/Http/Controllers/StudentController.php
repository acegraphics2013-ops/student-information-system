<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
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
        return view('students.create');
    }

    // Save a new student
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'student_number' => [
                'required', 'string', 'max:30',
                'unique:students,student_number',
            ],
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|max:255|unique:students,email',
            'course' => 'required|string|max:150',
            'year_level' => 'required|integer|between:1,6',
        ]);

        Student::create($validated);

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
        return view('students.edit', compact('student'));
    }

    // Update an existing student
    public function update(
        Request $request,
        Student $student
    ): RedirectResponse {
        $validated = $request->validate([
            'student_number' => [
                'required', 'string', 'max:30',
                Rule::unique('students', 'student_number')
                    ->ignore($student->id),
            ],
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => [
                'required', 'email', 'max:255',
                Rule::unique('students', 'email')
                    ->ignore($student->id),
            ],
            'course' => 'required|string|max:150',
            'year_level' => 'required|integer|between:1,6',
        ]);

        $student->update($validated);

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
}
