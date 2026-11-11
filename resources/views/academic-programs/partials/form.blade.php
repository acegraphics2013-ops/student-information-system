<form method="POST"
      action="{{ $program
          ? route('academic-programs.update', $program)
          : route('academic-programs.store') }}">

    @csrf

    @if ($program)
        @method('PUT')
    @endif

    <div class="space-y-5">
        <div>
            <x-input-label for="department_id" value="Department" />

            <select id="department_id"
                    name="department_id"
                    required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">

                <option value="">Select a department</option>

                @foreach ($colleges as $college)
                    @if ($college->departments->isNotEmpty())
                        <optgroup label="{{ $college->name }} ({{ $college->abbreviation }})">
                            @foreach ($college->departments as $department)
                                <option value="{{ $department->id }}"
                                    @selected((string) old('department_id', $program?->department_id) === (string) $department->id)>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </optgroup>
                    @endif
                @endforeach
            </select>

            <x-input-error :messages="$errors->get('department_id')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="name" value="Program Name" />

            <x-text-input id="name"
                          name="name"
                          class="block mt-1 w-full"
                          :value="old('name', $program?->name)"
                          placeholder="e.g. Bachelor of Science in Information Technology"
                          maxlength="255"
                          required />

            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4 pt-4">
            <x-primary-button>
                {{ $program ? 'Update Program' : 'Save Program' }}
            </x-primary-button>

            <a href="{{ route('academic-programs.index') }}" class="text-gray-600">
                Cancel
            </a>
        </div>
    </div>
</form>
