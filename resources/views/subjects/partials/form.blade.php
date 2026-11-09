
<form method="POST"
      action="{{ $subject
          ? route('subjects.update', $subject)
          : route('subjects.store') }}">

    @csrf

    @if($subject)
        @method('PUT')
    @endif

    <div class="space-y-5">

        <div>
            <x-input-label for="subject_code"
                           value="Subject Code" />

            <x-text-input id="subject_code"
                          name="subject_code"
                          class="block mt-1 w-full"
                          :value="old('subject_code', $subject?->subject_code)"
                          placeholder="e.g. IT101"
                          maxlength="30"
                          required />

            <x-input-error
                :messages="$errors->get('subject_code')"
                class="mt-2" />
        </div>

        <div>
            <x-input-label for="subject_name"
                           value="Subject Name" />

            <x-text-input id="subject_name"
                          name="subject_name"
                          class="block mt-1 w-full"
                          :value="old('subject_name', $subject?->subject_name)"
                          placeholder="e.g. Introduction to Computing"
                          required />

            <x-input-error
                :messages="$errors->get('subject_name')"
                class="mt-2" />
        </div>

        <div>
            <x-input-label for="units"
                           value="Number of Units" />

            <x-text-input id="units"
                          type="number"
                          name="units"
                          min="1"
                          max="12"
                          step="1"
                          class="block mt-1 w-full"
                          :value="old('units', $subject?->units)"
                          required />

            <x-input-error
                :messages="$errors->get('units')"
                class="mt-2" />
        </div>

        <div class="flex items-center gap-4 pt-4">
            <x-primary-button>
                {{ $subject ? 'Update Subject' : 'Save Subject' }}
            </x-primary-button>

            <a href="{{ route('subjects.index') }}"
               class="text-gray-600">
                Cancel
            </a>
        </div>

    </div>
</form>
