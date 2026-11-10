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

        <!-- Dark Navy Gradient Overlay -->
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v13a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z"></path>
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

            <!-- ===== RIGHT SIDE: Login Card ===== -->
            <div class="login-right">

                <div class="login-card">

                    <!-- Session Status -->
                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <!-- Card Header -->
                    <div class="login-card-header">
                        <img src="{{ asset('images/nemsu-logo.png') }}" alt="NEMSU official logo" class="login-logo object-contain">
                        <h2 class="login-title">NEMSU Tandag Campus</h2>
                        <p class="login-subtitle">Student Information Management System</p>
                        <div class="login-divider"></div>
                    </div>

                    <!-- Welcome Message Strip -->
                    <div class="welcome-strip">
                        <svg aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Secure access to your student information</span>
                    </div>

                    <!-- Welcome Text -->
                    <div class="welcome-text">
                        <h3>Sign in to your account</h3>
                        <p>Enter your registered email address and password.</p>
                    </div>

                    <!-- Login Form -->
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Email Address -->
                        <div class="form-group">
                            <label for="email" class="form-label-with-icon">
                                Email Address
                            </label>

                            <div class="input-with-icon">
                                <div class="input-icon" aria-hidden="true">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <rect x="3" y="5" width="18" height="14" rx="2" stroke-width="1.8"></rect>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m4 7 8 6 8-6"></path>
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

                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Password -->
                        <div class="form-group mt-4">
                            <label for="password" class="form-label-with-icon">
                                Password
                            </label>

                            <div class="input-with-icon">
                                <div class="input-icon" aria-hidden="true">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <rect x="4" y="10" width="16" height="11" rx="2" stroke-width="1.8"></rect>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 10V7a4 4 0 1 1 8 0v3"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 14v3"></path>
                                    </svg>
                                </div>
                                <x-text-input
                                    id="password"
                                    class="block mt-1 w-full form-input-with-icon pr-12"
                                    type="password"
                                    name="password"
                                    x-ref="password"
                                    required
                                    autocomplete="current-password"
                                    placeholder="Enter your password"
                                />
                                <button
                                    type="button"
                                    class="password-toggle-btn"
                                    @click="toggle()"
                                    :aria-label="show ? 'Hide password' : 'Show password'"
                                >
                                    <template x-if="show">
                                        <svg aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m3 3 18 18M10.6 10.6a2 2 0 0 0 2.8 2.8"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9.9 5.2A10.7 10.7 0 0 1 12 5c4.5 0 8.3 2.9 9.5 7a10.8 10.8 0 0 1-3.1 4.6M6.2 6.2A10.8 10.8 0 0 0 2.5 12c.5 1.7 1.5 3.2 2.8 4.4A10 10 0 0 0 12 19c.8 0 1.5-.1 2.2-.3"></path>
                                        </svg>
                                    </template>
                                    <template x-if="!show">
                                        <svg aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.5 12s3.4-7 9.5-7 9.5 7 9.5 7-3.4 7-9.5 7-9.5-7-9.5-7Z"></path>
                                            <circle cx="12" cy="12" r="3" stroke-width="1.8"></circle>
                                        </svg>
                                    </template>
                                </button>
                            </div>

                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Remember Me -->
                        <div class="form-options">
                            <label for="remember_me" class="remember-me">
                                <input id="remember_me" type="checkbox" name="remember">
                                <span>{{ __('Remember me') }}</span>
                            </label>
                        </div>

                        <!-- Sign In Button -->
                        <button type="submit" class="btn-signin">
                            <span>{{ __('Sign In') }}</span>
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </button>
                    </form>

                    <!-- Action Links: Forgot Password & Register -->
                    <div class="login-actions">
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="action-link">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                                </svg>
                                {{ __('Forgot your password?') }}
                            </a>
                        @endif
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="action-link register-action">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                {{ __('Need an account? Register here') }}
                            </a>
                        @endif
                    </div>

                    @include('auth.partials.footer')

                </div>

            </div>

        </div>
    </div>
</x-guest-layout>
