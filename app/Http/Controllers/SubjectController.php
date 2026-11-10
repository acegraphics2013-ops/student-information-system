<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SubjectController extends Controller
{
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
        ]);
        $search = trim($validated['search'] ?? '');

        $subjects = Subject::query()
            ->when($search !== '', function ($query) use ($search): void {
                $like = '%'.$search.'%';
                $query->where('subject_code', 'like', $like)
                    ->orWhere('subject_name', 'like', $like);
            })
            ->orderBy('subject_code')
            ->get();

        return view('subjects.index', [
            'subjects' => $subjects,
            'search' => $search,
        ]);
    }

    public function create(): View
    {
        return view('subjects.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'subject_code' => [
                'required',
                'string',
                'max:30',
                'unique:subjects,subject_code',
            ],
            'subject_name' => [
                'required',
                'string',
                'max:255',
            ],
            'units' => [
                'required',
                'integer',
                'min:1',
                'max:12',
            ],
        ]);

        Subject::create($validated);

        return redirect()
            ->route('subjects.index')
            ->with('success', 'Subject added successfully!');
    }

    public function show(Subject $subject): View
    {
        $subject->loadCount('enrollments');

        return view('subjects.show', compact('subject'));
    }

    public function edit(Subject $subject): View
    {
        return view('subjects.edit', compact('subject'));
    }

    public function update(
        Request $request,
        Subject $subject
    ): RedirectResponse {
        $validated = $request->validate([
            'subject_code' => [
                'required',
                'string',
                'max:30',
                Rule::unique('subjects', 'subject_code')
                    ->ignore($subject->id),
            ],
            'subject_name' => [
                'required',
                'string',
                'max:255',
            ],
            'units' => [
                'required',
                'integer',
                'min:1',
                'max:12',
            ],
        ]);

        $subject->update($validated);

        return redirect()
            ->route('subjects.index')
            ->with('success', 'Subject updated successfully!');
    }

    public function destroy(Subject $subject): RedirectResponse
    {
        $subject->delete();

        return redirect()
            ->route('subjects.index')
            ->with('success', 'Subject deleted successfully!');
    }
}
