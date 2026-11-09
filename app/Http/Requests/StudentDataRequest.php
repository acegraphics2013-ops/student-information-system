<?php

namespace App\Http\Requests;

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
            'course' => [...$required, 'string', 'max:150'],
            'year_level' => [...$required, 'integer', 'between:1,6'],
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
