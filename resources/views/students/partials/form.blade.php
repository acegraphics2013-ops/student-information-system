
<form method="POST"
      action="{{ $student
          ? route('students.update', $student)
          : route('students.store') }}">

    @csrf

    @if($student)
        @method('PUT')
    @endif

    <div class="space-y-4">

        <div>
            <x-input-label for="student_number"
                           value="Student Number" />

            <x-text-input id="student_number"
                          name="student_number"
                          class="block mt-1 w-full"
                          :value="old('student_number', $student?->student_number)"
                          required />

            <x-input-error
                :messages="$errors->get('student_number')"
                class="mt-2" />
        </div>

        <div>
            <x-input-label for="first_name"
                           value="First Name" />

            <x-text-input id="first_name"
                          name="first_name"
                          class="block mt-1 w-full"
                          :value="old('first_name', $student?->first_name)"
                          required />

            <x-input-error
                :messages="$errors->get('first_name')"
                class="mt-2" />
        </div>

        <div>
            <x-input-label for="last_name"
                           value="Last Name" />

            <x-text-input id="last_name"
                          name="last_name"
                          class="block mt-1 w-full"
                          :value="old('last_name', $student?->last_name)"
                          required />

            <x-input-error
                :messages="$errors->get('last_name')"
                class="mt-2" />
        </div>

        <div>
            <x-input-label for="email"
                           value="Email Address" />

            <x-text-input id="email"
                          type="email"
                          name="email"
                          class="block mt-1 w-full"
                          :value="old('email', $student?->email)"
                          required />

            <x-input-error
                :messages="$errors->get('email')"
                class="mt-2" />
        </div>

        <div
            x-data="{
                colleges: @js($academicCatalog),
                collegeId: @js((string) ($selectedCollegeId ?? '')),
                course: @js(old('course', $student?->course ?? '')),
                legacyCourse: @js($legacyCourse),
                get selectedCollege() {
                    return this.colleges.find((college) => String(college.id) === String(this.collegeId));
                },
                get programs() {
                    return this.selectedCollege?.programs ?? [];
                },
                resetCourse() {
                    this.course = '';
                },
                get preservingLegacyCourse() {
                    return this.legacyCourse && this.course === this.legacyCourse;
                }
            }"
            class="space-y-4"
        >
            <div>
                <x-input-label for="college_id" value="College" />
                <select id="college_id"
                        name="college_id"
                        x-model="collegeId"
                        @change="resetCourse()"
                        :required="!preservingLegacyCourse"
                        class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                    <option value="">Select a college</option>
                    <template x-for="college in colleges" :key="college.id">
                        <option :value="String(college.id)" x-text="`${college.name} (${college.abbreviation})`"></option>
                    </template>
                </select>
                <x-input-error :messages="$errors->get('college_id')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="course" value="Degree Program" />
                <select id="course"
                        name="course"
                        x-model="course"
                        :required="!preservingLegacyCourse"
                        :disabled="!collegeId && !preservingLegacyCourse"
                        class="block mt-1 w-full border-gray-300 rounded-md shadow-sm disabled:bg-gray-100">
                    <option value="">Select a degree program</option>
                    @if ($legacyCourse)
                        <option value="{{ $legacyCourse }}" x-show="!collegeId">
                            Existing program (not in current catalog): {{ $legacyCourse }}
                        </option>
                    @endif
                    <template x-for="program in programs" :key="program.id">
                        <option :value="program.name" x-text="program.name"></option>
                    </template>
                </select>
                <p class="mt-1 text-sm text-gray-500">Degree programs are not class subjects or enrollments.</p>
                <x-input-error :messages="$errors->get('course')" class="mt-2" />
            </div>
        </div>

        <div>
            <x-input-label for="year_level"
                           value="Year Level" />

            <select id="year_level"
                    name="year_level"
                    required
                    class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                <option value="">Select Year Level</option>

                @for($year = 1; $year <= 5; $year++)
                    <option value="{{ $year }}"
                        @selected(old('year_level', $student?->year_level) == $year)>
                        Year {{ $year }}
                    </option>
                @endfor
            </select>

            <x-input-error
                :messages="$errors->get('year_level')"
                class="mt-2" />
        </div>

        <div class="flex items-center gap-4 pt-4">
            <x-primary-button>
                {{ $student ? 'Update Student' : 'Save Student' }}
            </x-primary-button>

            <a href="{{ route('students.index') }}"
               class="text-gray-600">
                Cancel
            </a>
        </div>

    </div>
</form>
