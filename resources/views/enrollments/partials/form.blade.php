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
            semester: @js(old('semester', $semesters[0] ?? '1st Semester')),
            yearLevel: @js((string) old('year_level', $yearLevels[0] ?? 1)),
            step: @js(filled($initialStudentId ?? '') ? 2 : 1),
            notice: '',
            noticeTimer: null,
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
            get semesterUnits() {
                return this.selectedStudent?.units_by_semester?.[this.semester] ?? 0;
            },
            get totalUnits() {
                return this.semesterUnits + this.selectedUnits;
            },
            get displayedUnits() {
                return this.status === 'enrolled' ? this.totalUnits : this.selectedUnits;
            },
            get enrolledSubjectCodes() {
                const existingIds = this.selectedStudent?.existing_subject_ids ?? [];
                return this.subjects
                    .filter((subject) => existingIds.includes(String(subject.id)))
                    .map((subject) => subject.code);
            },
            get selectedSubjectCodes() {
                return this.subjects
                    .filter((subject) => this.selectedSubjectIds.includes(String(subject.id)))
                    .map((subject) => subject.code);
            },
            get statusLabel() {
                return { enrolled: 'Enrolled', completed: 'Completed', dropped: 'Dropped' }[this.status] ?? this.status;
            },
            hasSubject(subjectId) {
                return this.selectedStudent?.existing_subject_ids.includes(String(subjectId)) ?? false;
            },
            notifyAlreadyEnrolled(subject) {
                this.notice = `${subject.code} — ${subject.name} is already enrolled on this student’s record and cannot be enrolled again.`;
                clearTimeout(this.noticeTimer);
                this.noticeTimer = setTimeout(() => { this.notice = ''; }, 4000);
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
                this.notice = '';
            },
            changeStudent() {
                this.selectedStudentId = '';
                this.selectedSubjectIds = [];
                this.search = '';
                this.notice = '';
                this.step = 1;
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
            {{-- =========================================================
                 FULL ENROLLMENT PROCESS — 4 step wizard
                 1. Student -> 2. Subjects -> 3. Details -> 4. Review
                 ========================================================= --}}
            <input type="hidden" name="student_id" x-model="selectedStudentId">
            <input type="hidden" name="status" x-model="status">

            @if ($students->isEmpty() || $subjects->isEmpty())
                <p class="rounded bg-amber-50 p-3 text-amber-800">
                    Add at least one student and one subject before creating an enrollment.
                </p>
            @endif

            <!-- Step progress -->
            <ol x-cloak class="flex flex-wrap items-center gap-2 text-xs font-medium sm:text-sm" aria-label="Enrollment steps">
                @foreach (['Student', 'Subjects', 'Details', 'Review'] as $i => $label)
                    @php($n = $i + 1)
                    <li class="flex items-center gap-2">
                        <button type="button"
                                @click="if (step > {{ $n }}) step = {{ $n }}"
                                class="flex items-center gap-2 rounded-full px-3 py-1 transition"
                                :class="step === {{ $n }}
                                    ? 'bg-indigo-600 text-white'
                                    : (step > {{ $n }} ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-500')">
                            <span class="flex h-5 w-5 items-center justify-center rounded-full text-xs font-bold"
                                  :class="step === {{ $n }} ? 'bg-white/25' : 'bg-white/70'">{{ $n }}</span>
                            {{ $label }}
                        </button>
                        @if (! $loop->last)
                            <span class="h-px w-4 bg-gray-300 sm:w-6" aria-hidden="true"></span>
                        @endif
                    </li>
                @endforeach
            </ol>

            <!-- STEP 1: Find a student -->
            <section x-cloak x-show="step === 1" class="space-y-4" aria-labelledby="student-search-heading">
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
                    <x-input-error :messages="$errors->get('student_id')" class="mt-2" />
                </div>

                <div x-cloak x-show="selectedStudent"
                     class="flex items-center justify-between gap-4 rounded-lg border border-indigo-100 bg-indigo-50 p-4">
                    <div>
                        <p class="font-semibold text-gray-900" x-text="selectedStudent ? `${selectedStudent.first_name} ${selectedStudent.last_name}` : ''"></p>
                        <p class="mt-1 text-sm text-gray-600" x-text="selectedStudent ? `${selectedStudent.student_number} · ${selectedStudent.email}` : ''"></p>
                        <p class="mt-1 text-xs font-medium text-indigo-700" x-text="selectedStudent ? `Enrolled load in ${semester}: ${semesterUnits} / 10 units` : ''"></p>
                        <p class="mt-1 text-xs font-medium text-amber-700"
                           x-show="selectedStudent && enrolledSubjectCodes.length > 0"
                           x-text="`Subjects already on record: ${enrolledSubjectCodes.length} (${enrolledSubjectCodes.join(', ')})`"></p>
                    </div>
                    <button type="button" @click="changeStudent()"
                            class="shrink-0 rounded-md px-3 py-2 text-sm font-medium text-indigo-700 hover:bg-indigo-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        Change
                    </button>
                </div>

                <div class="flex items-center justify-end gap-3 pt-1">
                    <button type="button" @click="step = 2" :disabled="!selectedStudent"
                            class="inline-flex min-h-11 items-center justify-center rounded-md bg-indigo-600 px-4 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                        Next: Choose subjects
                    </button>
                </div>
            </section>

            <!-- STEP 2: Choose subjects -->
            <section x-cloak x-show="step === 2" class="space-y-4" aria-labelledby="subject-select-heading">
                <div class="flex flex-wrap items-end justify-between gap-3">
                    <div>
                        <h3 id="subject-select-heading" class="text-base font-semibold text-gray-900">2. Choose subjects</h3>
                        <p class="mt-1 text-sm text-gray-500">Select multiple subjects, up to 10 units per semester. Subjects already on the student’s record cannot be taken again.</p>
                    </div>
                    <p class="rounded-full bg-indigo-50 px-3 py-1 text-sm font-semibold text-indigo-800"
                       aria-live="polite">
                        <span x-text="displayedUnits"></span> / 10 units
                    </p>
                </div>
                <x-input-error :messages="$errors->get('subject_ids')" class="mt-2" />

                <!-- Already-enrolled notification -->
                <div x-cloak x-show="selectedStudent && enrolledSubjectCodes.length > 0"
                     class="flex items-start gap-3 rounded-lg border border-amber-200 bg-amber-50 p-3"
                     role="status" aria-live="polite">
                    <svg class="mt-0.5 h-5 w-5 shrink-0 text-amber-500" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"></path>
                    </svg>
                    <div class="min-w-0 text-sm text-amber-800">
                        <p class="font-semibold">
                            Already enrolled: this student has
                            <span x-text="enrolledSubjectCodes.length"></span>
                            <span x-text="enrolledSubjectCodes.length === 1 ? 'subject' : 'subjects'"></span>
                            loaded on their record.
                        </p>
                        <p class="mt-0.5">
                            <span class="font-medium" x-text="enrolledSubjectCodes.join(', ')"></span>
                            — shown as “Already enrolled” below and cannot be enrolled again. Select other subjects for
                            <span class="font-medium" x-text="semester"></span>.
                        </p>
                    </div>
                </div>

                <!-- Transient notice for clicked already-enrolled subjects -->
                <div x-cloak x-show="notice !== ''" x-transition.opacity.duration.200ms
                     class="rounded-lg border border-red-200 bg-red-50 px-4 py-2.5 text-sm font-medium text-red-700"
                     role="alert" aria-live="polite">
                    <span x-text="notice"></span>
                </div>

                <div class="max-h-80 space-y-2 overflow-y-auto rounded-lg border border-gray-200 p-2">
                    <template x-for="subject in subjects" :key="subject.id">
                        <label class="flex cursor-pointer items-center gap-3 rounded-md border border-transparent p-3 transition"
                               :class="selectedSubjectIds.includes(String(subject.id))
                                   ? 'border-indigo-200 bg-indigo-50'
                                   : (hasSubject(subject.id) ? 'border-amber-200 bg-amber-50/70' : 'hover:bg-gray-50')"
                               :for="`subject-${subject.id}`"
                               @click="hasSubject(subject.id) && notifyAlreadyEnrolled(subject)">
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
                                <span class="mt-0.5 block text-xs"
                                      :class="hasSubject(subject.id) ? 'font-medium text-amber-700' : 'text-gray-500'"
                                      x-text="hasSubject(subject.id) ? 'Already enrolled — cannot be enrolled again' : `${subject.units} ${subject.units === 1 ? 'unit' : 'units'}`"></span>
                            </span>
                            <span class="shrink-0 text-sm font-medium text-gray-600" x-text="`${subject.units} units`"></span>
                        </label>
                    </template>
                    <p x-show="subjects.length === 0" class="p-4 text-sm text-gray-500">No subjects are available.</p>
                </div>
                <x-input-error :messages="$errors->get('subject_ids.*')" class="mt-2" />

                <div class="flex items-center justify-between gap-3 pt-1">
                    <button type="button" @click="step = 1"
                            class="inline-flex min-h-11 items-center justify-center rounded-md border border-gray-300 bg-white px-4 text-sm font-semibold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Back
                    </button>
                    <button type="button" @click="step = 3" :disabled="selectedSubjectIds.length === 0"
                            class="inline-flex min-h-11 items-center justify-center rounded-md bg-indigo-600 px-4 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                        Next: Enrollment details
                    </button>
                </div>
            </section>

            <!-- STEP 3: Enrollment details -->
            <section x-cloak x-show="step === 3" class="space-y-4" aria-labelledby="details-heading">
                <div>
                    <h3 id="details-heading" class="text-base font-semibold text-gray-900">3. Enrollment details</h3>
                    <p class="mt-1 text-sm text-gray-500">Set the year, semester, and status for this enrollment.</p>
                </div>

                <div class="grid gap-4 sm:grid-cols-3">
                    <div>
                        <x-input-label for="year_level" value="Year Level" />
                        <select id="year_level" name="year_level" x-model="yearLevel" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            @foreach ($yearLevels as $year)
                                <option value="{{ $year }}">Year {{ $year }}</option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-gray-500">The year the student is enrolling for (starts at 1st year).</p>
                        <x-input-error :messages="$errors->get('year_level')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="semester" value="Semester" />
                        <select id="semester" name="semester" x-model="semester" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            @foreach ($semesters as $semester)
                                <option value="{{ $semester }}">{{ $semester }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('semester')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="status" value="Status" />
                        <select id="status" x-model="status" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            @foreach (['enrolled' => 'Enrolled', 'completed' => 'Completed', 'dropped' => 'Dropped'] as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-gray-500">The 10-unit limit applies to active enrolled subjects in the selected semester.</p>
                        <x-input-error :messages="$errors->get('status')" class="mt-2" />
                    </div>
                </div>

                <div class="flex items-center justify-between gap-3 pt-1">
                    <button type="button" @click="step = 2"
                            class="inline-flex min-h-11 items-center justify-center rounded-md border border-gray-300 bg-white px-4 text-sm font-semibold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Back
                    </button>
                    <button type="button" @click="step = 4"
                            class="inline-flex min-h-11 items-center justify-center rounded-md bg-indigo-600 px-4 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Next: Review
                    </button>
                </div>
            </section>

            <!-- STEP 4: Review & confirm -->
            <section x-cloak x-show="step === 4" class="space-y-4" aria-labelledby="review-heading">
                <div>
                    <h3 id="review-heading" class="text-base font-semibold text-gray-900">4. Review &amp; confirm</h3>
                    <p class="mt-1 text-sm text-gray-500">Check the details below, then enroll the student.</p>
                </div>

                <dl class="grid gap-4 rounded-lg border border-gray-200 bg-gray-50 p-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Student</dt>
                        <dd class="mt-1 text-sm font-medium text-gray-900"
                            x-text="selectedStudent ? `${selectedStudent.first_name} ${selectedStudent.last_name}` : '—'"></dd>
                        <dd class="text-xs text-gray-500"
                            x-text="selectedStudent ? `${selectedStudent.student_number} · ${selectedStudent.email}` : ''"></dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Subjects</dt>
                        <dd class="mt-1 text-sm font-medium text-gray-900"
                            x-text="selectedSubjectCodes.length ? selectedSubjectCodes.join(', ') : '—'"></dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Year Level</dt>
                        <dd class="mt-1 text-sm font-medium text-gray-900" x-text="'Year ' + yearLevel"></dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Semester</dt>
                        <dd class="mt-1 text-sm font-medium text-gray-900" x-text="semester"></dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Status</dt>
                        <dd class="mt-1 text-sm font-medium text-gray-900" x-text="statusLabel"></dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Total units</dt>
                        <dd class="mt-1 text-sm font-medium text-gray-900">
                            <span x-text="displayedUnits"></span> / 10
                        </dd>
                    </div>
                </dl>

                <x-input-error :messages="$errors->get('subject_ids')" class="mt-2" />

                <div class="flex items-center justify-between gap-3 pt-1">
                    <button type="button" @click="step = 3"
                            class="inline-flex min-h-11 items-center justify-center rounded-md border border-gray-300 bg-white px-4 text-sm font-semibold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Back
                    </button>
                    <div class="flex items-center gap-4">
                        <a href="{{ route('enrollments.index') }}" class="text-sm text-gray-600">Cancel</a>
                        <button type="submit"
                                x-bind:disabled="!selectedStudent || selectedSubjectIds.length === 0 || selectedUnits > 10 || (status === 'enrolled' && totalUnits > 10)"
                                class="inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-indigo-700 focus:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                            Enroll in Selected Subjects
                        </button>
                    </div>
                </div>
            </section>
        @endif

        @if ($enrollment)
            <div class="grid gap-4 sm:grid-cols-3">
                <div>
                    <x-input-label for="year_level" value="Year Level" />
                    <select id="year_level" name="year_level" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        @foreach ($yearLevels as $year)
                            <option value="{{ $year }}"
                                @selected((int) old('year_level', $enrollment->year_level) === $year)>
                                Year {{ $year }}
                            </option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-gray-500">The year the student was enrolled.</p>
                    <x-input-error :messages="$errors->get('year_level')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="semester" value="Semester" />
                    <select id="semester" name="semester" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        @foreach ($semesters as $semester)
                            <option value="{{ $semester }}"
                                @selected(old('semester', $enrollment->semester) === $semester)>
                                {{ $semester }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('semester')" class="mt-2" />
                </div>

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
            </div>
        @endif

        @if ($enrollment)
            @if ($students->isEmpty() || $subjects->isEmpty())
                <p class="rounded bg-amber-50 p-3 text-amber-800">
                    Add at least one student and one subject before creating an enrollment.
                </p>
            @endif

            <div class="flex items-center gap-4 pt-2">
                <button type="submit"
                        @disabled($students->isEmpty() || $subjects->isEmpty())
                        class="inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-indigo-700 focus:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                    Update Enrollment
                </button>
                <a href="{{ route('enrollments.index') }}" class="text-sm text-gray-600">Cancel</a>
            </div>
        @endif
    </div>
</form>
