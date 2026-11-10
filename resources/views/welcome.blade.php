
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="description"
          content="SIMS is an independent Laravel academic project demonstrating student, subject, and enrollment management, role-based access, and REST APIs.">

    <title>SIMS | Student Information Management System</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] {
            display: none !important;
        }

        html {
            scroll-padding-top: 6rem;
        }

        body {
            overflow-x: hidden;
        }
    </style>
</head>

<body class="min-h-screen bg-slate-50 font-sans text-slate-900 antialiased">

@php
    $campusImage = 'images/nemsu-tandag-administration.webp';
    $trainingImage = 'images/dict-caraga-laravel-tot-2026.png';

    $hasCampusImage = file_exists(public_path($campusImage));
    $hasTrainingImage = file_exists(public_path($trainingImage));

    $features = [
        [
            'tag' => 'STD',
            'title' => 'Student Management',
            'description' => 'Manage student profiles, course information, year levels, and student records.',
        ],
        [
            'tag' => 'SUB',
            'title' => 'Subject Management',
            'description' => 'Maintain subject codes, names, units, and searchable subject records.',
        ],
        [
            'tag' => 'ENR',
            'title' => 'Enrollment Management',
            'description' => 'Connect students with subjects and manage enrollment statuses.',
        ],
        [
            'tag' => 'AUTH',
            'title' => 'Authentication & Roles',
            'description' => 'Secure login with Laravel Breeze and role-based Administrator and Staff access.',
        ],
        [
            'tag' => 'DB',
            'title' => 'Database & Eloquent',
            'description' => 'Use SQLite, migrations, model relationships, and Laravel Eloquent ORM.',
        ],
        [
            'tag' => 'API',
            'title' => 'Student REST API',
            'description' => 'Provide RESTful Student endpoints with Sanctum authentication and authorization.',
        ],
        [
            'tag' => 'CAT',
            'title' => 'Academic Programs',
            'description' => 'Browse the supplied college, department, and degree-program catalog.',
        ],
        [
            'tag' => 'TEST',
            'title' => 'Testing & Validation',
            'description' => 'Verify application behavior using Laravel feature tests and input validation.',
        ],
    ];

    $topics = [
        'PHP Object-Oriented Programming',
        'Laravel MVC Architecture',
        'Routing and Controllers',
        'Blade Templates',
        'Database and Eloquent ORM',
        'CRUD Operations',
        'Authentication and Authorization',
        'RESTful API Development',
    ];
@endphp

<!-- ===================================================
     STICKY NAVIGATION
