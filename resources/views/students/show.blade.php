<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-medium text-indigo-600">Student Information Management System</p>
            <h1 class="mt-1 text-2xl font-semibold tracking-tight text-gray-900">Student Details</h1>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-5xl space-y-6 sm:px-6 lg:px-8">

            @php
                $enrollments = $student->enrollments;
                $totalSubjects = $enrollments->count();
                $totalUnits = $enrollments->sum(fn ($enrollment) => (int) ($enrollment->subject?->units ?? 0));
                $semesterOrder = array_flip(\App\Models\Enrollment::SEMESTERS);
                $byYear = $enrollments
                    ->sort(function ($a, $b) use ($semesterOrder) {
                        return [($a->year_level ?? 0), ($semesterOrder[$a->semester] ?? 99)]
                            <=> [($b->year_level ?? 0), ($semesterOrder[$b->semester] ?? 99)];
                    })
                    ->groupBy(fn ($enrollment) => (int) ($enrollment->year_level ?? 0));
                $statusClasses = [
                    'enrolled' => 'bg-green-100 text-green-800',
                    'completed' => 'bg-blue-100 text-blue-800',
                    'dropped' => 'bg-red-100 text-red-800',
                ];
            @endphp

            <!-- Student profile -->
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">
                            {{ $student->first_name }} {{ $student->last_name }}
                        </h2>
                        <p class="mt-1 text-sm text-gray-500">
                            Student No. {{ $student->student_number }}
                        </p>
                    </div>

                    <div class="flex flex-wrap items-center gap-3">
                        <a href="{{ route('students.edit', $student) }}"
                           class="inline-flex min-h-11 items-center justify-center rounded-md bg-indigo-700 px-4 text-sm font-semibold text-white shadow-sm hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            Edit student
                        </a>
                        <a href="{{ route('students.index') }}"
                           class="inline-flex min-h-11 items-center justify-center rounded-md border border-gray-300 bg-white px-4 text-sm font-semibold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            Back to students
                        </a>
                    </div>
                </div>

                <dl class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <div class="rounded-lg bg-gray-50 p-4">
                        <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Email</dt>
                        <dd class="mt-1 break-words text-sm font-medium text-gray-900">{{ $student->email }}</dd>
                    </div>
                    <div class="rounded-lg bg-gray-50 p-4">
                        <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Degree Program</dt>
                        <dd class="mt-1 text-sm font-medium text-gray-900">{{ $student->course }}</dd>
                    </div>
                    <div class="rounded-lg bg-gray-50 p-4">
                        <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Year Level</dt>
                        <dd class="mt-1 text-sm font-medium text-gray-900">{{ $student->year_level }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Enrolled subjects -->
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Enrolled Subjects</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            All subjects loaded on this student&rsquo;s record, grouped by year level, then semester.
                        </p>
                    </div>

                    @if ($totalSubjects > 0)
                        <div class="flex flex-wrap items-center gap-2 text-sm">
                            <span class="rounded-full bg-indigo-50 px-3 py-1 font-semibold text-indigo-700">
                                {{ $totalSubjects }} {{ $totalSubjects === 1 ? 'subject' : 'subjects' }}
                            </span>
                            <span class="rounded-full bg-gray-100 px-3 py-1 font-semibold text-gray-700">
                                {{ $totalUnits }} total {{ $totalUnits === 1 ? 'unit' : 'units' }}
                            </span>
                        </div>
                    @endif
                </div>

                @forelse ($byYear as $year => $yearEnrollments)
                    @php
                        $yearUnits = $yearEnrollments->sum(fn ($enrollment) => (int) ($enrollment->subject?->units ?? 0));
                    @endphp

                    <section class="mt-6 overflow-hidden rounded-lg border border-gray-200">
                        <div class="flex flex-wrap items-center justify-between gap-2 border-b border-gray-100 bg-gray-50 px-4 py-3">
                            <h4 class="font-semibold text-gray-900">
                                {{ \App\Models\Enrollment::yearLevelLabel($year ?: null) }}
                            </h4>
                            <p class="text-sm text-gray-500">
                                {{ $yearEnrollments->count() }}
                                {{ $yearEnrollments->count() === 1 ? 'subject' : 'subjects' }}
                                &middot; {{ $yearUnits }} {{ $yearUnits === 1 ? 'unit' : 'units' }}
                            </p>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full border-collapse text-sm">
                                <thead class="text-xs uppercase tracking-wide text-gray-500">
                                    <tr>
                                        <th scope="col" class="px-4 py-3 text-left font-semibold">Code</th>
                                        <th scope="col" class="px-4 py-3 text-left font-semibold">Subject</th>
                                        <th scope="col" class="px-4 py-3 text-left font-semibold">Semester</th>
                                        <th scope="col" class="px-4 py-3 text-left font-semibold">Units</th>
                                        <th scope="col" class="px-4 py-3 text-left font-semibold">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach ($yearEnrollments as $enrollment)
                                        <tr class="hover:bg-gray-50">
                                            <td class="whitespace-nowrap px-4 py-3 font-medium text-gray-900">
                                                {{ $enrollment->subject?->subject_code ?? '—' }}
                                            </td>
                                            <td class="px-4 py-3 text-gray-700">
                                                {{ $enrollment->subject?->subject_name ?? 'Unknown subject' }}
                                            </td>
                                            <td class="whitespace-nowrap px-4 py-3 text-gray-600">
                                                {{ $enrollment->semester }}
                                            </td>
                                            <td class="whitespace-nowrap px-4 py-3 text-gray-600">
                                                {{ $enrollment->subject?->units ?? '—' }}
                                            </td>
                                            <td class="whitespace-nowrap px-4 py-3">
                                                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $statusClasses[$enrollment->status] ?? 'bg-gray-100 text-gray-700' }}">
                                                    {{ ucfirst($enrollment->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </section>
                @empty
                    <div class="mt-6 rounded-lg border border-dashed border-gray-300 bg-gray-50 px-4 py-10 text-center">
                        <p class="font-semibold text-gray-900">No subjects enrolled yet</p>
                        <p class="mt-1 text-sm text-gray-500">This student has no enrollment records.</p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>
