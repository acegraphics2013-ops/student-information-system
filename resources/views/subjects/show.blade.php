
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            Subject Details
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">

                <h3 class="text-2xl font-bold mb-6">
                    {{ $subject->subject_name }}
                </h3>

                <div class="space-y-4">
                    <p>
                        <strong>Subject Code:</strong>
                        {{ $subject->subject_code }}
                    </p>

                    <p>
                        <strong>Subject Name:</strong>
                        {{ $subject->subject_name }}
                    </p>

                    <p>
                        <strong>Units:</strong>
                        {{ $subject->units }}
                    </p>

                    <p>
                        <strong>Enrollment Records:</strong>
                        {{ $subject->enrollments_count }}
                    </p>
                </div>

                <div class="mt-6 flex gap-4">
                    <a href="{{ route('subjects.index') }}"
                       class="text-blue-600">
                        Back to Subjects
                    </a>

                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('subjects.edit', $subject) }}"
                           class="text-amber-600">
                            Edit Subject
                        </a>
                    @endif
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
