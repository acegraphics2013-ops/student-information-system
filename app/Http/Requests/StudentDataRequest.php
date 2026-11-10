<?php

namespace App\Http\Requests;

use App\Models\Program;
use App\Models\Student;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

class StudentDataRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, mixed>|string>
     */
    public function rules(): array
    {
        $student = $this->route('student');
        $required = $this->isMethod('PATCH')
            ? ['sometimes', 'required']
            : ['required'];
        $isWebForm = $this->routeIs('students.store', 'students.update');
        $programQuery = Program::query();

        if ($isWebForm && $this->filled('college_id')) {
            $programQuery->whereHas(
                'department',
                fn ($query) => $query->where('college_id', $this->input('college_id'))
            );
        }

        $validCourses = $programQuery->pluck('name')->all();
        $isLegacyCourse = $student
            && ! Program::query()->where('name', $student->course)->exists();

        if ($isLegacyCourse) {
            $validCourses[] = $student->course;
        }

        $preservingLegacyCourse = $isLegacyCourse
            && $this->input('course') === $student->course;
        $selectorRequired = $isWebForm && ! $preservingLegacyCourse
            ? ['required', 'integer']
            : ['nullable', 'integer'];

        return [
            'student_number' => [
                ...$required,
                'string',
                'max:30',
                $this->uniqueRule('student_number', $student),
            ],
            'first_name' => [...$required, 'string', 'max:100'],
            'last_name' => [...$required, 'string', 'max:100'],
            'email' => [
                ...$required,
                'email',
                'max:255',
                $this->uniqueRule('email', $student),
            ],
            'course' => [...$required, 'string', 'max:150', Rule::in($validCourses)],
            'year_level' => [...$required, 'integer', 'between:1,6'],
            'college_id' => $isWebForm
                ? [
                    ...$selectorRequired,
                    Rule::exists('colleges', 'id'),
                ]
                : ['prohibited'],
            'department_id' => ['prohibited'],
        ];
    }

    private function uniqueRule(string $column, ?Student $student): Unique
    {
        $rule = Rule::unique('students', $column);

        if ($student instanceof Student) {
            $rule->ignore($student);
        }

        return $rule;
    }
}
