<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <p class="text-sm font-medium text-indigo-600">Administration / User Accounts</p>
                <h1 class="mt-1 text-2xl font-semibold text-gray-800">Account Details</h1>
            </div>
            <a href="{{ route('admin.users.index') }}" class="text-sm font-medium text-indigo-700 hover:text-indigo-900">Back to users</a>
        </div>
    </x-slot>

    <main class="py-10">
        <div class="mx-auto max-w-3xl space-y-5 px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div role="status" class="rounded-md border border-green-200 bg-green-50 p-4 text-sm text-green-800">{{ session('success') }}</div>
            @endif
            <section class="rounded-lg bg-white p-6 shadow-sm sm:p-8">
                <h2 class="text-lg font-semibold text-gray-900">{{ $user->name }}</h2>
                <dl class="mt-6 grid gap-5 sm:grid-cols-2">
                    <div><dt class="text-sm font-medium text-gray-500">Email</dt><dd class="mt-1 text-sm text-gray-900">{{ $user->email }}</dd></div>
                    <div><dt class="text-sm font-medium text-gray-500">Role</dt><dd class="mt-1 text-sm capitalize text-gray-900">{{ $user->role === 'admin' ? 'Administrator' : 'Staff' }}</dd></div>
                    <div><dt class="text-sm font-medium text-gray-500">Registered</dt><dd class="mt-1 text-sm text-gray-900">{{ $user->created_at?->format('F j, Y g:i A') ?? '—' }}</dd></div>
                    <div><dt class="text-sm font-medium text-gray-500">Email verification</dt><dd class="mt-1 text-sm text-gray-900">{{ $user->email_verified_at ? 'Verified' : 'Not verified' }}</dd></div>
                </dl>
                <div class="mt-8 border-t border-gray-100 pt-5">
                    <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex rounded-md bg-indigo-700 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-600">Manage role</a>
                </div>
            </section>
            <p class="text-sm text-gray-500">Password hashes, reset credentials, sessions, and access tokens are not displayed or editable here.</p>
        </div>
    </main>
</x-app-layout>
