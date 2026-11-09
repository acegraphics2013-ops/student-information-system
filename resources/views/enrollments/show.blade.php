<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Enrollment Details</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="rounded-lg bg-white p-6 shadow">
                <dl class="space-y-4">
                    <div>
                        <dt class="font-semibold">Student</dt>
                        <dd>{{ $enrollment->student->student_number }} — {{ $enrollment->student->first_name }} {{ $enrollment->student->last_name }}</dd>
                    </div>
                    <div>
                        <dt class="font-semibold">Subject</dt>
                        <dd>{{ $enrollment->subject->subject_code }} — {{ $enrollment->subject->subject_name }}</dd>
                    </div>
                    <div>
                        <dt class="font-semibold">Status</dt>
                        <dd class="capitalize">{{ $enrollment->status }}</dd>
                    </div>
                </dl>
                <div class="mt-6 flex gap-4">
                    <a class="text-blue-600" href="{{ route('enrollments.index') }}">Back to Enrollments</a>
                    @if (auth()->user()->role === 'admin')
                        <a class="text-amber-600" href="{{ route('enrollments.edit', $enrollment) }}">Edit Enrollment</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
