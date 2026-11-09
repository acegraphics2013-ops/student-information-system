<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="text-sm font-semibold uppercase tracking-wide text-indigo-600">Administration / User accounts</p>
                <h1 class="mt-1 text-2xl font-semibold tracking-tight text-gray-900">Account details</h1>
            </div>
            <a href="{{ route('admin.users.index') }}" class="inline-flex min-h-10 items-center rounded-md border border-gray-300 bg-white px-3 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Back to users</a>
        </div>
    </x-slot>

    <main class="py-10">
        <div class="mx-auto max-w-3xl space-y-5 px-4 sm:px-6 lg:px-8">
            <section class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-100 bg-gray-50/70 px-6 py-5 sm:px-8">
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Account profile</p>
                    <div class="mt-2 flex flex-wrap items-center justify-between gap-4">
                        <h2 class="text-xl font-semibold tracking-tight text-gray-900">{{ $user->name }}</h2>
                        <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-sm font-semibold {{ $user->role === 'admin' ? 'bg-indigo-50 text-indigo-800 ring-1 ring-indigo-200' : 'bg-gray-100 text-gray-700 ring-1 ring-gray-200' }}">
                            <span aria-hidden="true" class="h-2 w-2 rounded-full {{ $user->role === 'admin' ? 'bg-indigo-600' : 'bg-gray-500' }}"></span>
                            {{ $user->role === 'admin' ? 'Administrator' : 'Staff' }}
                        </span>
                    </div>
                </div>
                <div class="px-6 py-6 sm:px-8">
                    <dl class="grid gap-x-8 gap-y-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Email address</dt>
                            <dd class="mt-2 break-all text-sm font-medium text-gray-900">{{ $user->email }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Email verification</dt>
                            <dd class="mt-2 text-sm font-medium {{ $user->email_verified_at ? 'text-green-700' : 'text-amber-700' }}">{{ $user->email_verified_at ? 'Verified' : 'Not verified' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Registered</dt>
                            <dd class="mt-2 text-sm text-gray-900">{{ $user->created_at?->format('F j, Y g:i A') ?? 'Date unavailable' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Account ID</dt>
                            <dd class="mt-2 text-sm text-gray-900">#{{ $user->id }}</dd>
                        </div>
                    </dl>
                    <div class="mt-8 flex flex-wrap items-center justify-between gap-4 border-t border-gray-100 pt-6">
                        <p class="max-w-md text-sm text-gray-500">Role changes affect which management actions this account can perform.</p>
                        <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex min-h-11 items-center rounded-md bg-indigo-700 px-4 text-sm font-semibold text-white shadow-sm hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Manage access role</a>
                    </div>
                </div>
            </section>
            <aside class="flex gap-3 rounded-lg border border-blue-100 bg-blue-50 p-4 text-sm text-blue-900">
                <span aria-hidden="true" class="font-bold">i</span>
                <p>Sensitive credentials are protected. Password hashes, reset tokens, sessions, and API access tokens are not displayed or editable here.</p>
            </aside>
        </div>
    </main>
</x-app-layout>
