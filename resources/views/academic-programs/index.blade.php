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
                <p class="text-gray-700">Browse the catalog by college, department, or degree program. The catalog is for academic programs; it does not define subjects or student enrollments.</p>
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
                                        <li class="rounded-md bg-gray-50 px-3 py-2">{{ $program->name }}</li>
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
