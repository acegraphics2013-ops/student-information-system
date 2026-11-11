<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <p class="text-sm font-medium text-indigo-600">Student Subject Load</p>
                <h1 class="mt-1 text-2xl font-semibold text-gray-900">{{ $student->first_name }} {{ $student->last_name }}</h1>
            </div>
            <a href="{{ route('enrollments.index') }}" class="text-sm font-medium text-indigo-700 hover:text-indigo-900">Back to students</a>
        </div>
    </x-slot>

    @php
        $unitsBySemester = $student->enrollments
            ->where('status', 'enrolled')
            ->groupBy('semester')
            ->map(fn ($enrollments) => $enrollments->sum(fn ($enrollment) => (int) ($enrollment->subject?->units ?? 0)));
        $semesters = $student->enrollments->pluck('semester')->filter()->unique()->values();
        $semesterCount = $semesters->count();
    @endphp

    <main class="py-10">
        <div class="mx-auto max-w-7xl space-y-5 px-4 sm:px-6 lg:px-8">
            <section class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm sm:p-6">
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Student number</p>
                        <p class="mt-1 font-semibold text-gray-900">{{ $student->student_number }}</p>
                        <p class="mt-1 text-sm text-gray-600">{{ $student->email }}</p>
                    </div>
                    <div class="flex flex-wrap items-center gap-4 sm:justify-end">
                        <div class="rounded-lg bg-indigo-50 px-4 py-3 text-right">
                            <p class="text-xs font-medium uppercase tracking-wide text-indigo-700">Semesters enrolled</p>
                            <p class="mt-1 text-2xl font-bold text-indigo-900">{{ $semesterCount }} {{ $semesterCount === 1 ? 'semester' : 'semesters' }}</p>
                        </div>
                        <div class="rounded-lg bg-indigo-50 px-4 py-3 text-right">
                            <p class="text-xs font-medium uppercase tracking-wide text-indigo-700">Enrolled units / semester</p>
                            @forelse ($unitsBySemester as $semester => $units)
                                <p class="mt-1 text-sm font-semibold text-indigo-900">
                                    {{ $semester }}: {{ $units }} <span class="font-medium">/ 10</span>
                                </p>
                            @empty
                                <p class="mt-1 text-sm font-semibold text-indigo-900">0 <span class="font-medium">/ 10</span></p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </section>

            <section class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm" aria-label="Student subjects">
                <div class="flex items-center justify-between gap-3 border-b border-gray-100 px-4 py-4 sm:px-6">
                    <div>
                        <h2 class="font-semibold text-gray-900">All Subjects Loaded</h2>
                        <p class="mt-1 text-sm text-gray-500">{{ $student->enrollments->count() }} subject record(s)</p>
                    </div>
                    <a href="{{ route('enrollments.create', ['student_id' => $student->id]) }}" class="inline-flex min-h-10 items-center rounded-md bg-indigo-700 px-4 text-sm font-semibold text-white hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Add subjects
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 text-left text-xs uppercase tracking-wide text-gray-500">
                            <tr>
                                <th scope="col" class="px-4 py-3 font-semibold">Subject</th>
                                <th scope="col" class="px-4 py-3 font-semibold">Units</th>
                                <th scope="col" class="px-4 py-3 font-semibold">Semester</th>
                                <th scope="col" class="px-4 py-3 font-semibold">Year</th>
                                <th scope="col" class="px-4 py-3 font-semibold">Status</th>
                                <th scope="col" class="px-4 py-3 font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($student->enrollments as $enrollment)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-gray-700">
                                        <span class="font-medium text-gray-900">{{ $enrollment->subject?->subject_code ?? 'Subject removed' }}</span>
                                        @if ($enrollment->subject)
                                            <span class="text-gray-500">—</span>
                                            {{ $enrollment->subject->subject_name }}
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-3 text-gray-700">{{ $enrollment->subject?->units ?? '—' }}</td>
                                    <td class="whitespace-nowrap px-4 py-3">
                                        <span class="inline-flex rounded-full bg-indigo-50 px-2.5 py-1 text-xs font-semibold text-indigo-700">{{ $enrollment->semester }}</span>
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-3 text-gray-700">
                                        {{ \App\Models\Enrollment::yearLevelLabel($enrollment->year_level ? (int) $enrollment->year_level : null) }}
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-3">
                                        <span class="inline-flex rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold capitalize text-gray-700">{{ $enrollment->status }}</span>
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-3">
                                        <div class="flex items-center gap-3">
                                            <a href="{{ route('enrollments.edit', $enrollment) }}" class="font-medium text-indigo-700 hover:text-indigo-900">Edit</a>
                                            <form action="{{ route('enrollments.destroy', $enrollment) }}" method="POST" onsubmit="return confirm('Unload this subject from the student?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="font-medium text-red-600 hover:text-red-800">Unload</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </main>
</x-app-layout>
