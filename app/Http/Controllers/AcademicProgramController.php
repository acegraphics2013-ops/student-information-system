<?php

namespace App\Http\Controllers;

use App\Models\College;
use App\Models\Department;
use App\Models\Program;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AcademicProgramController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'college_id' => ['nullable', 'integer', 'exists:colleges,id'],
            'search' => ['nullable', 'string', 'max:100'],
        ]);
        $search = trim($filters['search'] ?? '');
        $collegeId = $filters['college_id'] ?? null;

        $programSearch = fn ($query) => $query->when(
            $search !== '',
            fn ($programs) => $programs->where(
                'name',
                'like',
                '%'.$search.'%'
            )
        );

        $colleges = College::query()
            ->when($collegeId, fn ($query) => $query->whereKey($collegeId))
            ->whereHas(
                'departments.programs',
                fn ($query) => $programSearch($query)
            )
            ->with([
                'departments' => fn ($query) => $query
                    ->whereHas('programs', $programSearch)
                    ->orderBy('name'),
                'departments.programs' => fn ($query) => $programSearch($query)
                    ->orderBy('name'),
            ])
            ->orderBy('name')
            ->get();

        return view('academic-programs.index', [
            'colleges' => $colleges,
            'collegeOptions' => College::query()->orderBy('name')->get(),
            'selectedCollegeId' => $collegeId,
            'search' => $search,
            'campus' => config('academic_catalog.campus'),
            'schoolYear' => config('academic_catalog.school_year'),
            'sourceNote' => config('academic_catalog.source_note'),
            'collegeCount' => College::count(),
            'departmentCount' => Department::count(),
            'programCount' => Program::count(),
        ]);
    }

    public function create(): View
    {
        return view('academic-programs.create', [
            'colleges' => $this->departmentOptions(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateProgram($request);

        Program::create($validated);

        return redirect()
            ->route('academic-programs.index')
            ->with('success', 'Academic program added successfully.');
    }

    public function edit(Program $academicProgram): View
    {
        return view('academic-programs.edit', [
            'program' => $academicProgram,
            'colleges' => $this->departmentOptions(),
        ]);
    }

    public function update(Request $request, Program $academicProgram): RedirectResponse
    {
        $validated = $this->validateProgram($request, $academicProgram);

        $academicProgram->update($validated);

        return redirect()
            ->route('academic-programs.index')
            ->with('success', 'Academic program updated successfully.');
    }

    public function destroy(Program $academicProgram): RedirectResponse
    {
        $academicProgram->delete();

        return redirect()
            ->route('academic-programs.index')
            ->with('success', 'Academic program deleted successfully.');
    }

    private function departmentOptions(): Collection
    {
        return College::query()
            ->with(['departments' => fn ($query) => $query->orderBy('name')])
            ->orderBy('name')
            ->get();
    }

    private function validateProgram(Request $request, ?Program $program = null): array
    {
        $nameUnique = Rule::unique('programs', 'name')
            ->where(fn ($query) => $query->where('department_id', $request->input('department_id')));

        if ($program) {
            $nameUnique->ignore($program->id);
        }

        return $request->validate([
            'department_id' => ['required', 'integer', Rule::exists('departments', 'id')],
            'name' => ['required', 'string', 'max:255', $nameUnique],
        ], [
            'name.unique' => 'This program already exists in the selected department.',
        ]);
    }
}
