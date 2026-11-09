<form method="POST" action="{{ $enrollment
    ? route('enrollments.update', $enrollment)
    : route('enrollments.store') }}">
    @csrf
    @if ($enrollment)
        @method('PUT')
    @endif

    <div class="space-y-5">
        <div>
            <x-input-label for="student_id" value="Student" />
            <select id="student_id" name="student_id" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                <option value="">Select a student</option>
                @foreach ($students as $student)
                    <option value="{{ $student->id }}"
                        @selected(old('student_id', $enrollment?->student_id) == $student->id)>
                        {{ $student->student_number }} — {{ $student->first_name }} {{ $student->last_name }}
                    </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('student_id')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="subject_id" value="Subject" />
            <select id="subject_id" name="subject_id" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                <option value="">Select a subject</option>
                @foreach ($subjects as $subject)
                    <option value="{{ $subject->id }}"
                        @selected(old('subject_id', $enrollment?->subject_id) == $subject->id)>
                        {{ $subject->subject_code }} — {{ $subject->subject_name }}
                    </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('subject_id')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="status" value="Status" />
            <select id="status" name="status" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                @foreach (['enrolled' => 'Enrolled', 'completed' => 'Completed', 'dropped' => 'Dropped'] as $value => $label)
                    <option value="{{ $value }}"
                        @selected(old('status', $enrollment?->status ?? 'enrolled') === $value)>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('status')" class="mt-2" />
        </div>

        @if ($students->isEmpty() || $subjects->isEmpty())
            <p class="rounded bg-amber-50 p-3 text-amber-800">
                Add at least one student and one subject before creating an enrollment.
            </p>
        @endif

        <div class="flex items-center gap-4 pt-4">
            <button type="submit"
                    @disabled($students->isEmpty() || $subjects->isEmpty())
                    class="rounded bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 disabled:cursor-not-allowed disabled:opacity-50">
                {{ $enrollment ? 'Update Enrollment' : 'Save Enrollment' }}
            </button>
            <a href="{{ route('enrollments.index') }}" class="text-sm text-gray-600">Cancel</a>
        </div>
    </div>
</form>
