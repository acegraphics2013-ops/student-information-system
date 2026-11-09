<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <p class="text-sm font-medium text-indigo-600">Administration</p>
                <h1 class="mt-1 text-2xl font-semibold text-gray-800">User Accounts</h1>
            </div>
            <a href="{{ route('admin.index') }}" class="text-sm font-medium text-indigo-700 hover:text-indigo-900">Back to dashboard</a>
        </div>
    </x-slot>

    <main class="py-10">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div role="status" class="rounded-md border border-green-200 bg-green-50 p-4 text-sm text-green-800">{{ session('success') }}</div>
            @endif
            <form method="GET" action="{{ route('admin.users.index') }}" class="grid gap-4 rounded-lg bg-white p-5 shadow-sm sm:grid-cols-[1fr_14rem_auto_auto] sm:items-end">
                <div>
                    <label for="user-search" class="block text-sm font-medium text-gray-700">Search name or email</label>
                    <input id="user-search" name="search" type="search" maxlength="100" value="{{ $search }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label for="user-role" class="block text-sm font-medium text-gray-700">Role</label>
                    <select id="user-role" name="role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">All roles</option>
                        <option value="admin" @selected($selectedRole === 'admin')>Administrator</option>
                        <option value="staff" @selected($selectedRole === 'staff')>Staff</option>
                    </select>
                </div>
                <button type="submit" class="rounded-md bg-indigo-700 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-600">Filter</button>
                <a href="{{ route('admin.users.index') }}" class="text-center text-sm font-medium text-gray-600 hover:text-gray-900">Clear</a>
            </form>

            <section class="overflow-hidden rounded-lg bg-white shadow-sm ring-1 ring-gray-200">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 text-left font-medium text-gray-600">
                            <tr>
                                <th scope="col" class="px-5 py-3">Name</th>
                                <th scope="col" class="px-5 py-3">Email</th>
                                <th scope="col" class="px-5 py-3">Role</th>
                                <th scope="col" class="px-5 py-3">Registered</th>
                                <th scope="col" class="px-5 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($users as $user)
                                <tr>
                                    <td class="whitespace-nowrap px-5 py-4 font-medium text-gray-900">{{ $user->name }}</td>
                                    <td class="whitespace-nowrap px-5 py-4 text-gray-600">{{ $user->email }}</td>
                                    <td class="whitespace-nowrap px-5 py-4">
                                        <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $user->role === 'admin' ? 'bg-indigo-100 text-indigo-800' : 'bg-gray-100 text-gray-700' }}">{{ $user->role === 'admin' ? 'Administrator' : 'Staff' }}</span>
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4 text-gray-600">{{ $user->created_at?->format('M j, Y') ?? '—' }}</td>
                                    <td class="whitespace-nowrap px-5 py-4">
                                        <a href="{{ route('admin.users.show', $user) }}" class="font-medium text-indigo-700 hover:text-indigo-900">View</a>
                                        <a href="{{ route('admin.users.edit', $user) }}" class="ms-3 font-medium text-indigo-700 hover:text-indigo-900">Manage role</a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="px-5 py-10 text-center text-gray-500">No user accounts match these filters.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="border-t border-gray-100 px-5 py-4">{{ $users->links() }}</div>
            </section>
        </div>
    </main>
</x-app-layout>
