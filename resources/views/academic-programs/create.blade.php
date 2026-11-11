<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-medium text-indigo-600">Student Information Management System</p>
            <h1 class="mt-1 text-2xl font-semibold tracking-tight text-gray-900">Add Academic Program</h1>
        </div>
    </x-slot>

    <main class="py-10">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                @include('academic-programs.partials.form', ['program' => null])
            </div>
        </div>
    </main>
</x-app-layout>
