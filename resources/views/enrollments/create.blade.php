<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">
                {{ filled($initialStudentId) ? 'Add Subjects to Student' : 'Enroll Student' }}
            </h2>
            @if (filled($initialStudentId))
                <p class="mt-1 text-sm text-gray-600">The student is selected. Choose the additional subjects to add to their load.</p>
            @endif
        </div>
    </x-slot>
    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="rounded-lg bg-white p-6 shadow">
                @include('enrollments.partials.form', ['enrollment' => null])
            </div>
        </div>
    </div>
</x-app-layout>
