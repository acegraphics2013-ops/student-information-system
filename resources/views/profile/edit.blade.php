<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-semibold uppercase tracking-wide text-indigo-600">Account settings</p>
            <h1 class="mt-1 text-2xl font-semibold tracking-tight text-gray-900">Your profile</h1>
            <p class="mt-1 text-sm text-gray-500">Manage your personal information, photo, and account security.</p>
        </div>
    </x-slot>

    <main class="py-10">
        <div class="mx-auto max-w-5xl space-y-6 px-4 sm:px-6 lg:px-8">
            @if ($errors->any())
                <div role="alert" class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-800">
                    <p class="font-semibold">Please review the highlighted information.</p>
                    <ul class="mt-2 list-inside list-disc">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif

            @if (session('status'))
                <div role="status" class="rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-800">
                    @switch(session('status'))
                        @case('profile-updated')Your profile information has been saved.@break
                        @case('profile-photo-updated')Your display photo has been updated.@break
                        @case('profile-photo-deleted')Your display photo has been removed.@break
                        @default Your account settings have been updated.
                    @endswitch
                </div>
            @endif

            <section class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="flex flex-col gap-5 bg-gradient-to-r from-indigo-50 via-white to-white p-6 sm:flex-row sm:items-center sm:p-8">
                    <div class="flex h-20 w-20 shrink-0 items-center justify-center overflow-hidden rounded-full bg-indigo-100 text-2xl font-semibold text-indigo-700 ring-4 ring-white shadow-sm">
                        @if ($user->profile_photo_path)
                            <img src="{{ asset('storage/'.$user->profile_photo_path) }}" alt="{{ $user->name }} display photo" class="h-full w-full object-cover">
                        @else
                            {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($user->name, 0, 1)) }}
                        @endif
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-center gap-3">
                            <h2 class="truncate text-xl font-semibold text-gray-900">{{ $user->name }}</h2>
                            <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold {{ $user->role === 'admin' ? 'bg-indigo-100 text-indigo-800' : 'bg-gray-100 text-gray-700' }}">
                                {{ $user->role === 'admin' ? 'Administrator' : 'Staff' }}
                            </span>
                        </div>
                        <p class="mt-1 break-all text-sm text-gray-600">{{ $user->email }}</p>
                        <p class="mt-2 text-xs text-gray-500">Your account details are visible only to you and authorized administrators.</p>
                    </div>
                </div>
            </section>

            <section class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm sm:p-7">
                <div class="flex flex-col gap-6 sm:flex-row sm:items-start">
                    <div class="sm:w-64">
                        <h2 class="text-base font-semibold text-gray-900">Display photo</h2>
                        <p class="mt-1 text-sm leading-6 text-gray-500">Choose a clear profile image. JPG, PNG, or WebP up to 2 MB.</p>
                    </div>
                    <div class="min-w-0 flex-1">
                        <form method="POST" action="{{ route('profile.photo.update') }}" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <div>
                                <label for="profile_photo" class="block text-sm font-medium text-gray-700">Upload photo</label>
                                <input id="profile_photo" name="profile_photo" type="file" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" required class="mt-2 block w-full rounded-md border border-gray-300 bg-white text-sm text-gray-700 file:mr-4 file:min-h-11 file:border-0 file:bg-gray-50 file:px-4 file:font-semibold file:text-gray-700 hover:file:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                @error('profile_photo')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div class="flex flex-wrap items-center gap-3">
                                <button type="submit" class="inline-flex min-h-10 items-center justify-center rounded-md bg-indigo-700 px-4 text-sm font-semibold text-white shadow-sm hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Upload photo</button>
                                @if ($user->profile_photo_path)
                                    <button form="remove-profile-photo" type="submit" class="inline-flex min-h-10 items-center justify-center rounded-md border border-gray-300 bg-white px-4 text-sm font-semibold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Remove photo</button>
                                @endif
                            </div>
                        </form>
                        @if ($user->profile_photo_path)
                            <form id="remove-profile-photo" method="POST" action="{{ route('profile.photo.destroy') }}" onsubmit="return confirm('Remove your display photo?')">
                                @csrf
                                @method('DELETE')
                            </form>
                        @endif
                    </div>
                </div>
            </section>

            <section class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm sm:p-7">
                @include('profile.partials.update-profile-information-form')
            </section>

            <section class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm sm:p-7">
                @include('profile.partials.update-password-form')
            </section>

            <section class="rounded-xl border border-red-200 bg-white p-5 shadow-sm sm:p-7">
                @include('profile.partials.delete-user-form')
            </section>
        </div>
    </main>
</x-app-layout>
