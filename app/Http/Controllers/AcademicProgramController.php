<?php

namespace App\Http\Controllers;

use App\Models\College;
use App\Models\Department;
use App\Models\Program;
use Illuminate\Http\Request;
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
}
