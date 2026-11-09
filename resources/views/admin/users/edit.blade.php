<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="text-sm font-semibold uppercase tracking-wide text-indigo-600">Administration / User accounts</p>
                <h1 class="mt-1 text-2xl font-semibold tracking-tight text-gray-900">Manage account role</h1>
            </div>
            <a href="{{ route('admin.users.show', $user) }}" class="inline-flex min-h-10 items-center rounded-md border border-gray-300 bg-white px-3 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Back to account</a>
        </div>
    </x-slot>

    <main class="py-10">
        <div class="mx-auto max-w-2xl space-y-5 px-4 sm:px-6 lg:px-8">
            <section class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-100 bg-gray-50/70 px-6 py-5 sm:px-8">
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Account</p>
                    <h2 class="mt-2 font-semibold text-gray-900">{{ $user->name }}</h2>
                    <p class="mt-1 break-all text-sm text-gray-600">{{ $user->email }}</p>
                </div>
                <div class="p-6 sm:p-8">
                    @if ($errors->any())
                        <div role="alert" class="mb-5 rounded-md border border-red-200 bg-red-50 p-4 text-sm text-red-800">
                            <p class="font-semibold">The role could not be updated.</p>
                            <ul class="mt-2 list-inside list-disc">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-6" onsubmit="return confirm('Save this account role change?')">
                        @csrf
                        @method('PATCH')
                        <fieldset>
                            <legend class="text-sm font-semibold text-gray-900">Choose an access role</legend>
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
                                <p id="role-help" class="mt-2 text-sm text-gray-500">Public registrations always receive Staff access. An administrator cannot demote their own account or demote the last remaining administrator.</p>
                            @endif
                            @error('role')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </fieldset>
                        <div class="flex flex-col-reverse gap-3 border-t border-gray-100 pt-5 sm:flex-row sm:justify-end">
                            <a href="{{ route('admin.users.show', $user) }}" class="inline-flex min-h-11 items-center justify-center rounded-md border border-gray-300 px-4 text-sm font-semibold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Cancel</a>
                            <button type="submit" class="inline-flex min-h-11 items-center justify-center rounded-md bg-indigo-700 px-4 text-sm font-semibold text-white shadow-sm hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" @disabled(auth()->user()->is($user))>Save role</button>
                        </div>
                    </form>
                </div>
            </section>
            <p class="px-1 text-sm text-gray-500">Changes apply immediately after saving. The server verifies each role change and protects the last administrator account.</p>
        </div>
    </main>
</x-app-layout>
