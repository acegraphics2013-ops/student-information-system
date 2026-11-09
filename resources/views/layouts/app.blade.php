<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            @if (session('success'))
                <div
                    x-data="{ show: true }"
                    x-show="show"
                    x-init="setTimeout(() => show = false, 5000)"
                    x-transition
                    role="status"
                    aria-live="polite"
                    class="fixed bottom-5 right-5 z-50 flex max-w-sm items-start gap-3 rounded-lg border border-green-200 bg-white p-4 text-sm text-green-800 shadow-lg ring-1 ring-black/5"
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

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
