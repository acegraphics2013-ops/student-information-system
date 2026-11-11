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
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <style>
                [x-cloak] { display: none !important; }
            </style>
        @endif
    </head>
    <body class="font-sans text-gray-900 antialiased bg-white">
        <!-- Background Grid 10% Opacity -->
        <div class="fixed inset-0 z-0 pointer-events-none" style="background-image: linear-gradient(rgba(20, 85, 184, 0.1) 1px, transparent 1px), linear-gradient(90deg, rgba(20, 85, 184, 0.1) 1px, transparent 1px); background-size: 40px 40px;"></div>

        <div class="relative z-10">
            @yield('content')
        </div>
    </body>
</html>