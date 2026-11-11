<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>SIMS | Student Information Management System - NEMSU Tandag Campus</title>
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

        @php
            $developerPhoto = file_exists(public_path('images/developerphoto.jpg'))
                ? 'images/developerphoto.jpg'
                : (file_exists(public_path('images/developerphoto')) ? 'images/developerphoto' : null);
        @endphp

        <!-- =========================================================
             01. BACKGROUND GRID PATTERN (10% opacity)
             ========================================================= -->
        <div class="bg-grid-pattern pointer-events-none fixed inset-0 z-0" aria-hidden="true"></div>

        <div class="relative z-10">

            <!-- =========================================================
                 02. NAVIGATION BAR
             ========================================================= -->
            <nav x-data="{ sidebarOpen: false }" class="fixed top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-md border-b border-gray-100 shadow-sm sticky">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between h-16 min-h-[72px]">
                        <!-- Brand -->
                        <div class="flex items-center gap-3">
                            <img src="{{ asset('images/nemsu-logo.png') }}" alt="NEMSU Logo" class="h-10 w-10 object-contain shrink-0">
                            <div class="hidden sm:block flex flex-col">
                                <span class="text-lg font-bold tracking-tight text-navy-900 leading-tight">SIMS</span>
                                <span class="text-xs font-medium text-blue-600 leading-tight">NEMSU Tandag Campus · Academic Project</span>
                            </div>
                        </div>

                        <!-- Desktop Menu -->
                        <div class="hidden md:flex items-center gap-8 shrink-0">
                            <a href="#home" class="text-sm font-medium text-gray-600 hover:text-blue-600 transition-colors whitespace-nowrap">Home</a>
                            <a href="#training" class="text-sm font-medium text-blue-600 hover:text-blue-700 transition-colors whitespace-nowrap font-semibold">Laravel Training</a>
                            <a href="#features" class="text-sm font-medium text-gray-600 hover:text-blue-600 transition-colors whitespace-nowrap">Features</a>
                            <a href="#developer" class="text-sm font-medium text-gray-600 hover:text-blue-600 transition-colors whitespace-nowrap">Developer</a>
                            @auth
                                <a href="{{ route('dashboard') }}" class="bg-blue-600 text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors shadow-sm whitespace-nowrap">
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="bg-blue-600 text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors shadow-sm whitespace-nowrap">
                                    Login
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="border border-blue-200 text-blue-600 px-5 py-2 rounded-lg text-sm font-medium hover:bg-blue-50 hover:border-blue-300 transition-colors whitespace-nowrap">
                                        Register
                                    </a>
                                @endif
                            @endauth
                        </div>

                        <!-- Mobile Menu Toggle -->
                        <div class="md:hidden flex items-center shrink-0">
                            <button @click="sidebarOpen = ! sidebarOpen" class="text-gray-600 hover:text-blue-600 p-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Mobile Sidebar -->
                <div x-cloak x-show="sidebarOpen" x-transition class="md:hidden absolute top-16 left-0 right-0 bg-white border-b border-gray-200 pb-4">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="space-y-2">
                            <a href="#home" @click="sidebarOpen = false" class="block px-4 py-2 text-sm font-medium text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors">Home</a>

                            <a href="#training" @click="sidebarOpen = false" class="block px-4 py-2 text-sm font-medium text-blue-600 hover:bg-blue-50 font-semibold rounded-lg transition-colors">Laravel Training</a>
                            <a href="#features" @click="sidebarOpen = false" class="block px-4 py-2 text-sm font-medium text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors">Features</a>
                            <a href="#developer" @click="sidebarOpen = false" class="block px-4 py-2 text-sm font-medium text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors">Developer</a>
                            @auth
                                <a href="{{ route('dashboard') }}" @click="sidebarOpen = false" class="block px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg text-center hover:bg-blue-700 transition-colors shadow-sm">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" @click="sidebarOpen = false" class="block px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg text-center hover:bg-blue-700 transition-colors shadow-sm">Login</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" @click="sidebarOpen = false" class="block px-4 py-2 border border-blue-200 text-blue-600 text-sm font-medium rounded-lg text-center hover:bg-blue-50 hover:border-blue-300 transition-colors">Register</a>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            </nav>

            <!-- =========================================================
                 03. HERO SECTION
             ========================================================= -->
            <section id="home" class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <!-- Kicker -->
                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 border border-blue-200 text-blue-700 text-xs font-semibold uppercase tracking-wider mb-6 inline-block">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        Student Information Management System
                    </span>

                    <!-- Headline -->
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-navy-900 mb-6 leading-tight">
                        Student Information<br>
                        Management System
                    </h1>

                    <!-- Supporting Paragraph -->
                    <p class="text-lg sm:text-xl text-gray-600 mb-8 max-w-2xl mx-auto leading-relaxed">
                        A student information management platform developed using PHP and Laravel,
                        demonstrating practical web development through student records, subject management,
                        enrollments, role-based access, database relationships, and RESTful APIs.
                    </p>

                    <!-- Call to Action Buttons -->
                    <div class="flex flex-col sm:flex-row justify-center gap-4">
                        @auth
                            <a href="{{ route('dashboard') }}" class="px-8 py-3 bg-blue-600 text-white rounded-lg text-lg font-semibold hover:bg-blue-700 shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-1 flex items-center justify-center gap-2">
                                <span>Open Dashboard</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="px-8 py-3 bg-blue-600 text-white rounded-lg text-lg font-semibold hover:bg-blue-700 shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-1 flex items-center justify-center gap-2">
                                <span>Sign In</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </a>
                            <a href="#features" class="px-8 py-3 bg-white border border-gray-200 text-gray-700 rounded-lg text-lg font-semibold shadow-sm hover:bg-gray-50 hover:border-gray-300 transition-all">
                                Explore the Project
                            </a>
                        @endauth
                    </div>

                    <!-- Technology Badges -->
                    <div class="flex flex-wrap justify-center gap-3 mt-10">
                        <span class="px-3 py-1 bg-white border border-gray-200 text-gray-600 text-xs font-medium rounded-full shadow-sm">PHP 8</span>
                        <span class="px-3 py-1 bg-white border border-gray-200 text-gray-600 text-xs font-medium rounded-full shadow-sm">Laravel 12</span>
                        <span class="px-3 py-1 bg-white border border-gray-200 text-gray-600 text-xs font-medium rounded-full shadow-sm">SQLite</span>
                        <span class="px-3 py-1 bg-white border border-gray-200 text-gray-600 text-xs font-medium rounded-full shadow-sm">Blade</span>
                        <span class="px-3 py-1 bg-white border border-gray-200 text-gray-600 text-xs font-medium rounded-full shadow-sm">Tailwind CSS</span>
                        <span class="px-3 py-1 bg-white border border-gray-200 text-gray-600 text-xs font-medium rounded-full shadow-sm">Alpine.js</span>
                    </div>
                </div>
            </section>

            <!-- =========================================================
                 04. FEATURED DICT-CARAGA LARAVEL TRAINING STORY
             ========================================================= -->
            <section id="training" class="py-20 lg:pt-32 lg:pb-24 border-t border-gray-200 bg-gray-50/50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex flex-col lg:flex-row gap-12 items-center">

                        <!-- Left: Training Image (Desktop) / Top (Mobile) -->
                        <div class="lg:w-1/2 flex justify-center">
                            @if (file_exists(public_path('images/dict-caraga-laravel-tot-2026.png')))
                                <img src="{{ asset('images/dict-caraga-laravel-tot-2026.png') }}"
                                     alt="DICT-Caraga Laravel Training of Trainers news graphic showing training participants and event details"
                                     class="w-full max-w-md lg:max-w-none rounded-xl border border-gray-200 shadow-lg object-contain">
                            @else
                                <div class="w-full max-w-md lg:max-w-none h-96 bg-gray-200 flex items-center justify-center rounded-xl border border-dashed border-gray-300">
                                    <div class="text-center text-gray-400">
                                        <p class="text-sm">DICT-Caraga Laravel Training Graphic</p>
                                        <p class="text-xs">Place image at: public/images/dict-caraga-laravel-tot-2026.png</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Right: Content -->
                        <div class="lg:w-1/2 space-y-6">
                            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-50 border border-indigo-200 text-indigo-700 text-xs font-semibold uppercase tracking-wider">
                                DICT-Caraga Training Spotlight
                            </span>

                            <h2 class="text-2xl sm:text-3xl font-bold text-navy-900 leading-tight">
                                Honing Future Web Development Trainers: DICT-Caraga Holds Pioneering Training of Trainers on Laravel in Bislig City
                            </h2>

                            <div class="grid grid-cols-2 gap-4 text-sm text-gray-600">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    <span>October 26–30, 2026</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /></svg>
                                    <span>Digital Transformation Center, Bislig City</span>
                                </div>
                                <div class="flex items-center gap-2 col-span-2">
                                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    <span>Five days · 40 hours · 22 participants</span>
                                </div>
                            </div>

                            <p class="text-gray-600 leading-relaxed text-sm sm:text-base">
                                The Department of Information and Communications Technology (DICT)-Caraga conducted a pioneering Training of Trainers program on Laravel in Bislig City, Surigao del Sur. The five-day intensive program equipped 22 participants with modern web development skills including PHP Object-Oriented Programming, Laravel installation and configuration, MVC architecture, routes and controllers, Blade templating, database management, Eloquent ORM, form handling and validation, authentication and authorization, CRUD functionality, RESTful API development, and practical development and testing.
                            </p>

                            <div class="space-y-3">
                                <h3 class="text-sm font-bold text-navy-900 uppercase tracking-wider">Training Topics</h3>
                                <div class="flex flex-wrap gap-2">
                                    <span class="px-2 py-1 bg-white border border-gray-200 rounded text-xs text-gray-600">PHP OOP</span>
                                    <span class="px-2 py-1 bg-white border border-gray-200 rounded text-xs text-gray-600">Laravel Setup</span>
                                    <span class="px-2 py-1 bg-white border border-gray-200 rounded text-xs text-gray-600">MVC Architecture</span>
                                    <span class="px-2 py-1 bg-white border border-gray-200 rounded text-xs text-gray-600">Blade Templating</span>
                                    <span class="px-2 py-1 bg-white border border-gray-200 rounded text-xs text-gray-600">Eloquent ORM</span>
                                    <span class="px-2 py-1 bg-white border border-gray-200 rounded text-xs text-gray-600">RESTful API</span>
                                    <span class="px-2 py-1 bg-white border border-gray-200 rounded text-xs text-gray-600">Authentication</span>
                                    <span class="px-2 py-1 bg-white border border-gray-200 rounded text-xs text-gray-600">CRUD</span>
                                </div>
                            </div>

                            <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                                <h4 class="text-sm font-bold text-navy-900 mb-2">Connection to SIMS</h4>
                                <p class="text-xs text-gray-600 leading-relaxed">
                                    SIMS is an independent academic project developed separately from the DICT-Caraga training event. The training topics covered align with modern Laravel web development concepts demonstrated in this Student Information Management System project.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- =========================================================
                 05. FROM LARAVEL CONCEPTS TO FUNCTIONAL APPLICATION
             ========================================================= -->
            <section id="features" class="py-20 lg:pt-32 lg:pb-32 border-t border-gray-200 bg-white">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <!-- Section Header -->
                    <div class="mb-16">
                        <h2 class="text-3xl sm:text-4xl font-bold text-navy-900 mb-4">From Laravel Concepts<br>to a Functional Application</h2>
                        <p class="text-lg text-gray-600 max-w-2xl leading-relaxed">
                            SIMS demonstrates how important Laravel web development concepts can be applied
                            in a working academic information system.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Student Management -->
                        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow">
                            <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center mb-4 text-blue-600">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-navy-900 mb-2">Student Management</h3>
                            <p class="text-gray-600 text-sm leading-relaxed">
                                Student records with database validation and CRUD operations.
                            </p>
                        </div>

                        <!-- Subject Management -->
                        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow">
                            <div class="w-12 h-12 bg-purple-50 rounded-lg flex items-center justify-center mb-4 text-purple-600">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-navy-900 mb-2">Subject Management</h3>
                            <p class="text-gray-600 text-sm leading-relaxed">
                                Subject codes, subject names, units, search, and administration tools.
                            </p>
                        </div>

                        <!-- Enrollment Management -->
                        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow">
                            <div class="w-12 h-12 bg-amber-50 rounded-lg flex items-center justify-center mb-4 text-amber-600">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0118 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375m-8.25-3l1.5 1.5 3-3.75" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-navy-900 mb-2">Enrollment Management</h3>
                            <p class="text-gray-600 text-sm leading-relaxed">
                                Student–subject enrollment records and status management.
                            </p>
                        </div>

                        <!-- Authentication and Authorization -->
                        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow">
                            <div class="w-12 h-12 bg-indigo-50 rounded-lg flex items-center justify-center mb-4 text-indigo-600">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-navy-900 mb-2">Authentication & Authorization</h3>
                            <p class="text-gray-600 text-sm leading-relaxed">
                                Laravel Breeze and Administrator/Staff role permissions.
                            </p>
                        </div>

                        <!-- Database and Eloquent -->
                        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow">
                            <div class="w-12 h-12 bg-teal-50 rounded-lg flex items-center justify-center mb-4 text-teal-600">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-navy-900 mb-2">Database & Eloquent</h3>
                            <p class="text-gray-600 text-sm leading-relaxed">
                                SQLite migrations, relationships, and Eloquent ORM.
                            </p>
                        </div>

                        <!-- RESTful API -->
                        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow">
                            <div class="w-12 h-12 bg-pink-50 rounded-lg flex items-center justify-center mb-4 text-pink-600">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-navy-900 mb-2">RESTful API</h3>
                            <p class="text-gray-600 text-sm leading-relaxed">
                                Student REST endpoints and Sanctum authentication.
                            </p>
                        </div>

                        <!-- Academic Programs -->
                        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow">
                            <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center mb-4 text-green-600">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-navy-900 mb-2">Academic Programs</h3>
                            <p class="text-gray-600 text-sm leading-relaxed">
                                NEMSU academic catalog with college, department, and degree-program hierarchy.
                            </p>
                        </div>

                        <!-- Testing and Verification -->
                        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow">
                            <div class="w-12 h-12 bg-gray-50 rounded-lg flex items-center justify-center mb-4 text-gray-600">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-navy-900 mb-2">Testing & Verification</h3>
                            <p class="text-gray-600 text-sm leading-relaxed">
                                PHPUnit feature tests and application validation.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- =========================================================
                 06. ABOUT THE PROJECT DEVELOPER
             ========================================================= -->
            <section id="developer" class="py-20 lg:pt-32 lg:pb-24 bg-navy-900 text-white border-t border-blue-600/30">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex flex-col lg:flex-row gap-12 items-center">
                        <!-- Developer Info -->
                        <div class="lg:w-1/2 space-y-6">
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-600/20 border border-blue-500/30 text-blue-200 text-xs font-semibold uppercase tracking-wider mb-4">
                                Developer Spotlight
                            </div>

                            <h2 class="text-3xl sm:text-4xl font-bold text-white mb-4">Ace Raz Cabuga</h2>
                            <p class="text-gray-300 text-lg leading-relaxed mb-6">
                                Creative Technologist, Web Developer, Graphic Designer, and Print Artist working across digital products, visual communication, web systems, and print production.
                            </p>

                            <div class="space-y-4">
                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-blue-400 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                    </svg>
                                    <span class="text-gray-300">Independent Academic Project</span>
                                </div>
                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                                    <span class="text-gray-300">SIMS v1.0 · NEMSU Tandag Campus · Academic Year 2026–2027</span>
                                </div>
                            </div>

                            <!-- GitHub Link -->
                            <a href="https://github.com/acegraphics2013-ops/student-information-system"
                               target="_blank" rel="noopener noreferrer"
                               class="inline-flex items-center gap-2 px-5 py-3 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700 shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-1 flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12"/></svg>
                                View on GitHub
                            </a>
                        </div>

                        <!-- Developer Photo -->
                        <div class="lg:w-1/2 flex justify-center">
                            <div class="relative">
                                <div class="absolute -inset-3 rounded-full bg-gradient-to-tr from-blue-500/30 via-indigo-400/20 to-transparent blur-md" aria-hidden="true"></div>
                                <div class="relative h-48 w-48 rounded-full overflow-hidden shadow-2xl border-4 border-white/20 bg-blue-900">
                                    @if ($developerPhoto)
                                        <img src="{{ asset($developerPhoto) }}"
                                             alt="Ace Raz Cabuga - Developer of SIMS"
                                             width="192" height="192"
                                             loading="lazy" decoding="async"
                                             class="h-full w-full object-cover object-top transition-transform duration-700 ease-out hover:scale-105">
                                    @else
                                        <div class="h-full w-full bg-gradient-to-br from-blue-600 to-blue-800 flex items-center justify-center text-white relative overflow-hidden">
                                            <div class="absolute inset-0 bg-gradient-to-tr from-blue-500 to-indigo-700 opacity-30"></div>
                                            <span class="text-4xl font-bold relative z-10">
                                                @php echo substr(auth()->user()->name ?? 'ACE', 0, 1); @endphp
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- =========================================================
                 07. FOOTER
             ========================================================= -->
            <footer class="border-t border-gray-200 bg-white py-12">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="border-t border-gray-100 pt-8">
                        <!-- Disclaimer -->
                        <div class="max-w-4xl mx-auto mb-8">
                            <div class="bg-amber-50 border border-amber-200 rounded-lg p-6 text-sm text-amber-800">
                                <p class="font-semibold mb-2"><strong>Academic Project Disclaimer:</strong></p>
                                <p>This website is an unofficial student project developed solely for academic purposes and project submission. It is not an official website of Northeastern Mindanao State University (NEMSU) – Tandag Campus and is not affiliated with or endorsed by the university.</p>
                                <p class="mt-2">The featured DICT-Caraga Laravel Training of Trainers news item is provided for educational and contextual reference and does not indicate DICT sponsorship, endorsement, or certification of SIMS.</p>
                            </div>
                        </div>

                        <!-- Footer Links -->
                        <div class="flex flex-col sm:flex-row justify-between items-center gap-6 mb-8">
                            <div class="text-sm text-gray-500">
                                &copy; {{ date('Y') }} Ace Raz Cabuga · NEMSU Tandag Campus SIMS · Academic Project
                            </div>
                            <div class="flex items-center gap-6 text-sm">
                                <a href="#home" class="text-gray-600 hover:text-blue-600 transition-colors">Home</a>
                                <a href="#training" class="text-gray-600 hover:text-blue-600 transition-colors">Laravel Training</a>
                                <a href="#features" class="text-gray-600 hover:text-blue-600 transition-colors">Features</a>
                                <a href="#developer" class="text-gray-600 hover:text-blue-600 transition-colors">Developer</a>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>

        </div>

        <!-- Alpine.js for Mobile Menu & Smooth Scroll -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const navbar = document.querySelector('nav');

                // Smooth scroll for nav links
                document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                    anchor.addEventListener('click', function (e) {
                        const targetId = this.getAttribute('href').substring(1);
                        const targetElement = document.getElementById(targetId);
                        if (!targetElement) return;

                        e.preventDefault();
                        const navbarHeight = navbar ? navbar.offsetHeight : 0;
                        const targetPosition = targetElement.getBoundingClientRect().top + window.pageYOffset - navbarHeight - 8;

                        window.scrollTo({
                            top: targetPosition,
                            behavior: 'smooth'
                        });

                        if (history.replaceState) {
                            history.replaceState(null, '', '#' + targetId);
                        }
                    });
                });
            });
        </script>
    </body>
</html>