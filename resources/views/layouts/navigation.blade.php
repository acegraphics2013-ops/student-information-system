<div>
    <div
        x-cloak
        x-show="sidebarOpen"
        x-transition.opacity
        @click="sidebarOpen = false"
        class="fixed inset-0 z-40 bg-gray-950/50 lg:hidden"
        aria-hidden="true"
    ></div>

    <aside
        id="app-sidebar"
        class="fixed inset-y-0 left-0 z-50 flex w-64 -translate-x-full flex-col border-r border-gray-200 bg-white shadow-xl transition-transform duration-200 ease-in-out lg:translate-x-0 lg:shadow-sm"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
        aria-label="Main sidebar"
    >
        <div class="flex h-16 shrink-0 items-center justify-between border-b border-gray-100 px-5">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2 text-xl font-bold tracking-tight text-indigo-700">
                <img src="{{ asset('images/nemsu-logo.png') }}" alt="NEMSU official logo" class="h-10 w-10 object-contain">
                <span>SIMS BY: ACE</span>
            </a>
            <button
                type="button"
                @click="sidebarOpen = false"
                aria-label="Close navigation menu"
                class="inline-flex h-9 w-9 items-center justify-center rounded-md text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 lg:hidden"
            >
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-5" aria-label="Main navigation">
            <p class="px-3 pb-2 text-xs font-semibold uppercase tracking-wider text-gray-400">Workspace</p>
            <a href="{{ route('dashboard') }}" @click="sidebarOpen = false" @class([
                'group flex min-h-11 items-center gap-3 rounded-lg px-3 text-sm font-medium transition-colors',
                'bg-indigo-50 text-indigo-700' => request()->routeIs('dashboard'),
                'text-gray-600 hover:bg-gray-50 hover:text-gray-900' => ! request()->routeIs('dashboard'),
            ])>
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <rect x="3.5" y="3.5" width="7" height="7" rx="1.5" stroke-width="1.8" />
                    <rect x="13.5" y="3.5" width="7" height="7" rx="1.5" stroke-width="1.8" />
                    <rect x="3.5" y="13.5" width="7" height="7" rx="1.5" stroke-width="1.8" />
                    <rect x="13.5" y="13.5" width="7" height="7" rx="1.5" stroke-width="1.8" />
                </svg>
                {{ __('Dashboard') }}
            </a>

            <p class="px-3 pb-2 pt-6 text-xs font-semibold uppercase tracking-wider text-gray-400">Management</p>
            <a href="{{ route('students.index') }}" @click="sidebarOpen = false" @class([
                'group flex min-h-11 items-center gap-3 rounded-lg px-3 text-sm font-medium transition-colors',
                'bg-indigo-50 text-indigo-700' => request()->routeIs('students.*'),
                'text-gray-600 hover:bg-gray-50 hover:text-gray-900' => ! request()->routeIs('students.*'),
            ])>
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <rect x="4" y="3.5" width="16" height="17" rx="2.5" stroke-width="1.8" />
                    <circle cx="12" cy="9" r="2.5" stroke-width="1.8" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7.5 17a4.5 4.5 0 0 1 9 0" />
                </svg>
                {{ __('Students') }}
            </a>
            <a href="{{ route('subjects.index') }}" @click="sidebarOpen = false" @class([
                'group flex min-h-11 items-center gap-3 rounded-lg px-3 text-sm font-medium transition-colors',
                'bg-indigo-50 text-indigo-700' => request()->routeIs('subjects.*'),
                'text-gray-600 hover:bg-gray-50 hover:text-gray-900' => ! request()->routeIs('subjects.*'),
            ])>
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6v14m0-14c-1.6-1.5-4-2-7-2v14c3 0 5.4.5 7 2m0-14c1.6-1.5 4-2 7-2v14c-3 0-5.4.5-7 2" />
                </svg>
                {{ __('Subjects') }}
            </a>
            <a href="{{ route('enrollments.index') }}" @click="sidebarOpen = false" @class([
                'group flex min-h-11 items-center gap-3 rounded-lg px-3 text-sm font-medium transition-colors',
                'bg-indigo-50 text-indigo-700' => request()->routeIs('enrollments.*'),
                'text-gray-600 hover:bg-gray-50 hover:text-gray-900' => ! request()->routeIs('enrollments.*'),
            ])>
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <rect x="4" y="3.5" width="16" height="17" rx="2.5" stroke-width="1.8" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m8 12 2.5 2.5L16.5 9M8 6.5h8" />
                </svg>
                {{ __('Enrollments') }}
            </a>
            <a href="{{ route('academic-programs.index') }}" @click="sidebarOpen = false" @class([
                'group flex min-h-11 items-center gap-3 rounded-lg px-3 text-sm font-medium transition-colors',
                'bg-indigo-50 text-indigo-700' => request()->routeIs('academic-programs.*'),
                'text-gray-600 hover:bg-gray-50 hover:text-gray-900' => ! request()->routeIs('academic-programs.*'),
            ])>
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 20h16M6 20V8l6-4 6 4v12M9 10h.01M15 10h.01M9 14h.01M15 14h.01M10 20v-3h4v3" />
                </svg>
                {{ __('Academic Programs') }}
            </a>

            @if (Auth::user()->role === 'admin')
                <p class="px-3 pb-2 pt-6 text-xs font-semibold uppercase tracking-wider text-gray-400">Administration</p>
                <a href="{{ route('admin.index') }}" @click="sidebarOpen = false" @class([
                    'group flex min-h-11 items-center gap-3 rounded-lg px-3 text-sm font-medium transition-colors',
                    'bg-indigo-50 text-indigo-700' => request()->routeIs('admin.index'),
                    'text-gray-600 hover:bg-gray-50 hover:text-gray-900' => ! request()->routeIs('admin.index'),
                ])>
                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3 4 7v5c0 5 3.4 8 8 9 4.6-1 8-4 8-9V7l-8-4Zm-3 9 2 2 4-4" />
                    </svg>
                    {{ __('Administration') }}
                </a>
                <a href="{{ route('admin.users.index') }}" @click="sidebarOpen = false" @class([
                    'group flex min-h-11 items-center gap-3 rounded-lg px-3 text-sm font-medium transition-colors',
                    'bg-indigo-50 text-indigo-700' => request()->routeIs('admin.users.*'),
                    'text-gray-600 hover:bg-gray-50 hover:text-gray-900' => ! request()->routeIs('admin.users.*'),
                ])>
                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <circle cx="9" cy="8" r="3" stroke-width="1.8" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 20v-1a6 6 0 0 1 12 0v1H3Zm13-12a3 3 0 1 1 2.5 3M18 14a5 5 0 0 1 3 4.6V20h-3" />
                    </svg>
                    {{ __('User Accounts') }}
                </a>
            @endif
        </nav>

        <div class="shrink-0 border-t border-gray-100 p-3" x-data="{ accountMenuOpen: false }">
            <div class="flex items-center gap-3 rounded-lg px-2 py-2">
                @if (Auth::user()->profile_photo_path)
                    <img src="{{ asset('storage/'.Auth::user()->profile_photo_path) }}" alt="{{ Auth::user()->name }} profile photo" class="h-10 w-10 shrink-0 rounded-full object-cover">
                @else
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-sm font-semibold text-indigo-700">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr(Auth::user()->name, 0, 1)) }}</span>
                @endif
                <div class="min-w-0 flex-1">
                    <p class="truncate text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</p>
                    <p class="truncate text-xs capitalize text-gray-500">{{ Auth::user()->role }}</p>
                </div>
                <button
                    type="button"
                    @click="accountMenuOpen = ! accountMenuOpen"
                    :aria-expanded="accountMenuOpen.toString()"
                    aria-controls="sidebar-account-actions"
                    aria-label="Toggle account options"
                    class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-md text-gray-500 hover:bg-gray-100 hover:text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                >
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <circle cx="4" cy="10" r="1.7" />
                        <circle cx="10" cy="10" r="1.7" />
                        <circle cx="16" cy="10" r="1.7" />
                    </svg>
                </button>
            </div>
            <div
                id="sidebar-account-actions"
                x-cloak
                x-show="accountMenuOpen"
                x-transition
                @keydown.escape.stop="accountMenuOpen = false"
                class="mt-2 space-y-1 border-t border-gray-100 pt-2"
                aria-label="Account options"
            >
                <a
                    href="{{ route('profile.edit') }}"
                    @click="accountMenuOpen = false; sidebarOpen = false"
                    @class([
                        'block min-h-10 rounded-lg px-3 py-2 text-sm font-medium transition-colors',
                        'bg-indigo-50 text-indigo-700' => request()->routeIs('profile.*'),
                        'text-gray-600 hover:bg-gray-50 hover:text-gray-900' => ! request()->routeIs('profile.*'),
                    ])
                >
                    {{ __('Profile settings') }}
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block min-h-10 w-full rounded-lg px-3 py-2 text-left text-sm font-medium text-gray-600 transition-colors hover:bg-red-50 hover:text-red-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        {{ __('Log Out') }}
                    </button>
                </form>
            </div>
        </div>
    </aside>
</div>
