<?php

namespace App\Http\Requests;

use App\Models\Program;
use App\Models\Student;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;
use Illuminate\Validation\Validator;

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
        $programNames = Program::query()->pluck('name')->all();
        $validCourses = $student
            ? array_merge($programNames, [$student->course])
            : $programNames;
        $isWebForm = $this->routeIs('students.store', 'students.update');
        $preservingLegacyCourse = $student
            && $this->input('course') === $student->course
            && ! in_array($student->course, $programNames, true);
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
            'department_id' => $isWebForm
                ? [
                    ...$selectorRequired,
                    Rule::exists('departments', 'id')
                        ->where('college_id', $this->input('college_id')),
                ]
                : ['prohibited'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        if (! $this->routeIs('students.store', 'students.update')) {
            return;
        }

        $student = $this->route('student');
        $course = $this->input('course');

        if (
            $student instanceof Student
            && $course === $student->course
            && ! Program::query()->where('name', $course)->exists()
            && $this->filled('college_id') === false
            && $this->filled('department_id') === false
        ) {
            return;
        }

        $program = Program::query()
            ->where('name', $course)
            ->with('department')
            ->first();

        if (! $program) {
            return;
        }

        if (
            $program->department->college_id != $this->input('college_id')
        ) {
            $validator->errors()->add(
                'college_id',
                'The selected college does not offer this program.'
            );
        }

        if ($program->department_id != $this->input('department_id')) {
            $validator->errors()->add(
                'department_id',
                'The selected department does not offer this program.'
            );
        }
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
