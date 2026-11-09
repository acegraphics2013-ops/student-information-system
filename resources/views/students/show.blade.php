
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            Student Details
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">

                <h3 class="text-xl font-bold mb-4">
                    {{ $student->first_name }}
                    {{ $student->last_name }}
                </h3>

                <div class="space-y-3">
                    <p><strong>Student Number:</strong>
                        {{ $student->student_number }}</p>

                    <p><strong>Email:</strong>
                        {{ $student->email }}</p>

                    <p><strong>Course:</strong>
                        {{ $student->course }}</p>

                    <p><strong>Year Level:</strong>
                        {{ $student->year_level }}</p>
                </div>

                <div class="mt-6 flex gap-4">
                    <a href="{{ route('students.index') }}"
                       class="text-blue-600">
                        Back to Students
                    </a>

                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('students.edit', $student) }}"
                           class="text-amber-600">
                            Edit Student
                        </a>
                    @endif
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
