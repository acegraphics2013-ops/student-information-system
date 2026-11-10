<form method="POST" action="{{ $enrollment
    ? route('enrollments.update', $enrollment)
    : route('enrollments.store') }}"
    @if (! $enrollment)
        x-data="{
            students: @js($enrollmentStudents),
            subjects: @js($subjects->map(fn ($subject) => [
                'id' => (string) $subject->id,
                'code' => $subject->subject_code,
                'name' => $subject->subject_name,
                'units' => (int) $subject->units,
            ])->values()),
            search: '',
            selectedStudentId: @js((string) old('student_id', $initialStudentId ?? '')),
            selectedSubjectIds: @js(array_map('strval', is_array(old('subject_ids', [])) ? old('subject_ids', []) : [])),
            status: @js(old('status', 'enrolled')),
            get filteredStudents() {
                const query = this.search.trim().toLowerCase();
                if (!query) return [];
                return this.students.filter((student) =>
                    `${student.student_number} ${student.first_name} ${student.last_name} ${student.email}`.toLowerCase().includes(query)
                ).slice(0, 8);
            },
            get selectedStudent() {
                return this.students.find((student) => String(student.id) === String(this.selectedStudentId));
            },
            get selectedUnits() {
                return this.subjects
                    .filter((subject) => this.selectedSubjectIds.includes(String(subject.id)))
                    .reduce((total, subject) => total + subject.units, 0);
            },
            get totalUnits() {
                return (this.selectedStudent?.enrolled_units ?? 0) + this.selectedUnits;
            },
            get displayedUnits() {
                return this.status === 'enrolled' ? this.totalUnits : this.selectedUnits;
            },
            hasSubject(subjectId) {
                return this.selectedStudent?.existing_subject_ids.includes(String(subjectId)) ?? false;
            },
            canSelect(subject) {
                return this.selectedSubjectIds.includes(String(subject.id))
                    || (!this.hasSubject(subject.id)
                        && this.selectedUnits + subject.units <= 10
                        && (this.status !== 'enrolled' || this.totalUnits + subject.units <= 10));
            },
            toggleSubject(subject) {
                const id = String(subject.id);
                if (this.selectedSubjectIds.includes(id)) {
                    this.selectedSubjectIds = this.selectedSubjectIds.filter((selectedId) => selectedId !== id);
                } else if (this.canSelect(subject)) {
                    this.selectedSubjectIds.push(id);
                }
            },
            selectStudent(student) {
                this.selectedStudentId = String(student.id);
                this.selectedSubjectIds = [];
                this.search = '';
            },
            changeStudent() {
                this.selectedStudentId = '';
                this.selectedSubjectIds = [];
                this.search = '';
            }
        }"
    @endif
