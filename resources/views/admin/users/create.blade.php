<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="text-sm font-semibold uppercase tracking-wide text-indigo-600">Administration / User accounts</p>
                <h1 class="mt-1 text-2xl font-semibold tracking-tight text-gray-900">Create user account</h1>
            </div>
            <a href="{{ route('admin.users.index') }}" class="inline-flex min-h-10 items-center rounded-md border border-gray-300 bg-white px-3 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Back to users</a>
        </div>
    </x-slot>

    <main class="py-10">
        <div class="mx-auto max-w-2xl px-4 sm:px-6 lg:px-8">
            <section class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-100 bg-gray-50/70 px-6 py-5 sm:px-8">
                    <h2 class="font-semibold text-gray-900">Account details</h2>
                    <p class="mt-1 text-sm text-gray-500">Create login credentials and choose the account's access level.</p>
                </div>
                <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-5 p-6 sm:p-8">
                    @csrf
                    @if ($errors->any())
                        <div role="alert" class="rounded-md border border-red-200 bg-red-50 p-4 text-sm text-red-800">
                            <p class="font-semibold">The account could not be created.</p>
                            <ul class="mt-2 list-inside list-disc">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                        </div>
                    @endif
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700">Full name</label>
                        <input id="name" name="name" type="text" value="{{ old('name') }}" required maxlength="255" autocomplete="name" class="mt-2 block min-h-11 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700">Email address</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required maxlength="255" autocomplete="email" class="mt-2 block min-h-11 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="role" class="block text-sm font-semibold text-gray-700">Access role</label>
                        <select id="role" name="role" required class="mt-2 block min-h-11 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="staff" @selected(old('role', 'staff') === 'staff')>Staff — read access to management modules</option>
                            <option value="admin" @selected(old('role') === 'admin')>Administrator — full management access</option>
                        </select>
                        @error('role')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700">Temporary password</label>
                        <input id="password" name="password" type="password" required minlength="8" autocomplete="new-password" class="mt-2 block min-h-11 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <p class="mt-1 text-xs text-gray-500">Use at least 8 characters. Share this temporary password securely.</p>
                        @error('password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700">Confirm temporary password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required minlength="8" autocomplete="new-password" class="mt-2 block min-h-11 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div class="flex flex-col-reverse gap-3 border-t border-gray-100 pt-5 sm:flex-row sm:justify-end">
                        <a href="{{ route('admin.users.index') }}" class="inline-flex min-h-11 items-center justify-center rounded-md border border-gray-300 px-4 text-sm font-semibold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Cancel</a>
                        <button type="submit" class="inline-flex min-h-11 items-center justify-center rounded-md bg-indigo-700 px-4 text-sm font-semibold text-white shadow-sm hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Create account</button>
                    </div>
                </form>
            </section>
        </div>
    </main>
</x-app-layout>
