<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-medium text-indigo-600">Student Information Management System</p>
            <p class="mt-1 text-sm font-medium text-gray-500">{{ $campus }} · School Year {{ $schoolYear }}</p>
            <h1 class="mt-1 text-2xl font-semibold leading-tight text-gray-800">Academic Programs</h1>
        </div>
    </x-slot>

    <main class="py-10">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            <section class="rounded-lg bg-white p-6 shadow-sm">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                    <p class="max-w-3xl text-gray-700">Browse the catalog by college, department, or degree program. The catalog is for academic programs; it does not define subjects or student enrollments.</p>
                    @if (auth()->user()->role === 'admin')
                        <a href="{{ route('academic-programs.create') }}" class="inline-flex min-h-11 shrink-0 items-center justify-center rounded-md bg-indigo-700 px-4 text-sm font-semibold text-white shadow-sm hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            Add program
                        </a>
                    @endif
                </div>
                <div class="mt-5 grid gap-4 sm:grid-cols-3">
                    @foreach ([
                        ['label' => 'Colleges', 'count' => $collegeCount],
                        ['label' => 'Departments', 'count' => $departmentCount],
                        ['label' => 'Degree programs', 'count' => $programCount],
                    ] as $stat)
                        <div class="rounded-md bg-gray-50 p-4">
                            <p class="text-sm font-medium text-gray-500">{{ $stat['label'] }}</p>
                            <p class="mt-1 text-2xl font-semibold text-gray-900">{{ number_format($stat['count']) }}</p>
                        </div>
                    @endforeach
                </div>
            </section>

            <form method="GET" action="{{ route('academic-programs.index') }}" class="grid gap-4 rounded-lg bg-white p-6 shadow-sm sm:grid-cols-[1fr_2fr_auto_auto] sm:items-end">
                <div>
                    <label for="college-filter" class="block text-sm font-medium text-gray-700">College</label>
                    <select id="college-filter" name="college_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">All colleges</option>
                        @foreach ($collegeOptions as $college)
                            <option value="{{ $college->id }}" @selected((string) $selectedCollegeId === (string) $college->id)>
                                {{ $college->name }} ({{ $college->abbreviation }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="program-search" class="block text-sm font-medium text-gray-700">Search programs</label>
                    <input id="program-search" name="search" type="search" maxlength="100" value="{{ $search }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Search degree program names">
                </div>
                <button type="submit" class="inline-flex justify-center rounded-md bg-indigo-700 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Filter</button>
                <a href="{{ route('academic-programs.index') }}" class="inline-flex justify-center rounded-md border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">Clear</a>
            </form>

            @forelse ($colleges as $college)
                <section class="overflow-hidden rounded-lg bg-white shadow-sm ring-1 ring-gray-200">
                    <header class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-900">{{ $college->name }} <span class="text-sm font-medium text-gray-500">({{ $college->abbreviation }})</span></h2>
                    </header>
                    <div class="divide-y divide-gray-200">
                        @foreach ($college->departments as $department)
                            <div class="px-6 py-5">
                                <h3 class="font-medium text-gray-800">{{ $department->name }}</h3>
                                <ul class="mt-3 grid gap-2 text-sm text-gray-600 sm:grid-cols-2">
                                    @foreach ($department->programs as $program)
                                        <li class="flex items-start justify-between gap-3 rounded-md bg-gray-50 px-3 py-2">
                                            <span class="min-w-0">{{ $program->name }}</span>
                                            @if (auth()->user()->role === 'admin')
                                                <div class="flex shrink-0 items-center gap-3 text-xs font-semibold">
                                                    <a href="{{ route('academic-programs.edit', $program) }}" class="text-indigo-700 hover:text-indigo-900 focus:outline-none focus:underline">Edit</a>
                                                    <form method="POST" action="{{ route('academic-programs.destroy', $program) }}" onsubmit="return confirm('Delete this academic program?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-800 focus:outline-none focus:underline">Delete</button>
                                                    </form>
                                                </div>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach
                    </div>
                </section>
            @empty
                <div class="rounded-lg border border-dashed border-gray-300 bg-white p-8 text-center text-gray-600">
                    No academic programs match the selected filters.
                </div>
            @endforelse

            <p class="text-sm text-gray-500">{{ $sourceNote }}</p>
        </div>
    </main>
</x-app-layout>
