<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <p class="text-sm font-medium text-indigo-600">Administration / User Accounts</p>
                <h1 class="mt-1 text-2xl font-semibold text-gray-800">Manage Account Role</h1>
            </div>
            <a href="{{ route('admin.users.show', $user) }}" class="text-sm font-medium text-indigo-700 hover:text-indigo-900">Back to account</a>
        </div>
    </x-slot>

    <main class="py-10">
        <div class="mx-auto max-w-2xl space-y-5 px-4 sm:px-6 lg:px-8">
            <section class="rounded-lg bg-white p-6 shadow-sm sm:p-8">
                <h2 class="font-semibold text-gray-900">{{ $user->name }}</h2>
                <p class="mt-1 text-sm text-gray-600">{{ $user->email }}</p>
                @if ($errors->any())
                    <div role="alert" class="mt-5 rounded-md border border-red-200 bg-red-50 p-4 text-sm text-red-800">
                        <ul class="list-inside list-disc">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                    </div>
                @endif
                <form method="POST" action="{{ route('admin.users.update', $user) }}" class="mt-6 space-y-5" onsubmit="return confirm('Save this account role change?')">
                    @csrf
                    @method('PATCH')
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700">Account role</label>
                        <select id="role" name="role" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" @disabled(auth()->user()->is($user))>
                            <option value="staff" @selected(old('role', $user->role) === 'staff')>Staff — read access to management modules</option>
                            <option value="admin" @selected(old('role', $user->role) === 'admin')>Administrator — full management access</option>
                        </select>
                        @if (auth()->user()->is($user))
                            <p class="mt-2 text-sm text-amber-700">Your own role cannot be changed here.</p>
                            <input type="hidden" name="role" value="{{ $user->role }}">
                        @else
                            <p class="mt-2 text-sm text-gray-500">Public registrations receive Staff access. An administrator cannot demote their own account or demote the last remaining administrator.</p>
                        @endif
                        @error('role')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <button type="submit" class="rounded-md bg-indigo-700 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-600" @disabled(auth()->user()->is($user))>Save role</button>
                        <a href="{{ route('admin.users.show', $user) }}" class="rounded-md border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">Cancel</a>
                    </div>
                </form>
            </section>
        </div>
    </main>
</x-app-layout>