=================================================== -->
<header
    x-data="{ mobileOpen: false }"
    class="sticky top-0 z-50 border-b border-white/10
           bg-[#082448]/95 text-white shadow-lg
           backdrop-blur-xl"
>
    <nav
        class="mx-auto flex min-h-20 max-w-7xl
               items-center justify-between gap-6 px-4
               sm:px-6 lg:px-8"
        aria-label="Main navigation"
    >
        <!-- Brand -->
        <a href="{{ url('/') }}"
           class="flex shrink-0 items-center gap-3
                  rounded-md focus:outline-none
                  focus:ring-2 focus:ring-blue-300">

            <div class="flex h-11 w-11 items-center
                        justify-center rounded-xl
                        bg-blue-600 shadow-md">
                <svg class="h-7 w-7"
                     viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="1.8"
                     aria-hidden="true">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="m2 10 10-5 10 5-10 5-10-5Z"/>
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M6 12v5c4 3 8 3 12 0v-5"/>
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M22 10v6"/>
                </svg>
            </div>

            <div class="leading-tight">
                <span class="block text-xl font-extrabold tracking-tight">
                    SIMS
                </span>

                <span class="block text-xs text-blue-200">
                    NEMSU Tandag Campus
                </span>
            </div>
        </a>

        <!-- Desktop Navigation -->
        <div class="hidden items-center gap-6 lg:flex">

            <a href="#home"
               class="text-sm font-medium text-white
                      transition hover:text-blue-300">
                Home
            </a>

            <a href="#about"
               class="text-sm font-medium text-slate-200
                      transition hover:text-blue-300">
                About SIMS
            </a>

            <a href="#features"
               class="text-sm font-medium text-slate-200
                      transition hover:text-blue-300">
                Features
            </a>

            <a href="#training"
               class="text-sm font-medium text-slate-200
                      transition hover:text-blue-300">
                Laravel Training
            </a>

            <a href="#developer"
               class="text-sm font-medium text-slate-200
                      transition hover:text-blue-300">
                Developer
            </a>
        </div>

        <!-- Desktop Authentication -->
        <div class="hidden items-center gap-3 lg:flex">
            @auth
                <a href="{{ route('dashboard') }}"
                   class="inline-flex min-h-11 items-center
                          rounded-lg bg-blue-600 px-5 py-2
                          text-sm font-semibold transition
                          hover:bg-blue-500
                          focus:outline-none
                          focus:ring-2 focus:ring-blue-300">
                    Open Dashboard
                </a>
            @else
                <a href="{{ route('login') }}"
                   class="rounded-lg px-4 py-2 text-sm
                          font-semibold text-white
                          transition hover:text-blue-300">
                    Log In
                </a>

                @if (Route::has('register'))
                    <a href="{{ route('register') }}"
                       class="inline-flex min-h-11 items-center
                              rounded-lg border border-blue-300/50
                              px-5 py-2 text-sm font-semibold
                              transition hover:bg-white/10">
                        Register
                    </a>
                @endif
            @endauth
        </div>

        <!-- Mobile Menu Toggle -->
        <button
            type="button"
            class="inline-flex h-11 w-11 items-center
                   justify-center rounded-lg
                   border border-white/20
                   hover:bg-white/10
                   focus:outline-none
                   focus:ring-2 focus:ring-blue-300
                   lg:hidden"
            x-on:click="mobileOpen = !mobileOpen"
            x-bind:aria-expanded="mobileOpen.toString()"
            aria-label="Toggle navigation menu"
            aria-controls="mobile-navigation"
        >
            <svg class="h-6 w-6"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor"
                 aria-hidden="true">
                <path
                    x-show="!mobileOpen"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16"
                />
                <path
                    x-cloak
                    x-show="mobileOpen"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M6 6l12 12M18 6 6 18"
                />
            </svg>
        </button>
    </nav>

    <!-- Mobile Navigation -->
    <div
        id="mobile-navigation"
        x-cloak
        x-show="mobileOpen"
        x-transition
        class="border-t border-white/10
               bg-[#082448] lg:hidden"
    >
        <div class="mx-auto flex max-w-7xl
                    flex-col gap-1 px-4 py-4
                    sm:px-6">

            <a href="#home"
               x-on:click="mobileOpen = false"
               class="rounded-lg px-4 py-3 hover:bg-white/10">
                Home
            </a>

            <a href="#about"
               x-on:click="mobileOpen = false"
               class="rounded-lg px-4 py-3 hover:bg-white/10">
                About SIMS
            </a>

            <a href="#features"
               x-on:click="mobileOpen = false"
               class="rounded-lg px-4 py-3 hover:bg-white/10">
                Features
            </a>

            <a href="#training"
               x-on:click="mobileOpen = false"
               class="rounded-lg px-4 py-3 hover:bg-white/10">
                Laravel Training
            </a>

            <a href="#developer"
               x-on:click="mobileOpen = false"
               class="rounded-lg px-4 py-3 hover:bg-white/10">
                Developer
            </a>

            <div class="mt-3 border-t border-white/10 pt-4">
                @auth
                    <a href="{{ route('dashboard') }}"
                       class="block rounded-lg bg-blue-600
                              px-4 py-3 text-center font-semibold">
                        Open Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="block rounded-lg bg-blue-600
                              px-4 py-3 text-center font-semibold">
                        Log In
                    </a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                           class="mt-3 block rounded-lg
                                  border border-white/30
                                  px-4 py-3 text-center">
                            Register
                        </a>
                    @endif
                @endauth
            </div>
        </div>
    </div>
</header>

<main>

    <!-- ===================================================
         HERO SECTION
    =================================================== -->
    <section
        id="home"
        class="relative isolate overflow-hidden
               bg-gradient-to-br from-[#061A38]
               via-[#0B376F] to-[#1466AC] text-white"
    >
        <div class="pointer-events-none absolute
                    -right-20 top-0 h-96 w-96
                    rounded-full bg-blue-400/10 blur-3xl">
        </div>

        <div class="pointer-events-none absolute
                    -bottom-32 -left-20 h-96 w-96
                    rounded-full bg-cyan-400/10 blur-3xl">
        </div>

        <div class="relative mx-auto grid max-w-7xl
                    items-center gap-12 px-4 py-20
                    sm:px-6 lg:grid-cols-2
                    lg:gap-14 lg:px-8 lg:py-28">

            <!-- Hero Text -->
            <div>
                <span class="inline-flex rounded-full
                             border border-blue-300/30
                             bg-white/10 px-4 py-2
                             text-xs font-semibold
                             uppercase tracking-widest
                             text-blue-100">
                    PHP · Laravel 12 · Academic Project
                </span>

                <h1 class="mt-7 max-w-2xl
                           text-4xl font-extrabold
                           leading-tight tracking-tight
                           sm:text-5xl xl:text-6xl">
                    Student Information
                    <span class="block text-sky-300">
                        Management System
                    </span>
                </h1>

                <p class="mt-5 text-lg font-semibold
                          text-blue-100">
                    A Practical Laravel Web Application
                </p>

                <p class="mt-6 max-w-xl text-base
                          leading-8 text-slate-200">
                    SIMS is an independent academic project
                    demonstrating the management of student
                    records, subjects, enrollments, and
                    academic programs through a Laravel-based
                    information system.
                </p>

                <div class="mt-9 flex flex-wrap gap-4">
                    <a href="#features"
                       class="inline-flex min-h-12
                              items-center justify-center
                              rounded-lg bg-white px-6
                              font-semibold text-[#10396C]
                              shadow-md transition
                              hover:bg-blue-50">
                        Explore Features
                    </a>

                    @auth
                        <a href="{{ route('dashboard') }}"
                           class="inline-flex min-h-12
                                  items-center justify-center
                                  rounded-lg bg-blue-600
                                  px-6 font-semibold
                                  transition hover:bg-blue-500">
                            Open Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="inline-flex min-h-12
                                  items-center justify-center
                                  rounded-lg bg-blue-600
                                  px-6 font-semibold
                                  transition hover:bg-blue-500">
                            Access SIMS
                        </a>
                    @endauth
                </div>

                <!-- Technology Tags -->
                <div class="mt-10 flex flex-wrap gap-2">
                    @foreach ([
                        'PHP',
                        'Laravel',
                        'Blade',
                        'Tailwind CSS',
                        'Alpine.js',
                        'SQLite',
                        'Sanctum',
                    ] as $technology)
                        <span class="rounded-full
                                     border border-white/15
                                     bg-white/10 px-3 py-1.5
                                     text-xs font-medium text-blue-100">
                            {{ $technology }}
                        </span>
                    @endforeach
                </div>
            </div>

            <!-- Hero Visual -->
            <div class="relative">
                <div class="overflow-hidden rounded-2xl
                            border border-white/20
                            bg-white/10 p-3 shadow-2xl
                            backdrop-blur-sm">

                    @if ($hasCampusImage)
                        <img
                            src="{{ asset($campusImage) }}"
                            alt="NEMSU Tandag Campus Administration Building"
                            class="aspect-[16/10] w-full
                                   rounded-xl object-cover object-center"
                            fetchpriority="high"
                        >
                    @else
                        <div class="rounded-xl bg-white p-5
                                    text-slate-900 shadow-xl">

                            <div class="flex items-center
                                        justify-between border-b
                                        border-slate-100 pb-4">
                                <div>
                                    <h3 class="font-bold">
                                        SIMS Dashboard
                                    </h3>
                                    <p class="text-xs text-slate-500">
                                        Student Information Management
                                    </p>
                                </div>

                                <span class="rounded-full
                                             bg-blue-50 px-3 py-1
                                             text-xs font-medium
                                             text-blue-700">
                                    Preview
                                </span>
                            </div>

                            <div class="mt-5 grid grid-cols-2 gap-3">
                                @foreach ([
                                    'Students',
                                    'Subjects',
                                    'Enrollments',
                                    'Academic Programs',
                                ] as $item)
                                    <div class="rounded-lg border
                                                border-slate-200
                                                bg-slate-50 p-5">
                                        <div class="mb-3 h-8 w-8
                                                    rounded-lg bg-blue-100">
                                        </div>

                                        <p class="text-sm font-semibold">
                                            {{ $item }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-5 rounded-lg bg-blue-50
                                        p-4 text-sm text-blue-900">
                                Laravel MVC · Database Management
                                · Authentication · REST API
                            </div>
                        </div>
                    @endif
                </div>

                <div class="mt-4 text-center
                            text-xs text-blue-100">
                    Independent academic project · NEMSU Tandag Campus
                </div>
            </div>
        </div>
    </section>

    <!-- ===================================================
         ABOUT SECTION
    =================================================== -->
    <section id="about" class="bg-white px-4 py-20 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-7xl">
            <div class="grid items-center gap-12 lg:grid-cols-2">

                <div>
                    <span class="text-sm font-bold
                                 uppercase tracking-widest
                                 text-blue-700">
                        About the Project
                    </span>

                    <h2 class="mt-4 text-3xl font-bold
                               tracking-tight text-slate-900
                               sm:text-4xl">
                        One System for Managing
                        Academic Records
                    </h2>

                    <p class="mt-6 leading-8 text-slate-600">
                        The Student Information Management System
                        is designed to demonstrate how Laravel
                        can be used to build a structured,
                        database-driven web application.
                    </p>

                    <p class="mt-4 leading-8 text-slate-600">
                        It applies the Model-View-Controller
                        architecture to student records,
                        subject information, and enrollment
                        operations while incorporating
                        authentication, authorization,
                        validation, and RESTful APIs.
                    </p>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="rounded-2xl border border-blue-100
                                bg-blue-50 p-6">
                        <div class="text-2xl font-extrabold text-blue-700">
                            MVC
                        </div>
                        <h3 class="mt-2 font-bold">
                            Structured Architecture
                        </h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">
                            Models, views, controllers, and
                            organized application routes.
                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-200
                                bg-white p-6 shadow-sm">
                        <div class="text-2xl font-extrabold text-blue-700">
                            CRUD
                        </div>
                        <h3 class="mt-2 font-bold">
                            Record Management
                        </h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">
                            Create, read, update, and delete
                            academic records.
                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-200
                                bg-white p-6 shadow-sm">
                        <div class="text-2xl font-extrabold text-blue-700">
                            API
                        </div>
                        <h3 class="mt-2 font-bold">
                            RESTful Integration
                        </h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">
                            Student endpoints secured
                            using Laravel authentication.
                        </p>
                    </div>

                    <div class="rounded-2xl border border-blue-100
                                bg-blue-50 p-6">
                        <div class="text-2xl font-extrabold text-blue-700">
                            QA
                        </div>
                        <h3 class="mt-2 font-bold">
                            Automated Testing
                        </h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">
                            Feature tests for application
                            behavior and validation.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===================================================
         DICT-CARAGA LARAVEL TRAINING SPOTLIGHT
    =================================================== -->
    <section
        id="training"
        class="border-y border-slate-200
               bg-slate-50 px-4 py-20
               sm:px-6 lg:px-8"
    >
        <div class="mx-auto max-w-7xl">

            <div class="mb-10">
                <span class="inline-flex rounded-full
                             bg-blue-100 px-4 py-2
                             text-xs font-bold uppercase
                             tracking-wider text-blue-800">
                    DICT-Caraga Training Spotlight
                </span>

                <h2 class="mt-5 text-3xl font-bold
                           tracking-tight text-slate-900
                           sm:text-4xl">
                    Laravel Training and Web Development
                </h2>

                <p class="mt-3 max-w-3xl text-slate-600">
                    A regional initiative focused on
                    strengthening practical web development
                    and future programming instruction.
                </p>
            </div>

            <div class="grid items-start gap-10 lg:grid-cols-2">

                <!-- Training News Image -->
                <figure class="overflow-hidden rounded-2xl
                               border border-slate-200
                               bg-white shadow-lg">

                    @if ($hasTrainingImage)
                        <img
                            src="{{ asset($trainingImage) }}"
                            alt="DICT-Caraga news graphic featuring participants in the Laravel Training of Trainers program in Bislig City"
                            class="h-auto w-full object-contain"
                            loading="lazy"
                        >
                    @else
                        <div class="flex min-h-72 flex-col
                                    items-center justify-center
                                    bg-gradient-to-br
                                    from-blue-900 to-indigo-950
                                    p-8 text-center text-white">

                            <div class="mb-5 rounded-full
                                        bg-white/15 p-4">
                                <svg class="h-12 w-12"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="1.5"
                                     viewBox="0 0 24 24"
                                     aria-hidden="true">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          d="M4 5h16v14H4zM8 9h8M8 13h8M8 17h5"/>
                                </svg>
                            </div>

                            <h3 class="text-xl font-bold">
                                DICT-Caraga Laravel Training
                            </h3>

                            <p class="mt-3 text-sm text-blue-100">
                                Add the supplied news image to
                                public/images to display it here.
                            </p>
                        </div>
                    @endif

                    <figcaption class="border-t border-slate-100
                                       px-5 py-4 text-xs
                                       leading-5 text-slate-500">
                        Source: DICT-Caraga news update graphic
                        supplied by the project developer.
                        Displayed for educational reference.
                    </figcaption>
                </figure>

                <!-- Training News Content -->
                <article>
                    <div class="mb-5 flex flex-wrap gap-2">
                        <span class="rounded-full bg-white
                                     px-4 py-2 text-xs
                                     font-semibold text-blue-800
                                     ring-1 ring-slate-200">
                            October 26–30, 2026
                        </span>

                        <span class="rounded-full bg-white
                                     px-4 py-2 text-xs
                                     font-semibold text-blue-800
                                     ring-1 ring-slate-200">
                            Bislig City, Surigao del Sur
                        </span>
                    </div>

                    <h3 class="text-2xl font-bold
                               leading-snug text-slate-900
                               sm:text-3xl">
                        Honing Future Web Development Trainers:
                        DICT-Caraga Holds Pioneering Training
                        of Trainers on Laravel in Bislig City
                    </h3>

                    <p class="mt-6 leading-8 text-slate-600">
                        According to the supplied DICT-Caraga
                        news report, a five-day Training of
                        Trainers on the PHP Laravel framework
                        took place at the Digital Transformation
                        Center in Bislig City from
                        October 26 to 30, 2026.
                    </p>

                    <p class="mt-4 leading-8 text-slate-600">
                        The 40-hour training involved 22
                        participants and covered practical
                        web application development, Laravel
                        architecture, database operations,
                        authentication, security, and
                        RESTful API development.
                    </p>

                    <h4 class="mt-8 text-lg font-bold text-slate-900">
                        Topics Highlighted in the Training
                    </h4>

                    <div class="mt-4 grid gap-3 sm:grid-cols-2">
                        @foreach ($topics as $topic)
                            <div class="flex items-start gap-3
                                        rounded-lg border
                                        border-blue-100
                                        bg-white p-3 text-sm
                                        text-slate-700">

                                <span class="font-bold text-blue-600"
                                      aria-hidden="true">
                                    ✓
                                </span>

                                <span>{{ $topic }}</span>
                            </div>
                        @endforeach
                    </div>
                </article>
            </div>

            <!-- Training to Project Connection -->
            <div class="mt-12 rounded-2xl border
                        border-blue-200 bg-blue-50 p-6
                        sm:p-8">

                <h3 class="text-xl font-bold text-blue-950">
                    From Laravel Concepts to a Working Application
                </h3>

                <p class="mt-3 leading-8 text-blue-900">
                    SIMS independently demonstrates many of
                    these Laravel development concepts through
                    its MVC architecture, record management,
                    database relationships, authorization,
                    validation, and Student REST API.
                    The DICT training feature provides
                    educational context and does not indicate
                    that DICT developed, sponsored, certified,
                    or endorsed this project.
                </p>
            </div>
        </div>
    </section>

    <!-- ===================================================
         SIMS FEATURES
    =================================================== -->
    <section id="features"
             class="bg-white px-4 py-20 sm:px-6 lg:px-8">

        <div class="mx-auto max-w-7xl">
            <div class="mx-auto mb-12 max-w-3xl text-center">

                <span class="text-sm font-bold uppercase
                             tracking-widest text-blue-700">
                    Application Modules
                </span>

                <h2 class="mt-4 text-3xl font-bold
                           tracking-tight text-slate-900
                           sm:text-4xl">
                    Explore the SIMS Features
                </h2>

                <p class="mt-4 leading-8 text-slate-600">
                    These modules demonstrate the key Laravel
                    features used in the existing Student
                    Information Management System.
                </p>
            </div>

            <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-4">

                @foreach ($features as $feature)
                    <article class="group rounded-2xl
                                    border border-slate-200
                                    bg-white p-6 shadow-sm
                                    transition duration-200
                                    hover:-translate-y-1
                                    hover:border-blue-200
                                    hover:shadow-lg">

                        <div class="mb-6 flex h-12 w-12
                                    items-center justify-center
                                    rounded-xl bg-blue-100
                                    text-xs font-extrabold
                                    tracking-wide text-blue-700
                                    transition group-hover:bg-blue-600
                                    group-hover:text-white">
                            {{ $feature['tag'] }}
                        </div>

                        <h3 class="text-lg font-bold text-slate-900">
                            {{ $feature['title'] }}
                        </h3>

                        <p class="mt-3 text-sm leading-7
                                  text-slate-600">
                            {{ $feature['description'] }}
                        </p>
                    </article>
                @endforeach

            </div>

            <div class="mt-10 text-center">
                @auth
                    <a href="{{ route('dashboard') }}"
                       class="inline-flex min-h-12
                              items-center justify-center
                              rounded-lg bg-[#12396B]
                              px-7 font-semibold text-white
                              transition hover:bg-blue-700">
                        Open SIMS Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="inline-flex min-h-12
                              items-center justify-center
                              rounded-lg bg-[#12396B]
                              px-7 font-semibold text-white
                              transition hover:bg-blue-700">
                        Sign In to Explore SIMS
                    </a>
                @endauth
            </div>
        </div>
    </section>

    <!-- ===================================================
         DEVELOPER SECTION
    =================================================== -->
    <section
        id="developer"
        class="bg-gradient-to-br from-slate-100
               to-blue-50 px-4 py-20
               sm:px-6 lg:px-8"
    >
        <div class="mx-auto max-w-7xl">
            <div class="grid items-center gap-10 lg:grid-cols-2">

                <div>
                    <span class="text-sm font-bold uppercase
                                 tracking-widest text-blue-700">
                        Project Developer
                    </span>

                    <h2 class="mt-4 text-4xl font-extrabold
                               tracking-tight text-slate-900">
                        Ace Raz Cabuga
                    </h2>

                    <p class="mt-4 text-lg font-semibold
                              text-blue-800">
                        Creative Technologist · Web Developer ·
                        Graphic Designer · Print Artist
                    </p>

                    <p class="mt-6 max-w-xl leading-8
                              text-slate-600">
                        Working across digital products,
                        visual communication, web systems,
                        and print production.
                    </p>

                    <p class="mt-4 max-w-xl leading-8
                              text-slate-600">
                        The SIMS project demonstrates practical
                        Laravel application development through
                        an independent academic system.
                    </p>

                    <a href="https://github.com/acegraphics2013-ops/student-information-system"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="mt-8 inline-flex min-h-12
                              items-center justify-center
                              rounded-lg bg-[#12396B]
                              px-6 font-semibold text-white
                              transition hover:bg-blue-700">

                        View SIMS on GitHub

                        <svg class="ml-2 h-5 w-5"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2"
                             viewBox="0 0 24 24"
                             aria-hidden="true">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M5 12h14m-6-6 6 6-6 6"/>
                        </svg>
                    </a>
                </div>

                <div class="rounded-2xl border
                            border-blue-100 bg-white
                            p-7 shadow-xl">

                    <div class="mb-5 flex items-center gap-3">
                        <div class="flex h-12 w-12
                                    items-center justify-center
                                    rounded-xl bg-blue-100
                                    text-xl font-bold text-blue-700">
                            &lt;/&gt;
                        </div>

                        <div>
                            <h3 class="font-bold text-slate-900">
                                Development Stack
                            </h3>

                            <p class="text-xs text-slate-500">
                                Technologies used by SIMS
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        @foreach ([
                            'PHP 8',
                            'Laravel 12',
                            'Laravel Breeze',
                            'Blade',
                            'Tailwind CSS',
                            'Alpine.js',
                            'SQLite',
                            'Eloquent ORM',
                            'Sanctum',
                            'PHPUnit',
                            'Git',
                        ] as $item)
                            <span class="rounded-lg
                                         bg-slate-100 px-3 py-2
                                         text-xs font-semibold
                                         text-slate-700">
                                {{ $item }}
                            </span>
                        @endforeach
                    </div>

                    <div class="mt-6 rounded-lg
                                border border-blue-100
                                bg-blue-50 p-4">

                        <p class="text-sm leading-7 text-blue-900">
                            <strong>Project focus:</strong>
                            practical application development,
                            software engineering,
                            database management,
                            secure authentication,
                            and automated verification.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </section>

</main>

<!-- ===================================================
     FOOTER
=================================================== -->
<footer class="bg-[#061A38] px-4 py-12
               text-slate-300 sm:px-6 lg:px-8">

    <div class="mx-auto grid max-w-7xl
                gap-8 md:grid-cols-3">

        <!-- Brand -->
        <div>
            <h2 class="text-2xl font-bold text-white">
                SIMS
            </h2>

            <p class="mt-2 text-sm text-blue-200">
                Student Information Management System
            </p>

            <p class="mt-4 text-sm leading-7">
                An independent Laravel academic project
                associated with NEMSU Tandag Campus
                coursework.
            </p>
        </div>

        <!-- Quick Links -->
        <div>
            <h3 class="font-bold text-white">
                Quick Links
            </h3>

            <div class="mt-4 flex flex-col gap-3 text-sm">
                <a href="#home" class="hover:text-white">Home</a>
                <a href="#about" class="hover:text-white">About SIMS</a>
                <a href="#features" class="hover:text-white">Features</a>
                <a href="#training" class="hover:text-white">Laravel Training</a>
                <a href="#developer" class="hover:text-white">Developer</a>
            </div>
        </div>

        <!-- Disclaimer -->
        <div>
            <h3 class="font-bold text-white">
                Academic Project Disclaimer
            </h3>

            <p class="mt-4 text-xs leading-6">
                This website is an unofficial student
                project developed solely for academic
                purposes and project submission. It is
                not an official website of Northeastern
                Mindanao State University (NEMSU) –
                Tandag Campus and is not affiliated with
                or endorsed by the university.
            </p>

            <p class="mt-3 text-xs leading-6">
                The DICT-Caraga training feature is
                included for educational reference and
                does not imply sponsorship or endorsement
                of SIMS by DICT-Caraga.
            </p>
        </div>
    </div>

    <div class="mx-auto mt-10 max-w-7xl
                border-t border-white/10 pt-6
                text-center text-xs text-slate-400">

        <p>
            &copy; {{ now()->year }} SIMS Student Project.
            All rights reserved.
        </p>

        <p class="mt-2">
            Developed using PHP and Laravel.
        </p>
    </div>
</footer>

</body>
</html>
