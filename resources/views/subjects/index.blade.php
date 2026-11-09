<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-medium text-indigo-600">Student Information System</p>
            <h1 class="mt-1 text-2xl font-semibold tracking-tight text-gray-900">Subject Management</h1>
        </div>
    </x-slot>

    <main class="py-10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <section class="space-y-5 rounded-xl border border-gray-200 bg-white p-4 shadow-sm sm:p-6">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Subjects</h2>
                        <p class="mt-1 text-sm text-gray-500">Search by subject code or subject name.</p>
                    </div>
                    @if (auth()->user()->role === 'admin')
                        <a href="{{ route('subjects.create') }}" class="inline-flex min-h-11 items-center justify-center rounded-md bg-indigo-700 px-4 text-sm font-semibold text-white shadow-sm hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            Add subject
                        </a>
                    @endif
                </div>

                <form method="GET" action="{{ route('subjects.index') }}" role="search" class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                    <label for="subject-search" class="block text-sm font-semibold text-gray-700">Search subjects</label>
                    <div class="mt-2 flex flex-col gap-2 sm:flex-row">
                        <input id="subject-search" name="search" type="search" value="{{ $search }}" maxlength="100" placeholder="Subject code or name" class="min-h-11 min-w-0 flex-1 rounded-md border-gray-300 bg-white shadow-sm placeholder:text-gray-400 focus:border-indigo-500 focus:ring-indigo-500">
                        <button type="submit" class="inline-flex min-h-11 items-center justify-center rounded-md bg-gray-900 px-5 text-sm font-semibold text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-700 focus:ring-offset-2">Search</button>
                        @if ($search !== '')
                            <a href="{{ route('subjects.index') }}" class="inline-flex min-h-11 items-center justify-center rounded-md border border-gray-300 bg-white px-4 text-sm font-semibold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Clear</a>
                        @endif
                    </div>
                </form>

                <section class="overflow-hidden rounded-lg border border-gray-200" aria-label="Subject results">
                    <div class="flex flex-wrap items-center justify-between gap-2 border-b border-gray-100 px-4 py-3 sm:px-5">
                        <h3 class="font-semibold text-gray-900">Subject records</h3>
                        <p class="text-sm text-gray-500">
                            @if ($subjects->total() > 0)
                                Showing {{ $subjects->firstItem() }}–{{ $subjects->lastItem() }} of {{ number_format($subjects->total()) }}
                            @else
                                No records found
                            @endif
                        </p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse text-sm">
                            <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500">
                                <tr>
                                    <th scope="col" class="px-4 py-3 text-left font-semibold">Code</th>
                                    <th scope="col" class="px-4 py-3 text-left font-semibold">Subject Name</th>
                                    <th scope="col" class="px-4 py-3 text-left font-semibold">Units</th>
                                    <th scope="col" class="px-4 py-3 text-left font-semibold">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse ($subjects as $subject)
                                    <tr class="hover:bg-gray-50">
                                        <td class="whitespace-nowrap px-4 py-3 font-medium text-gray-900">{{ $subject->subject_code }}</td>
                                        <td class="px-4 py-3 text-gray-700">{{ $subject->subject_name }}</td>
                                        <td class="whitespace-nowrap px-4 py-3 text-gray-600">{{ $subject->units }}</td>
                                        <td class="whitespace-nowrap px-4 py-3">
                                            <div class="flex items-center gap-3">
                                                <a href="{{ route('subjects.show', $subject) }}" class="font-medium text-indigo-700 hover:text-indigo-900 focus:outline-none focus:underline">View</a>
                                                @if (auth()->user()->role === 'admin')
                                                    <a href="{{ route('subjects.edit', $subject) }}" class="font-medium text-gray-600 hover:text-gray-900 focus:outline-none focus:underline">Edit</a>
                                                    <form method="POST" action="{{ route('subjects.destroy', $subject) }}" onsubmit="return confirm('Delete this subject and its related enrollments?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="font-medium text-red-600 hover:text-red-800 focus:outline-none focus:underline">Delete</button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-12 text-center">
                                            <p class="font-semibold text-gray-900">{{ $search !== '' ? 'No matching subjects' : 'No subject records yet' }}</p>
                                            <p class="mt-1 text-sm text-gray-500">{{ $search !== '' ? 'Try another search term or clear your search.' : 'Subject records will appear here after they are added.' }}</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if ($subjects->hasPages())
                        <div class="border-t border-gray-100 px-4 py-4 sm:px-5">{{ $subjects->links() }}</div>
                    @endif
                </section>
            </section>
        </div>
    </main>
</x-app-layout>
