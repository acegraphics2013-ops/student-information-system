
<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-medium text-indigo-600">Student Information System</p>
            <h1 class="mt-1 text-2xl font-semibold leading-tight text-gray-800">
                {{ __('Dashboard') }}
            </h1>
        </div>
    </x-slot>



    <div class="py-10">
        <div class="mx-auto max-w-7xl space-y-8 px-4 sm:px-6 lg:px-8">
            <section class="rounded-lg bg-white p-6 shadow-sm sm:p-8">
                <p class="text-sm font-medium text-gray-500">Your account</p>
                <div class="mt-2 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-900">
                            {{ __('Welcome back, :name', ['name' => auth()->user()->name]) }}
                        </h2>
                        <p class="mt-1 text-sm text-gray-600">{{ auth()->user()->email }}</p>
                    </div>
                    <span class="inline-flex w-fit items-center rounded-full bg-indigo-50 px-3 py-1 text-sm font-medium capitalize text-indigo-700">
                        {{ auth()->user()->role ?? 'staff' }}
                    </span>
                </div>
            </section>

            <section aria-labelledby="overview-heading">
                <div class="mb-4">
                    <h2 id="overview-heading" class="text-lg font-semibold text-gray-900">System overview</h2>
                    <p class="mt-1 text-sm text-gray-600">Current records in the information system.</p>
                </div>

                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ([
                        ['label' => 'Students', 'count' => $studentCount],
                        ['label' => 'Subjects', 'count' => $subjectCount],
                        ['label' => 'Enrollments', 'count' => $enrollmentCount],
                    ] as $stat)
                        <article class="rounded-lg bg-white p-6 shadow-sm ring-1 ring-gray-200">
                            <p class="text-sm font-medium text-gray-500">{{ $stat['label'] }}</p>
                            <p class="mt-3 text-3xl font-semibold tracking-tight text-gray-900">{{ number_format($stat['count']) }}</p>
                            <p class="mt-1 text-sm text-gray-500">Total records</p>
                        </article>
                    @endforeach
                </div>
            </section>

            <section aria-labelledby="catalog-heading" class="rounded-lg border border-indigo-100 bg-indigo-50 p-6 sm:p-8">
                <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm font-medium text-indigo-700">{{ config('academic_catalog.campus') }} · School Year {{ config('academic_catalog.school_year') }}</p>
                        <h2 id="catalog-heading" class="mt-1 text-lg font-semibold text-indigo-950">Academic program catalog</h2>
                        <p class="mt-1 text-sm text-indigo-800">Browse colleges, departments, and degree programs. These programs are separate from class subjects.</p>
                    </div>
                    <a href="{{ route('academic-programs.index') }}" class="inline-flex w-fit items-center rounded-md bg-indigo-700 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Browse programs
                    </a>
                </div>
                <div class="mt-5 grid gap-4 sm:grid-cols-3">
                    @foreach ([
                        ['label' => 'Colleges', 'count' => $collegeCount],
                        ['label' => 'Departments', 'count' => $departmentCount],
                        ['label' => 'Degree programs', 'count' => $programCount],
                    ] as $catalogStat)
                        <div class="rounded-md bg-white p-4 ring-1 ring-indigo-100">
                            <p class="text-sm font-medium text-gray-500">{{ $catalogStat['label'] }}</p>
                            <p class="mt-2 text-2xl font-semibold text-gray-900">{{ number_format($catalogStat['count']) }}</p>
                        </div>
                    @endforeach
                </div>
            </section>

            @if (auth()->user()->role === 'admin')
                <section class="rounded-lg border border-indigo-100 bg-indigo-50 p-6 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <h2 class="font-semibold text-indigo-950">Administrator tools</h2>
                        <p class="mt-1 text-sm text-indigo-800">Open the administration area for your account.</p>
                    </div>
                    <a href="{{ route('admin.index') }}" class="mt-4 inline-flex items-center rounded-md bg-indigo-700 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:mt-0">
                        Open administrator panel
                    </a>
                </section>
            @endif
        </div>
    </div>
</x-app-layout>
