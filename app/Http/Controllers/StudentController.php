<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\StudentDataRequest;
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
    public function store(StudentDataRequest $request): RedirectResponse
    {
        Student::create($request->validated());

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
        StudentDataRequest $request,
        Student $student
    ): RedirectResponse {
        $student->update($request->validated());

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
