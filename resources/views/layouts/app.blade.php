<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>NEMSU Tandag | Student Information Management System</title>
        <link rel="icon" type="image/png" href="{{ asset('images/nemsu-logo.png') }}">
        <link rel="apple-touch-icon" href="{{ asset('images/nemsu-logo.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            [x-cloak] { display: none !important; }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div x-data="{ sidebarOpen: false }" @keydown.escape.window="sidebarOpen = false" class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            @if (session('success'))
                <div
                    x-data="{ show: true }"
                    x-show="show"
                    x-init="setTimeout(() => show = false, 5000)"
                    x-transition
                    role="status"
                    aria-live="polite"
                    class="fixed bottom-5 right-5 z-[60] flex max-w-sm items-start gap-3 rounded-lg border border-green-200 bg-white p-4 text-sm text-green-800 shadow-lg ring-1 ring-black/5"
                >
                    <span aria-hidden="true" class="mt-0.5 font-bold text-green-600">✓</span>
                    <p class="flex-1">{{ session('success') }}</p>
                    <button
                        type="button"
                        @click="show = false"
                        aria-label="Dismiss notification"
                        class="-mr-1 -mt-1 rounded p-1 text-green-700 hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-green-600"
                    >
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="min-h-screen lg:pl-64">
                <div class="sticky top-0 z-30 flex h-16 items-center gap-3 border-b border-gray-200 bg-white px-4 shadow-sm lg:hidden">
                    <button
                        type="button"
                        @click="sidebarOpen = true"
                        aria-label="Open navigation menu"
                        aria-controls="app-sidebar"
                        :aria-expanded="sidebarOpen.toString()"
                        class="inline-flex h-10 w-10 items-center justify-center rounded-md text-gray-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    >
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 text-left text-[0.78rem] font-bold leading-tight tracking-tight text-indigo-700">
                        <img src="{{ asset('images/nemsu-logo.png') }}" alt="NEMSU official logo" class="h-9 w-9 object-contain">
                        <span>Student Information Management System</span>
                    </a>
                    <span class="ml-auto truncate text-sm font-medium text-gray-600">{{ Auth::user()->name }}</span>
                </div>

                @isset($header)
                    <header class="bg-white shadow-sm">
                        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <main>
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