>
    @csrf
    @if ($enrollment)
        @method('PUT')
    @endif

    <div class="space-y-5">
        @if ($enrollment)
            <div>
                <x-input-label for="student_id" value="Student" />
                <select id="student_id" name="student_id" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="">Select a student</option>
                    @foreach ($students as $student)
                        <option value="{{ $student->id }}"
                            @selected(old('student_id', $enrollment->student_id) == $student->id)>
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
                            @selected(old('subject_id', $enrollment->subject_id) == $subject->id)>
                            {{ $subject->subject_code }} — {{ $subject->subject_name }} ({{ $subject->units }} units)
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('subject_id')" class="mt-2" />
            </div>
        @else
            <section class="space-y-4" aria-labelledby="student-search-heading">
                <div>
                    <h3 id="student-search-heading" class="text-base font-semibold text-gray-900">1. Find a student</h3>
                    <p class="mt-1 text-sm text-gray-500">Search by student number, name, or email.</p>
                </div>

                <div x-show="!selectedStudent" class="relative">
                    <label for="student-search" class="sr-only">Search students</label>
                    <input id="student-search"
                           type="search"
                           x-model="search"
                           autocomplete="off"
                           placeholder="Start typing a student number or name..."
                           class="block w-full rounded-lg border-gray-300 py-3 pl-4 pr-4 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <div x-cloak x-show="search.trim().length > 0"
                         class="absolute z-20 mt-1 max-h-64 w-full overflow-y-auto rounded-lg border border-gray-200 bg-white shadow-lg"
                         role="listbox"
                         aria-label="Matching students">
                        <template x-for="student in filteredStudents" :key="student.id">
                            <button type="button"
                                    @click="selectStudent(student)"
                                    class="flex w-full items-center justify-between gap-4 border-b border-gray-100 px-4 py-3 text-left last:border-0 hover:bg-indigo-50 focus:bg-indigo-50 focus:outline-none">
                                <span>
                                    <span class="block text-sm font-semibold text-gray-900" x-text="`${student.first_name} ${student.last_name}`"></span>
                                    <span class="block text-xs text-gray-500" x-text="`${student.student_number} · ${student.email}`"></span>
                                </span>
                                <span class="shrink-0 text-xs font-medium text-indigo-600">Select</span>
                            </button>
                        </template>
                        <p x-show="filteredStudents.length === 0" class="px-4 py-3 text-sm text-gray-500">
                            No students match your search.
                        </p>
                    </div>
                    <input type="hidden" name="student_id" x-model="selectedStudentId">
                    <x-input-error :messages="$errors->get('student_id')" class="mt-2" />
                </div>

                <div x-cloak x-show="selectedStudent"
                     class="flex items-center justify-between gap-4 rounded-lg border border-indigo-100 bg-indigo-50 p-4">
                    <div>
                        <p class="font-semibold text-gray-900" x-text="selectedStudent ? `${selectedStudent.first_name} ${selectedStudent.last_name}` : ''"></p>
                        <p class="mt-1 text-sm text-gray-600" x-text="selectedStudent ? `${selectedStudent.student_number} · ${selectedStudent.email}` : ''"></p>
                        <p class="mt-1 text-xs font-medium text-indigo-700" x-text="selectedStudent ? `Current enrolled load: ${selectedStudent.enrolled_units} / 10 units` : ''"></p>
                    </div>
                    <button type="button" @click="changeStudent()"
                            class="shrink-0 rounded-md px-3 py-2 text-sm font-medium text-indigo-700 hover:bg-indigo-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        Change
                    </button>
                </div>
            </section>

            <section x-cloak x-show="selectedStudent" class="space-y-4" aria-labelledby="subject-select-heading">
                <div class="flex flex-wrap items-end justify-between gap-3">
                    <div>
                        <h3 id="subject-select-heading" class="text-base font-semibold text-gray-900">2. Choose subjects</h3>
                        <p class="mt-1 text-sm text-gray-500">Select multiple subjects, up to 10 units total.</p>
                    </div>
                    <p class="rounded-full bg-indigo-50 px-3 py-1 text-sm font-semibold text-indigo-800"
                       aria-live="polite">
                        <span x-text="displayedUnits"></span> / 10 units
                    </p>
                </div>
                <input type="hidden" name="status" x-model="status">
                <x-input-error :messages="$errors->get('subject_ids')" class="mt-2" />
                <div class="max-h-80 space-y-2 overflow-y-auto rounded-lg border border-gray-200 p-2">
                    <template x-for="subject in subjects" :key="subject.id">
                        <label class="flex cursor-pointer items-center gap-3 rounded-md border border-transparent p-3 transition"
                               :class="selectedSubjectIds.includes(String(subject.id)) ? 'border-indigo-200 bg-indigo-50' : 'hover:bg-gray-50'"
                               :for="`subject-${subject.id}`">
                            <input type="checkbox"
                                   :id="`subject-${subject.id}`"
                                   name="subject_ids[]"
                                   :value="subject.id"
                                   :checked="selectedSubjectIds.includes(String(subject.id))"
                                   :disabled="!canSelect(subject)"
                                   @change="toggleSubject(subject)"
                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 disabled:cursor-not-allowed disabled:opacity-50">
                            <span class="min-w-0 flex-1">
                                <span class="block text-sm font-semibold text-gray-900" x-text="`${subject.code} — ${subject.name}`"></span>
                                <span class="mt-0.5 block text-xs text-gray-500" x-text="hasSubject(subject.id) ? 'Already on this student’s record' : `${subject.units} ${subject.units === 1 ? 'unit' : 'units'}`"></span>
                            </span>
                            <span class="shrink-0 text-sm font-medium text-gray-600" x-text="`${subject.units} units`"></span>
                        </label>
                    </template>
                    <p x-show="subjects.length === 0" class="p-4 text-sm text-gray-500">No subjects are available.</p>
                </div>
                <x-input-error :messages="$errors->get('subject_ids.*')" class="mt-2" />

                <div>
                    <x-input-label for="status" value="Status" />
                    <select id="status" x-model="status" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        @foreach (['enrolled' => 'Enrolled', 'completed' => 'Completed', 'dropped' => 'Dropped'] as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-gray-500">The 10-unit limit applies to active enrolled subjects.</p>
                    <x-input-error :messages="$errors->get('status')" class="mt-2" />
                </div>
            </section>
        @endif

        @if ($enrollment)
            <div>
                <x-input-label for="status" value="Status" />
                <select id="status" name="status" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    @foreach (['enrolled' => 'Enrolled', 'completed' => 'Completed', 'dropped' => 'Dropped'] as $value => $label)
                        <option value="{{ $value }}"
                            @selected(old('status', $enrollment->status) === $value)>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('status')" class="mt-2" />
            </div>
        @endif

        @if ($students->isEmpty() || $subjects->isEmpty())
            <p class="rounded bg-amber-50 p-3 text-amber-800">
                Add at least one student and one subject before creating an enrollment.
            </p>
        @endif

        <div class="flex items-center gap-4 pt-2">
            <button type="submit"
                    @if ($enrollment)
                        @disabled($students->isEmpty() || $subjects->isEmpty())
                    @else
                        x-bind:disabled="!selectedStudent || selectedSubjectIds.length === 0 || selectedUnits > 10 || (status === 'enrolled' && totalUnits > 10)"
                    @endif
                    class="inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-indigo-700 focus:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                {{ $enrollment ? 'Update Enrollment' : 'Enroll in Selected Subjects' }}
            </button>
            <a href="{{ route('enrollments.index') }}" class="text-sm text-gray-600">Cancel</a>
        </div>
    </div>
</form>
