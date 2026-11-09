<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Enroll Student</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="rounded-lg bg-white p-6 shadow">
                @include('enrollments.partials.form', ['enrollment' => null])
            </div>
        </div>
    </div>
</x-app-layout>
