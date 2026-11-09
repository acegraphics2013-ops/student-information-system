
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            Subject Management
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">

                @if(session('success'))
                    <div class="mb-4 rounded bg-green-100 p-3 text-green-800">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold">Subjects</h3>

                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('subjects.create') }}"
                           class="bg-blue-600 text-white px-4 py-2 rounded">
                            + Add Subject
                        </a>
                    @endif
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full border-collapse">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="p-3 text-left">Code</th>
                                <th class="p-3 text-left">Subject Name</th>
                                <th class="p-3 text-left">Units</th>
                                <th class="p-3 text-left">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($subjects as $subject)
                                <tr class="border-b">
                                    <td class="p-3">
                                        {{ $subject->subject_code }}
                                    </td>

                                    <td class="p-3">
                                        {{ $subject->subject_name }}
                                    </td>

                                    <td class="p-3">
                                        {{ $subject->units }}
                                    </td>

                                    <td class="p-3">
                                        <div class="flex gap-3">
                                            <a href="{{ route('subjects.show', $subject) }}"
                                               class="text-blue-600">
                                                View
                                            </a>

                                            @if(auth()->user()->role === 'admin')
                                                <a href="{{ route('subjects.edit', $subject) }}"
                                                   class="text-amber-600">
                                                    Edit
                                                </a>

                                                <form method="POST"
                                                      action="{{ route('subjects.destroy', $subject) }}"
                                                      onsubmit="return confirm('Delete this subject and its related enrollments?')">
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
                                    <td colspan="4" class="p-4 text-center text-gray-500">
                                        No subjects found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $subjects->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
