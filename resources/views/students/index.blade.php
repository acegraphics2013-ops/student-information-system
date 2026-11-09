
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            Student Management
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow rounded-lg p-6">

                @if(session('success'))
                    <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold">Students</h3>

                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('students.create') }}"
                           class="bg-blue-600 text-white px-4 py-2 rounded">
                            + Add Student
                        </a>
                    @endif
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full border-collapse">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="p-3 text-left">Student No.</th>
                                <th class="p-3 text-left">Name</th>
                                <th class="p-3 text-left">Course</th>
                                <th class="p-3 text-left">Year</th>
                                <th class="p-3 text-left">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($students as $student)
                                <tr class="border-b">
                                    <td class="p-3">
                                        {{ $student->student_number }}
                                    </td>

                                    <td class="p-3">
                                        {{ $student->first_name }}
                                        {{ $student->last_name }}
                                    </td>

                                    <td class="p-3">
                                        {{ $student->course }}
                                    </td>

                                    <td class="p-3">
                                        {{ $student->year_level }}
                                    </td>

                                    <td class="p-3">
                                        <div class="flex gap-3">
                                            <a href="{{ route('students.show', $student) }}"
                                               class="text-blue-600">
                                                View
                                            </a>

                                            @if(auth()->user()->role === 'admin')
                                                <a href="{{ route('students.edit', $student) }}"
                                                   class="text-amber-600">
                                                    Edit
                                                </a>

                                                <form method="POST"
                                                      action="{{ route('students.destroy', $student) }}"
                                                      onsubmit="return confirm('Delete this student and all related enrollments?')">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit"
                                                            class="text-red-600">
                                                        Delete
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-4 text-center">
                                        No students found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $students->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
