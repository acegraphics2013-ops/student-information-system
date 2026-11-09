<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Enrollment Management</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">
                @if (session('success'))
                    <div class="mb-5 rounded bg-green-100 p-3 text-green-800" role="status">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="mb-6 flex items-center justify-between">
                    <h3 class="text-lg font-bold">
                        Enrollments
                    </h3>
                    @if (auth()->user()->role === 'admin')
                        <a href="{{ route('enrollments.create') }}"
                           class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
                            + Enroll Student
                        </a>
                    @endif
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-100 text-left">
                            <tr>
                                <th class="p-3">Student</th>
                                <th class="p-3">Subject</th>
                                <th class="p-3">Status</th>
                                <th class="p-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($enrollments as $enrollment)
                                <tr class="border-b">
                                    <td class="p-3">
                                        {{ $enrollment->student->student_number }} —
                                        {{ $enrollment->student->first_name }}
                                        {{ $enrollment->student->last_name }}
                                    </td>
                                    <td class="p-3">
                                        {{ $enrollment->subject->subject_code }} —
                                        {{ $enrollment->subject->subject_name }}
                                    </td>
                                    <td class="p-3 capitalize">{{ $enrollment->status }}</td>
                                    <td class="p-3">
                                        <div class="flex items-center gap-3">
                                            <a href="{{ route('enrollments.show', $enrollment) }}" class="text-blue-600">View</a>
                                            @if (auth()->user()->role === 'admin')
                                                <a href="{{ route('enrollments.edit', $enrollment) }}" class="text-amber-600">Edit</a>
                                                <form action="{{ route('enrollments.destroy', $enrollment) }}"
                                                      method="POST"
                                                      onsubmit="return confirm('Remove this enrollment record?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600">Delete</button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-6 text-center text-gray-500">
                                        No enrollments found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">{{ $enrollments->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
