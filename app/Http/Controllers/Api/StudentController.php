<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudentDataRequest;
use App\Http\Resources\StudentResource;
use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class StudentController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $students = Student::query()
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->paginate(10);

        return StudentResource::collection($students);
    }

    public function store(StudentDataRequest $request): JsonResponse
    {
        $student = Student::create(
            $request->safe()->only($this->studentFields())
        );

        return (new StudentResource($student))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Student $student): StudentResource
    {
        return new StudentResource($student);
    }

    public function update(
        StudentDataRequest $request,
        Student $student
    ): StudentResource {
        $student->update(
            $request->safe()->only($this->studentFields())
        );

        return new StudentResource($student->refresh());
    }

    public function destroy(Student $student): Response
    {
        $student->delete();

        return response()->noContent();
    }

    /**
     * @return array<int, string>
     */
    private function studentFields(): array
    {
        return [
            'student_number',
            'first_name',
            'last_name',
            'email',
            'course',
            'year_level',
        ];
    }
}
