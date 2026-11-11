<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-medium text-indigo-600">Student Information Management System</p>
            <h1 class="mt-1 text-2xl font-semibold tracking-tight text-gray-900">Enrollment Management</h1>
        </div>
    </x-slot>

    <main class="py-10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <section class="space-y-5 rounded-xl border border-gray-200 bg-white p-4 shadow-sm sm:p-6">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Enrollments</h2>
                        <p class="mt-1 text-sm text-gray-500">Search for a student, then view their complete subject load.</p>
                    </div>
                    <a href="{{ route('enrollments.create') }}" class="inline-flex min-h-11 items-center justify-center rounded-md bg-indigo-700 px-4 text-sm font-semibold text-white shadow-sm hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Enroll student
                    </a>
                </div>

                <form method="GET" action="{{ route('enrollments.index') }}" role="search" class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                    <label for="enrollment-search" class="block text-sm font-semibold text-gray-700">Search enrollments</label>
                    <div class="mt-2 flex flex-col gap-2 sm:flex-row">
                        <input id="enrollment-search" name="search" type="search" value="{{ $search }}" maxlength="100" placeholder="Student name or number, subject, semester, or status" class="min-h-11 min-w-0 flex-1 rounded-md border-gray-300 bg-white shadow-sm placeholder:text-gray-400 focus:border-indigo-500 focus:ring-indigo-500">
                        <button type="submit" class="inline-flex min-h-11 items-center justify-center rounded-md bg-gray-900 px-5 text-sm font-semibold text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-700 focus:ring-offset-2">Search</button>
                        @if ($search !== '')
                            <a href="{{ route('enrollments.index') }}" class="inline-flex min-h-11 items-center justify-center rounded-md border border-gray-300 bg-white px-4 text-sm font-semibold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Clear</a>
                        @endif
                    </div>
                </form>

                <section class="overflow-hidden rounded-lg border border-gray-200" aria-label="Enrollment results">
                    <div class="flex flex-wrap items-center justify-between gap-2 border-b border-gray-100 px-4 py-3 sm:px-5">
                        <h3 class="font-semibold text-gray-900">Enrollment records</h3>
                        <p class="text-sm text-gray-500">
                            @if ($students->total() > 0)
                                Showing {{ $students->firstItem() }}–{{ $students->lastItem() }} of {{ number_format($students->total()) }} students
                            @else
                                No records found
                            @endif
                        </p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 text-left text-xs uppercase tracking-wide text-gray-500">
                                <tr>
                                    <th scope="col" class="px-4 py-3 font-semibold">Student</th>
                                    <th scope="col" class="px-4 py-3 font-semibold">Semesters Enrolled</th>
                                    <th scope="col" class="px-4 py-3 font-semibold">Subjects Loaded</th>
                                    <th scope="col" class="px-4 py-3 font-semibold">Enrolled Units (per semester)</th>
                                    <th scope="col" class="px-4 py-3 font-semibold">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse ($students as $student)
                                    <tr class="hover:bg-gray-50">
                                        <td class="whitespace-nowrap px-4 py-3 text-gray-700">
                                            <span class="font-medium text-gray-900">{{ $student->student_number }}</span>
                                            <span class="text-gray-500">—</span>
                                            {{ $student->first_name }} {{ $student->last_name }}
                                        </td>
                                        <td class="px-4 py-3">
                                            @php
                                                $semesters = $student->enrollments->pluck('semester')->filter()->unique()->values();
                                                $semesterCount = $semesters->count();
                                            @endphp
                                            <p class="text-sm font-semibold text-gray-900">
                                                {{ $semesterCount }} {{ $semesterCount === 1 ? 'semester' : 'semesters' }}
                                                <span class="font-normal text-gray-500">enrolled</span>
                                            </p>
                                            <div class="mt-1.5 flex flex-wrap gap-1.5">
                                                @foreach ($semesters as $semester)
                                                    <span class="inline-flex rounded-full bg-indigo-50 px-2.5 py-1 text-xs font-semibold text-indigo-700">{{ $semester }}</span>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-gray-700">
                                            {{ $student->enrollments->count() }}
                                        </td>
                                        <td class="px-4 py-3 text-gray-700">
                                            @php
                                                // The 10-unit cap is per semester, so show each
                                                // semester's active load instead of a cross-semester total.
                                                $unitsBySemester = $student->enrollments
                                                    ->where('status', 'enrolled')
                                                    ->groupBy('semester')
                                                    ->map(fn ($enrollments) => $enrollments->sum(fn ($enrollment) => (int) ($enrollment->subject?->units ?? 0)));
                                            @endphp

                                            @forelse ($unitsBySemester as $semester => $units)
                                                <p class="text-sm">
                                                    <span class="text-gray-500">{{ $semester }}:</span>
                                                    <span class="font-semibold {{ $units > 10 ? 'text-red-600' : 'text-gray-900' }}">{{ $units }}</span>
                                                    <span class="text-gray-500">/ 10</span>
                                                </p>
                                            @empty
                                                <span class="text-gray-400">None</span>
                                            @endforelse
                                        </td>
                                        <td class="whitespace-nowrap px-4 py-3">
                                            <a href="{{ route('enrollments.show', $student->enrollments->first()) }}" class="font-medium text-indigo-700 hover:text-indigo-900 focus:outline-none focus:underline">View subjects</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-12 text-center">
                                            <p class="font-semibold text-gray-900">{{ $search !== '' ? 'No matching students' : 'No enrollment records yet' }}</p>
                                            <p class="mt-1 text-sm text-gray-500">{{ $search !== '' ? 'Try another student, subject, semester, or status search.' : 'Enrollment records will appear here after students are enrolled.' }}</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if ($students->hasPages())
                        <div class="border-t border-gray-100 px-4 py-4 sm:px-5">{{ $students->links() }}</div>
                    @endif
                </section>
            </section>
        </div>
    </main>
</x-app-layout>
