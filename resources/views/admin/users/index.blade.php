<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="text-sm font-semibold uppercase tracking-wide text-indigo-600">Administration</p>
                <h1 class="mt-1 text-2xl font-semibold tracking-tight text-gray-900">User accounts</h1>
                <p class="mt-1 text-sm text-gray-500">Review registered accounts and manage access roles.</p>
            </div>
            <a href="{{ route('admin.index') }}" class="inline-flex min-h-10 items-center rounded-md border border-gray-300 bg-white px-3 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Back to dashboard</a>
        </div>
    </x-slot>

    <main class="py-10">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div role="status" class="flex items-start gap-3 rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-800">
                    <span aria-hidden="true" class="mt-0.5 font-bold">✓</span>
                    <p>{{ session('success') }}</p>
                </div>
            @endif
            <section aria-label="User account filters" class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm sm:p-6">
                <form method="GET" action="{{ route('admin.users.index') }}" class="grid gap-4 sm:grid-cols-[minmax(0,1fr)_15rem_auto] sm:items-end">
                    <div>
                        <label for="user-search" class="block text-sm font-semibold text-gray-700">Search accounts</label>
                        <p class="mt-1 text-xs text-gray-500">Search by a person's name or email address.</p>
                        <input id="user-search" name="search" type="search" maxlength="100" value="{{ $search }}" placeholder="e.g. Alex or alex@example.com" class="mt-2 block min-h-11 w-full rounded-md border-gray-300 shadow-sm placeholder:text-gray-400 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="user-role" class="block text-sm font-semibold text-gray-700">Account role</label>
                        <select id="user-role" name="role" class="mt-2 block min-h-11 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">All roles</option>
                            <option value="admin" @selected($selectedRole === 'admin')>Administrator</option>
                            <option value="staff" @selected($selectedRole === 'staff')>Staff</option>
                        </select>
                    </div>
                    <div class="flex gap-2 sm:pb-0.5">
                        <button type="submit" class="inline-flex min-h-11 flex-1 items-center justify-center rounded-md bg-indigo-700 px-5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:flex-none">Apply filters</button>
                        @if ($search !== '' || $selectedRole !== '')
                            <a href="{{ route('admin.users.index') }}" class="inline-flex min-h-11 items-center justify-center rounded-md border border-gray-300 px-4 text-sm font-semibold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Clear</a>
                        @endif
                    </div>
                </form>
            </section>

            <section class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-2 border-b border-gray-100 px-5 py-4 sm:px-6">
                    <div>
                        <h2 class="font-semibold text-gray-900">Registered accounts</h2>
                        <p class="mt-1 text-sm text-gray-500">
                            @if ($users->total() > 0)
                                Showing {{ $users->firstItem() }}–{{ $users->lastItem() }} of {{ number_format($users->total()) }} {{ \Illuminate\Support\Str::plural('account', $users->total()) }}
                            @else
                                0 accounts found
                            @endif
                        </p>
                    </div>
                    <p class="text-xs text-gray-500">Roles control access to management features.</p>
                </div>
                <div class="hidden overflow-x-auto sm:block">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <tr>
                                <th scope="col" class="px-6 py-3">Account</th>
                                <th scope="col" class="px-6 py-3">Access role</th>
                                <th scope="col" class="px-6 py-3">Registered</th>
                                <th scope="col" class="px-6 py-3 text-right">Manage</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($users as $user)
                                <tr class="transition-colors hover:bg-gray-50/80">
                                    <td class="px-6 py-4">
                                        <a href="{{ route('admin.users.show', $user) }}" class="font-semibold text-gray-900 hover:text-indigo-700">{{ $user->name }}</a>
                                        <p class="mt-1 text-gray-500">{{ $user->email }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center gap-2 rounded-full px-2.5 py-1 text-xs font-semibold {{ $user->role === 'admin' ? 'bg-indigo-50 text-indigo-800 ring-1 ring-indigo-200' : 'bg-gray-100 text-gray-700 ring-1 ring-gray-200' }}">
                                            <span aria-hidden="true" class="h-1.5 w-1.5 rounded-full {{ $user->role === 'admin' ? 'bg-indigo-600' : 'bg-gray-500' }}"></span>
                                            {{ $user->role === 'admin' ? 'Administrator' : 'Staff' }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-gray-600">{{ $user->created_at?->format('M j, Y') ?? '—' }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-right">
                                        <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex min-h-9 items-center rounded-md px-3 text-sm font-semibold text-indigo-700 hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-indigo-500" aria-label="Manage role for {{ $user->name }}">Manage role</a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="px-6 py-12 text-center">
                                    <p class="font-medium text-gray-900">No matching accounts</p>
                                    <p class="mt-1 text-sm text-gray-500">Try another search or clear the selected filters.</p>
                                </td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="divide-y divide-gray-100 sm:hidden">
                    @forelse ($users as $user)
                        <article class="p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <a href="{{ route('admin.users.show', $user) }}" class="break-words font-semibold text-gray-900 hover:text-indigo-700">{{ $user->name }}</a>
                                    <p class="mt-1 break-all text-sm text-gray-500">{{ $user->email }}</p>
                                </div>
                                <span class="shrink-0 rounded-full px-2.5 py-1 text-xs font-semibold {{ $user->role === 'admin' ? 'bg-indigo-50 text-indigo-800 ring-1 ring-indigo-200' : 'bg-gray-100 text-gray-700 ring-1 ring-gray-200' }}">{{ $user->role === 'admin' ? 'Admin' : 'Staff' }}</span>
                            </div>
                            <div class="mt-4 flex items-center justify-between gap-3">
                                <p class="text-xs text-gray-500">Registered {{ $user->created_at?->format('M j, Y') ?? '—' }}</p>
                                <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex min-h-9 items-center rounded-md px-3 text-sm font-semibold text-indigo-700 hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-indigo-500" aria-label="Manage role for {{ $user->name }}">Manage role</a>
                            </div>
                        </article>
                    @empty
                        <div class="px-5 py-12 text-center">
                            <p class="font-medium text-gray-900">No matching accounts</p>
                            <p class="mt-1 text-sm text-gray-500">Try another search or clear the selected filters.</p>
                        </div>
                    @endforelse
                </div>
                @if ($users->hasPages())
                    <div class="border-t border-gray-100 px-4 py-4 sm:px-6">{{ $users->links() }}</div>
                @endif
            </section>
        </div>
    </main>
</x-app-layout>
