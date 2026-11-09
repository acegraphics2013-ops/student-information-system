
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="text-sm font-semibold uppercase tracking-wide text-indigo-600">{{ $campus }} · School Year {{ $schoolYear }}</p>
                <h1 class="mt-1 text-2xl font-semibold tracking-tight text-gray-900">Administration dashboard</h1>
                <p class="mt-1 text-sm text-gray-500">Manage system access and monitor academic records.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.users.index') }}" class="inline-flex min-h-11 items-center rounded-md bg-indigo-700 px-4 text-sm font-semibold text-white shadow-sm hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Manage accounts</a>
                <a href="{{ route('academic-programs.index') }}" class="inline-flex min-h-11 items-center rounded-md border border-gray-300 bg-white px-4 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">View catalog</a>
            </div>
        </div>
    </x-slot>

    <main class="py-10">
        <div class="mx-auto max-w-7xl space-y-8 px-4 sm:px-6 lg:px-8">
            <section class="overflow-hidden rounded-xl border border-indigo-100 bg-gradient-to-br from-indigo-700 via-indigo-700 to-blue-800 p-6 shadow-sm sm:p-8">
                <div class="flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
                    <div class="max-w-2xl">
                        <p class="text-sm font-semibold uppercase tracking-wider text-indigo-100">Administrator workspace</p>
                        <h2 class="mt-2 text-2xl font-bold tracking-tight text-white sm:text-3xl">Welcome, {{ auth()->user()->name }}</h2>
                        <p class="mt-2 text-sm leading-6 text-indigo-100">A single place to manage access, review student records, and navigate the academic catalog.</p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('students.create') }}" class="inline-flex min-h-10 items-center rounded-md bg-white px-3 text-sm font-semibold text-indigo-800 shadow-sm hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-indigo-700">Add student</a>
                        <a href="{{ route('enrollments.create') }}" class="inline-flex min-h-10 items-center rounded-md border border-indigo-300 px-3 text-sm font-semibold text-white hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-indigo-700">New enrollment</a>
                    </div>
                </div>
            </section>

            <section aria-labelledby="statistics-heading">
                <div class="mb-4">
                    <h2 id="statistics-heading" class="text-lg font-semibold text-gray-900">System summary</h2>
                    <p class="mt-1 text-sm text-gray-600">Live totals grouped by account access, academic records, and catalog data.</p>
                </div>
                @php
                    $statisticGroups = [
                        ['title' => 'Accounts', 'labels' => ['Registered Users', 'Administrators', 'Staff Accounts'], 'accent' => 'border-indigo-500'],
                        ['title' => 'Academic records', 'labels' => ['Students', 'Subjects', 'Enrollments'], 'accent' => 'border-blue-500'],
                        ['title' => 'Academic catalog', 'labels' => ['Colleges', 'Departments', 'Degree Programs'], 'accent' => 'border-violet-500'],
                    ];
                @endphp
                <div class="grid gap-4 xl:grid-cols-3">
                    @foreach ($statisticGroups as $group)
                        <section class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm sm:p-5" aria-label="{{ $group['title'] }} statistics">
                            <h3 class="text-sm font-semibold text-gray-700">{{ $group['title'] }}</h3>
                            <div class="mt-3 grid grid-cols-3 gap-2">
                                @foreach (collect($statistics)->whereIn('label', $group['labels']) as $statistic)
                                    <article class="min-w-0 rounded-lg border-l-2 {{ $group['accent'] }} bg-gray-50 px-3 py-3">
                                        <p class="text-xs leading-4 text-gray-500">{{ $statistic['label'] }}</p>
                                        <p class="mt-2 text-2xl font-bold tracking-tight text-gray-900">{{ number_format($statistic['count']) }}</p>
                                    </article>
                                @endforeach
                            </div>
                        </section>
                    @endforeach
                </div>
            </section>

            <section aria-labelledby="management-heading">
                <div class="mb-4">
                    <h2 id="management-heading" class="text-lg font-semibold text-gray-900">Management shortcuts</h2>
                    <p class="mt-1 text-sm text-gray-600">Open existing modules to review records or start common tasks.</p>
                </div>
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ([
                        ['title' => 'Students', 'description' => 'Browse, add, edit, and view student records.', 'href' => route('students.index'), 'action' => 'View students', 'create' => route('students.create'), 'create_label' => 'Add student'],
                        ['title' => 'Subjects', 'description' => 'Manage subject codes, names, and units.', 'href' => route('subjects.index'), 'action' => 'View subjects', 'create' => route('subjects.create'), 'create_label' => 'Add subject'],
                        ['title' => 'Enrollments', 'description' => 'Review enrollment records and statuses.', 'href' => route('enrollments.index'), 'action' => 'View enrollments', 'create' => route('enrollments.create'), 'create_label' => 'Enroll student'],
                        ['title' => 'User accounts', 'description' => 'Search accounts and manage administrator/staff roles.', 'href' => route('admin.users.index'), 'action' => 'View users', 'create' => null, 'create_label' => null],
                    ] as $shortcut)
                        <article class="flex flex-col rounded-lg bg-white p-5 shadow-sm ring-1 ring-gray-200">
                            <h3 class="font-semibold text-gray-900">{{ $shortcut['title'] }}</h3>
                            <p class="mt-2 flex-1 text-sm text-gray-600">{{ $shortcut['description'] }}</p>
                            <div class="mt-5 flex flex-wrap gap-x-4 gap-y-2 text-sm font-semibold">
                                <a href="{{ $shortcut['href'] }}" class="text-indigo-700 hover:text-indigo-900">{{ $shortcut['action'] }}</a>
                                @if ($shortcut['create'])
                                    <a href="{{ $shortcut['create'] }}" class="text-gray-600 hover:text-gray-900">{{ $shortcut['create_label'] }}</a>
                                @endif
                            </div>
                        </article>
                    @endforeach
                    <article class="flex flex-col rounded-lg bg-white p-5 shadow-sm ring-1 ring-gray-200 sm:col-span-2 lg:col-span-4">
                        <h3 class="font-semibold text-gray-900">Academic programs</h3>
                        <p class="mt-2 text-sm text-gray-600">Browse the supplied College → Department → Degree Program catalog. Programs are distinct from class subjects and enrollment records.</p>
                        <a href="{{ route('academic-programs.index') }}" class="mt-4 w-fit text-sm font-semibold text-indigo-700 hover:text-indigo-900">Open academic programs</a>
                    </article>
                </div>
            </section>

            <section aria-labelledby="reports-heading" class="grid gap-6 lg:grid-cols-2">
                <article class="rounded-lg bg-white p-5 shadow-sm ring-1 ring-gray-200">
                    <h2 id="reports-heading" class="text-lg font-semibold text-gray-900">Students by academic program</h2>
                    <p class="mt-1 text-sm text-gray-500">Counts match stored student course names exactly to a single catalog program.</p>
                    <div class="mt-4 space-y-3">
                        @forelse ($studentsByProgram as $row)
                            <div class="flex items-start justify-between gap-4 border-b border-gray-100 pb-3 last:border-0">
                                <div><p class="text-sm font-medium text-gray-800">{{ $row['name'] }}</p><p class="mt-1 text-xs text-gray-500">{{ $row['college'] }}</p></div>
                                <span class="rounded-full bg-indigo-50 px-2.5 py-1 text-sm font-semibold text-indigo-800">{{ number_format($row['count']) }}</span>
                            </div>
                        @empty
                            <p class="rounded-md bg-gray-50 p-4 text-sm text-gray-600">No student records currently match a unique program in the catalog.</p>
                        @endforelse
                        @if ($unmatchedStudentCourseCount > 0)
                            <p class="text-xs text-amber-700">{{ number_format($unmatchedStudentCourseCount) }} student record(s) use a legacy, unmatched, or ambiguous course name and are not assigned to a catalog program in this report.</p>
                        @endif
                    </div>
                </article>

                <article class="rounded-lg bg-white p-5 shadow-sm ring-1 ring-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Students by year level</h2>
                    <div class="mt-4 space-y-3">
                        @forelse ($studentsByYearLevel as $row)
                            <div class="flex items-center justify-between border-b border-gray-100 pb-3 last:border-0">
                                <span class="text-sm text-gray-700">Year {{ $row->year_level }}</span>
                                <span class="text-sm font-semibold text-gray-900">{{ number_format($row->total) }}</span>
                            </div>
                        @empty
                            <p class="rounded-md bg-gray-50 p-4 text-sm text-gray-600">No student records are available for this report.</p>
                        @endforelse
                    </div>
                    <h3 class="mt-6 border-t border-gray-100 pt-5 font-semibold text-gray-900">Enrollment status</h3>
                    <div class="mt-3 space-y-3">
                        @forelse ($enrollmentsByStatus as $row)
                            <div class="flex items-center justify-between border-b border-gray-100 pb-3 last:border-0">
                                <span class="text-sm capitalize text-gray-700">{{ $row->status }}</span>
                                <span class="text-sm font-semibold text-gray-900">{{ number_format($row->total) }}</span>
                            </div>
                        @empty
                            <p class="rounded-md bg-gray-50 p-4 text-sm text-gray-600">No enrollment records are available for this report.</p>
                        @endforelse
                    </div>
                </article>
            </section>

            <section aria-labelledby="catalog-summary-heading" class="rounded-lg bg-white p-5 shadow-sm ring-1 ring-gray-200 sm:p-6">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <h2 id="catalog-summary-heading" class="text-lg font-semibold text-gray-900">Academic catalog overview</h2>
                        <p class="mt-1 text-sm text-gray-500">{{ $campus }} · School Year {{ $schoolYear }}</p>
                    </div>
                    <a href="{{ route('academic-programs.index') }}" class="text-sm font-semibold text-indigo-700 hover:text-indigo-900">Browse full catalog</a>
                </div>
                <div class="mt-5 overflow-x-auto">
                    <table class="min-w-full text-left text-sm">
                        <thead class="border-b border-gray-200 text-xs uppercase tracking-wide text-gray-500">
                            <tr><th scope="col" class="py-3 pe-4">College</th><th scope="col" class="py-3 pe-4">Abbreviation</th><th scope="col" class="py-3 pe-4">Degree programs</th><th scope="col" class="py-3 pe-4">Students matched</th><th scope="col" class="py-3">Catalog details</th></tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($programCountsByCollege as $item)
                                <tr>
                                    <td class="py-3 pe-4 font-medium text-gray-800">{{ $item['college']->name }}</td>
                                    <td class="py-3 pe-4 text-gray-600">{{ $item['college']->abbreviation }}</td>
                                    <td class="py-3 pe-4 text-gray-600">{{ $item['program_count'] }}</td>
                                    <td class="py-3 pe-4 text-gray-600">{{ number_format($studentsByCollege->firstWhere('college', $item['college']->name)['count'] ?? 0) }}</td>
                                    <td class="py-3">
                                        <details>
                                            <summary class="cursor-pointer font-medium text-indigo-700 hover:text-indigo-900">Departments and programs</summary>
                                            <div class="mt-3 space-y-3">
                                                @foreach ($item['college']->departments as $department)
                                                    <div>
                                                        <p class="text-sm font-semibold text-gray-800">{{ $department->name }}</p>
                                                        <ul class="mt-1 list-inside list-disc text-sm text-gray-600">
                                                            @foreach ($department->programs as $program)
                                                                <li>{{ $program->name }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </details>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="py-5 text-center text-gray-500">The academic catalog has not been seeded.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            <section aria-labelledby="recent-heading">
                <div class="mb-4">
                    <h2 id="recent-heading" class="text-lg font-semibold text-gray-900">Recent records</h2>
                    <p class="mt-1 text-sm text-gray-500">These are records ordered by their creation timestamps, not a history of user actions.</p>
                </div>
                <div class="grid gap-6 lg:grid-cols-3">
                    <article class="rounded-lg bg-white p-5 shadow-sm ring-1 ring-gray-200">
                        <h3 class="font-semibold text-gray-900">Recently created students</h3>
                        <ul class="mt-4 space-y-3">
                            @forelse ($recentStudents as $student)
                                <li class="border-b border-gray-100 pb-3 last:border-0"><a href="{{ route('students.show', $student) }}" class="text-sm font-medium text-indigo-700 hover:text-indigo-900">{{ $student->first_name }} {{ $student->last_name }}</a><p class="mt-1 text-xs text-gray-500">{{ $student->student_number }} · {{ $student->created_at?->diffForHumans() ?? 'Date unavailable' }}</p></li>
                            @empty
                                <li class="text-sm text-gray-500">No student records yet.</li>
                            @endforelse
                        </ul>
                    </article>
                    <article class="rounded-lg bg-white p-5 shadow-sm ring-1 ring-gray-200">
                        <h3 class="font-semibold text-gray-900">Recently created enrollments</h3>
                        <ul class="mt-4 space-y-3">
                            @forelse ($recentEnrollments as $enrollment)
                                <li class="border-b border-gray-100 pb-3 last:border-0"><a href="{{ route('enrollments.show', $enrollment) }}" class="text-sm font-medium text-indigo-700 hover:text-indigo-900">{{ $enrollment->student?->first_name }} {{ $enrollment->student?->last_name }}</a><p class="mt-1 text-xs text-gray-500">{{ $enrollment->subject?->subject_code }} · {{ $enrollment->created_at?->diffForHumans() ?? 'Date unavailable' }}</p></li>
                            @empty
                                <li class="text-sm text-gray-500">No enrollment records yet.</li>
                            @endforelse
                        </ul>
                    </article>
                    <article class="rounded-lg bg-white p-5 shadow-sm ring-1 ring-gray-200">
                        <h3 class="font-semibold text-gray-900">Recently registered users</h3>
                        <ul class="mt-4 space-y-3">
                            @forelse ($recentUsers as $user)
                                <li class="border-b border-gray-100 pb-3 last:border-0"><a href="{{ route('admin.users.show', $user) }}" class="text-sm font-medium text-indigo-700 hover:text-indigo-900">{{ $user->name }}</a><p class="mt-1 text-xs text-gray-500">{{ $user->role === 'admin' ? 'Administrator' : 'Staff' }} · {{ $user->created_at?->diffForHumans() ?? 'Date unavailable' }}</p></li>
                            @empty
                                <li class="text-sm text-gray-500">No user accounts yet.</li>
                            @endforelse
                        </ul>
                    </article>
                </div>
            </section>
        </div>
    </main>
</x-app-layout>
