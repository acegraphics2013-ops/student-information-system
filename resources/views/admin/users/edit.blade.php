<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="text-sm font-semibold uppercase tracking-wide text-indigo-600">Administration / User accounts</p>
                <h1 class="mt-1 text-2xl font-semibold tracking-tight text-gray-900">Edit user account</h1>
            </div>
            <a href="{{ route('admin.users.show', $user) }}" class="inline-flex min-h-10 items-center rounded-md border border-gray-300 bg-white px-3 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Back to account</a>
        </div>
    </x-slot>

    <main class="py-10">
        <div class="mx-auto max-w-2xl space-y-5 px-4 sm:px-6 lg:px-8">
            <section class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-100 bg-gray-50/70 px-6 py-5 sm:px-8">
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Account</p>
                    <h2 class="mt-2 font-semibold text-gray-900">Account details</h2>
                    <p class="mt-1 break-all text-sm text-gray-600">{{ $user->email }}</p>
                </div>
                <div class="p-6 sm:p-8">
                    @if ($errors->any())
                        <div role="alert" class="mb-5 rounded-md border border-red-200 bg-red-50 p-4 text-sm text-red-800">
                            <p class="font-semibold">The account could not be updated.</p>
                            <ul class="mt-2 list-inside list-disc">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('admin.users.update', $user) }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PATCH')
                        <section class="space-y-4 border-b border-gray-100 pb-6" aria-labelledby="profile-photo-heading">
                            <div>
                                <h3 id="profile-photo-heading" class="text-sm font-semibold text-gray-900">Profile photo</h3>
                                <p class="mt-1 text-sm text-gray-500">JPG, PNG, or WebP up to 2 MB.</p>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="flex h-16 w-16 shrink-0 items-center justify-center overflow-hidden rounded-full bg-indigo-100 text-xl font-semibold text-indigo-700">
                                    @if ($user->profile_photo_path)
                                        <img src="{{ asset('storage/'.$user->profile_photo_path) }}" alt="{{ $user->name }} profile photo" class="h-full w-full object-cover">
                                    @else
                                        {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($user->name, 0, 1)) }}
                                    @endif
                                </div>
                                <div class="min-w-0 flex-1">
                                    <label for="profile_photo" class="block text-sm font-medium text-gray-700">Upload or replace photo</label>
                                    <input id="profile_photo" name="profile_photo" type="file" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" class="mt-2 block w-full rounded-md border border-gray-300 bg-white text-sm text-gray-700 file:mr-4 file:min-h-10 file:border-0 file:bg-gray-50 file:px-4 file:font-semibold file:text-gray-700 hover:file:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    @error('profile_photo')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                    @if ($user->profile_photo_path)
                                        <label class="mt-3 inline-flex items-center gap-2 text-sm text-gray-700">
                                            <input type="checkbox" name="remove_profile_photo" value="1" @checked(old('remove_profile_photo')) class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                            Remove current photo
                                        </label>
                                    @endif
                                </div>
                            </div>
                        </section>
                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-700">Full name</label>
                            <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required maxlength="255" autocomplete="name" class="mt-2 block min-h-11 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-700">Email address</label>
                            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required maxlength="255" autocomplete="email" class="mt-2 block min-h-11 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <fieldset>
                            <legend class="text-sm font-semibold text-gray-900">Access role</legend>
                            <p class="mt-1 text-sm text-gray-500">Select the least privileged role needed for this person's work.</p>
                            <label for="role" class="sr-only">Account role</label>
                            <select id="role" name="role" required class="mt-3 block min-h-12 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" @disabled(auth()->user()->is($user)) aria-describedby="role-help">
                                <option value="staff" @selected(old('role', $user->role) === 'staff')>Staff — read access to management modules</option>
                                <option value="admin" @selected(old('role', $user->role) === 'admin')>Administrator — full management access</option>
                            </select>
                            @if (auth()->user()->is($user))
                                <p id="role-help" class="mt-2 text-sm text-amber-700">Your own administrator role cannot be changed from this page.</p>
                                <input type="hidden" name="role" value="{{ $user->role }}">
                            @else
                                <p id="role-help" class="mt-2 text-sm text-gray-500">Staff have read access to management modules. Administrators can create and manage records and user accounts. The last administrator cannot be demoted.</p>
                            @endif
                            @error('role')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </fieldset>
                        <fieldset class="space-y-3 border-t border-gray-100 pt-5">
                            <legend class="text-sm font-semibold text-gray-900">Change password (optional)</legend>
                            <p class="text-sm text-gray-500">Leave both fields blank to keep the current password.</p>
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700">New password</label>
                                <input id="password" name="password" type="password" minlength="8" autocomplete="new-password" class="mt-2 block min-h-11 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm new password</label>
                                <input id="password_confirmation" name="password_confirmation" type="password" minlength="8" autocomplete="new-password" class="mt-2 block min-h-11 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </fieldset>
                        <div class="flex flex-col-reverse gap-3 border-t border-gray-100 pt-5 sm:flex-row sm:justify-end">
                            <a href="{{ route('admin.users.show', $user) }}" class="inline-flex min-h-11 items-center justify-center rounded-md border border-gray-300 px-4 text-sm font-semibold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Cancel</a>
                            <button type="submit" class="inline-flex min-h-11 items-center justify-center rounded-md bg-indigo-700 px-4 text-sm font-semibold text-white shadow-sm hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Save changes</button>
                        </div>
                    </form>
                </div>
            </section>
            <p class="px-1 text-sm text-gray-500">Changes apply immediately after saving. The server validates the account details and protects administrator access.</p>
        </div>
    </main>
</x-app-layout>
