<x-guest-layout>
    <div class="login-page" data-bg-image="true" x-data="{
        show: false,
        toggle() {
            this.show = !this.show;
            this.$refs.password.type = this.show ? 'text' : 'password';
        }
    }">

        <!-- Background Image / Gradient -->
        <div class="login-bg"></div>

        <!-- Dark Navy Gradient Overlay (60% opacity) -->
        <div class="login-overlay"></div>

        <!-- Page Content -->
        <div class="login-content">

            <!-- ===== LEFT SIDE: University Branding & Feature Cards ===== -->
            <div class="login-left">

                <!-- Branding Section -->
                <div class="branding">
                    <p class="brand-kicker">CAMPUS STUDENT SERVICES</p>

                    <h1 class="brand-title">
                        NORTHEASTERN MINDANAO<br>STATE UNIVERSITY
                    </h1>

                    <div class="brand-accent-line"></div>

                    <p class="brand-subtitle">NEMSU TANDAG CAMPUS</p>

                    <p class="brand-system">Student Information Management System</p>

                    <span class="school-year-badge">
                        <svg aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v13a1 1 0 01-1-1V6a1 1 0 011-1z"></path>
                        </svg>
                        SCHOOL YEAR 2026–2027
                    </span>

                    <p class="brand-description">
                        A secure and centralized system for managing student records, subjects, academic programs, and enrollments at NEMSU Tandag Campus.
                    </p>
                </div>

                <!-- Feature Cards -->
                <div class="feature-cards">

                    <!-- Card 1: Student Records -->
                    <div class="feature-card">
                        <div class="feature-icon feature-icon-blue">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3>Student Records</h3>
                            <p>Manage student information and profiles.</p>
                        </div>
                    </div>

                    <!-- Card 2: Subjects -->
                    <div class="feature-card">
                        <div class="feature-icon feature-icon-purple">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 4.5 6.253v7.244c.482.856 1.12 1.312 1.8 1.312h3c.679 0 1.318-.456 1.8-1.312V6.253z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3>Subjects</h3>
                            <p>Create and maintain subject records.</p>
                        </div>
                    </div>

                    <!-- Card 3: Academic Programs -->
                    <div class="feature-card">
                        <div class="feature-icon feature-icon-green">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 0112 21.75 12.083 12.083 0 01-12 21.75L12 14z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3>Academic Programs</h3>
                            <p>View colleges, departments, and degree programs.</p>
                        </div>
                    </div>

                    <!-- Card 4: Enrollments -->
                    <div class="feature-card">
                        <div class="feature-icon feature-icon-amber">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7l3 3 6-6"></path>
                            </svg>
                        </div>
                        <div>
                            <h3>Enrollments</h3>
                            <p>Manage student subject enrollments.</p>
                        </div>
                    </div>

                </div>

            </div>

            <!-- ===== RIGHT SIDE: Forgot Password Card ===== -->
            <div class="login-right">

                <div class="login-card">

                    <!-- Session Status -->
                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <!-- Card Header -->
                    <div class="login-card-header text-center">
                        <img src="{{ asset('images/nemsu-logo.png') }}" alt="NEMSU official logo" class="login-logo object-contain">

                        <h2 class="login-title">Forgot your password?</h2>
                        <p class="login-subtitle">
                            No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.
                        </p>

                        <div class="login-divider"></div>
                    </div>

                    <!-- Forgot Password Form -->
                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <!-- Email Address -->
                        <div class="form-group">
                            <label for="email" class="form-label-with-icon">
                                
                                Email Address
                            </label>

                            <div class="input-with-icon">
                                <div class="input-icon">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <x-text-input
                                    id="email"
                                    class="block mt-1 w-full form-input-with-icon"
                                    type="email"
                                    name="email"
                                    :value="old('email')"
                                    required
                                    autofocus
                                    autocomplete="username"
                                    placeholder="you@example.com"
                                />
                            </div>
                            <br>

                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Send Reset Link Button -->
                        <button type="submit" class="btn-signin">
                            <span>{{ __('Email Password Reset Link') }}</span>
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </button>
                    </form>

                    <!-- Back to Login Link -->
                    <div class="register-link text-center">
                        <span>Remember your password? </span>
                        <a href="{{ route('login') }}">Sign in</a>
                    </div>

                    @include('auth.partials.footer')

                </div>

            </div>

        </div>
    </div>
</x-guest-layout>