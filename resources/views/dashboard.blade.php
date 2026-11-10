<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.15em] text-blue-600">NEMSU Tandag Campus</p>
                <h1 class="mt-1 text-2xl font-bold leading-tight text-gray-900 sm:text-3xl">Dashboard</h1>
                <p class="mt-1 text-sm text-gray-500">Student Information Management System · SY 2026–2027</p>
            </div>
            <div class="hidden sm:flex items-center gap-2">
                <span class="inline-flex items-center rounded-full bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-700 ring-1 ring-inset ring-blue-100">
                    <svg class="mr-1.5 h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ now()->format('M d, Y') }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-8 sm:py-10">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">

            {{-- Welcome / Account Card --}}
            <section class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-slate-900 via-blue-900 to-blue-800 p-6 text-white shadow-lg sm:p-8">
                <div class="absolute -right-10 -top-10 h-40 w-40 rounded-full bg-white/10 blur-2xl"></div>
                <div class="absolute -bottom-12 -left-12 h-48 w-48 rounded-full bg-blue-500/20 blur-3xl"></div>
                <div class="relative flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center gap-4">
                        <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-white/10 ring-1 ring-white/20 backdrop-blur">
                            <span class="text-2xl font-bold">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr(auth()->user()->name, 0, 1)) }}</span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-blue-200">Welcome back</p>
                            <h2 class="text-xl font-bold sm:text-2xl">{{ auth()->user()->name }}</h2>
                            <p class="mt-0.5 text-sm text-blue-100/90">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center rounded-full bg-white/15 px-4 py-1.5 text-sm font-semibold capitalize text-white ring-1 ring-white/20 backdrop-blur">
                            <svg class="mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            {{ auth()->user()->role ?? 'staff' }}
                        </span>
                        <a href="{{ route('profile.edit') }}" class="inline-flex items-center rounded-xl bg-white px-4 py-2 text-sm font-semibold text-blue-900 shadow-sm transition hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-white/50">
                            <svg class="mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            Profile
                        </a>
                    </div>
                </div>
            </section>

            {{-- Quick Stats --}}
            <section aria-labelledby="overview-heading">
                <div class="mb-4 flex items-end justify-between">
                    <div>
                        <h2 id="overview-heading" class="text-lg font-bold text-gray-900">System Overview</h2>
                        <p class="mt-0.5 text-sm text-gray-500">Current records in the information system</p>
                    </div>
                </div>

                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    {{-- Students --}}
                    <a href="{{ route('students.index') }}" class="group relative overflow-hidden rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100 transition hover:-translate-y-0.5 hover:shadow-md hover:ring-blue-100">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Students</p>
                                <p class="mt-2 text-3xl font-bold tracking-tight text-gray-900">{{ number_format($studentCount) }}</p>
                                <p class="mt-1 text-xs text-gray-400">Total student records</p>
                            </div>
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-blue-600 ring-1 ring-blue-100 transition group-hover:bg-blue-600 group-hover:text-white">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center text-sm font-medium text-blue-600 opacity-0 transition group-hover:opacity-100">
                            View students
                            <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </div>
                    </a>

                    {{-- Subjects --}}
                    <a href="{{ route('subjects.index') }}" class="group relative overflow-hidden rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100 transition hover:-translate-y-0.5 hover:shadow-md hover:ring-purple-100">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Subjects</p>
                                <p class="mt-2 text-3xl font-bold tracking-tight text-gray-900">{{ number_format($subjectCount) }}</p>
                                <p class="mt-1 text-xs text-gray-400">Total subject records</p>
                            </div>
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-purple-50 text-purple-600 ring-1 ring-purple-100 transition group-hover:bg-purple-600 group-hover:text-white">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center text-sm font-medium text-purple-600 opacity-0 transition group-hover:opacity-100">
                            View subjects
                            <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </div>
                    </a>

                    {{-- Enrollments --}}
                    <a href="{{ route('enrollments.index') }}" class="group relative overflow-hidden rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100 transition hover:-translate-y-0.5 hover:shadow-md hover:ring-amber-100 sm:col-span-2 lg:col-span-1">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Enrollments</p>
                                <p class="mt-2 text-3xl font-bold tracking-tight text-gray-900">{{ number_format($enrollmentCount) }}</p>
                                <p class="mt-1 text-xs text-gray-400">Total enrollment records</p>
                            </div>
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-50 text-amber-600 ring-1 ring-amber-100 transition group-hover:bg-amber-500 group-hover:text-white">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7l3 3 6-6"/></svg>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center text-sm font-medium text-amber-600 opacity-0 transition group-hover:opacity-100">
                            View enrollments
                            <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </div>
                    </a>
                </div>
            </section>

            {{-- Academic Catalog & Quick Actions --}}
            <div class="grid gap-6 lg:grid-cols-3">
                {{-- Academic Program Catalog --}}
                <section aria-labelledby="catalog-heading" class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100 sm:p-7 lg:col-span-2">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                        <div>
                            <div class="mb-2 inline-flex items-center gap-1.5 rounded-full bg-indigo-50 px-2.5 py-1 text-xs font-semibold text-indigo-700">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                {{ config('academic_catalog.campus') }} · SY {{ config('academic_catalog.school_year') }}
                            </div>
                            <h2 id="catalog-heading" class="text-lg font-bold text-gray-900">Academic Program Catalog</h2>
                            <p class="mt-1 text-sm text-gray-500">Browse colleges, departments, and degree programs. These programs are separate from class subjects.</p>
                        </div>
                        <a href="{{ route('academic-programs.index') }}" class="inline-flex shrink-0 items-center justify-center rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            Browse programs
                            <svg class="ml-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>
                    </div>

                    <div class="mt-6 grid gap-4 sm:grid-cols-3">
                        <div class="rounded-xl border border-gray-100 bg-gray-50/50 p-4">
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-100 text-indigo-600">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                </div>
                                <div>
                                    <p class="text-2xl font-bold text-gray-900">{{ number_format($collegeCount) }}</p>
                                    <p class="text-xs font-medium text-gray-500">Colleges</p>
                                </div>
                            </div>
                        </div>
                        <div class="rounded-xl border border-gray-100 bg-gray-50/50 p-4">
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 text-blue-600">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                                </div>
                                <div>
                                    <p class="text-2xl font-bold text-gray-900">{{ number_format($departmentCount) }}</p>
                                    <p class="text-xs font-medium text-gray-500">Departments</p>
                                </div>
                            </div>
                        </div>
                        <div class="rounded-xl border border-gray-100 bg-gray-50/50 p-4">
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                                </div>
                                <div>
                                    <p class="text-2xl font-bold text-gray-900">{{ number_format($programCount) }}</p>
                                    <p class="text-xs font-medium text-gray-500">Degree Programs</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- Quick Actions / Admin --}}
                <section class="space-y-4">
                    @if (auth()->user()->role === 'admin')
                        <div class="rounded-2xl bg-gradient-to-br from-indigo-600 to-blue-700 p-6 text-white shadow-sm">
                            <div class="mb-4 flex h-11 w-11 items-center justify-center rounded-xl bg-white/15 ring-1 ring-white/20">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3L4 7v5c0 5 3.4 8 8 9 4.6-1 8-4 8-9V7l-8-4zm-3 9l2 2 4-4"/></svg>
                            </div>
                            <h3 class="text-base font-bold">Administrator Tools</h3>
                            <p class="mt-1 text-sm text-indigo-100">Manage users, roles, and system settings from the admin panel.</p>
                            <a href="{{ route('admin.index') }}" class="mt-4 inline-flex w-full items-center justify-center rounded-xl bg-white px-4 py-2.5 text-sm font-semibold text-indigo-700 transition hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-white/50">
                                Open Admin Panel
                            </a>
                        </div>
                    @endif

                    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
                        <h3 class="text-base font-bold text-gray-900">Quick Actions</h3>
                        <p class="mt-1 text-sm text-gray-500">Jump to common tasks</p>
                        <div class="mt-4 space-y-2">
                            <a href="{{ route('students.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                                </span>
                                Manage Students
                            </a>
                            <a href="{{ route('subjects.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-purple-50 text-purple-600">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                </span>
                                Manage Subjects
                            </a>
                            <a href="{{ route('enrollments.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-50 text-amber-600">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7l3 3 6-6"/></svg>
                                </span>
                                Manage Enrollments
                            </a>
                            <a href="{{ route('academic-programs.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                </span>
                                Academic Programs
                            </a>
                        </div>
                    </div>
                </section>
            </div>

        </div>
    </div>
</x-app-layout>
