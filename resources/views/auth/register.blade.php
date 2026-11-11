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

            <!-- ===== RIGHT SIDE: Register Card ===== -->
            <div class="login-right">

                <div class="login-card">

                    <!-- Session Status -->
                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <!-- Card Header -->
                    <div class="login-card-header text-center">
                        <img src="{{ asset('images/nemsu-logo.png') }}" alt="NEMSU official logo" class="login-logo object-contain">

                        <h2 class="login-title">Create your account</h2>
                        <p class="login-subtitle">
                            Join NEMSU Tandag Campus SIMS today.
                        </p>

                        <div class="login-divider"></div>
                    </div>

                    <!-- Register Form -->
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <!-- Name -->
                        <div class="form-group">
                            <label for="name" class="form-label-with-icon">
                               
                                Full Name
                            </label>

                            <div class="input-with-icon">
                                <div class="input-icon">
                                    <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"></path>
                                    </svg>
                                </div>
                                <x-text-input
                                    id="name"
                                    class="block mt-1 w-full form-input-with-icon"
                                    type="text"
                                    name="name"
                                    :value="old('name')"
                                    required
                                    autofocus
                                    autocomplete="name"
                                    placeholder="Juan Dela Cruz"
                                />
                            </div>

                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Email Address -->
                        <div class="form-group mt-4">
                            <label for="email" class="form-label-with-icon">
                               
                                Email Address
                            </label>

                            <div class="input-with-icon">
                                <div class="input-icon">
                                    <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916v-.243"></path>
                                    </svg>
                                </div>
                                <x-text-input
                                    id="email"
                                    class="block mt-1 w-full form-input-with-icon"
                                    type="email"
                                    name="email"
                                    :value="old('email')"
                                    required
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
                                <div class="input-icon">
                                    <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"></path>
                                    </svg>
                                </div>
                                <x-text-input
                                    id="password"
                                    class="block mt-1 w-full form-input-with-icon pr-12"
                                    type="password"
                                    name="password"
                                    x-ref="password"
                                    required
                                    autocomplete="new-password"
                                    placeholder="Enter your password"
                                />
                                <button
                                    type="button"
                                    class="password-toggle-btn"
                                    @click="toggle()"
                                    :aria-label="show ? 'Hide password' : 'Show password'"
                                >
                                    <template x-if="show">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l-3.29-3.29M5.302 5.302l-3.29-3.29"></path>
                                        </svg>
                                    </template>
                                    <template x-if="!show">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </template>
                                </button>
                            </div>

                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Confirm Password -->
                        <div class="form-group mt-4">
                            <label for="password_confirmation" class="form-label-with-icon">
                                
                                Confirm Password
                            </label>

                            <div class="input-with-icon">
                                <div class="input-icon">
                                    <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"></path>
                                    </svg>
                                </div>
                                <x-text-input
                                    id="password_confirmation"
                                    class="block mt-1 w-full form-input-with-icon pr-12"
                                    type="password"
                                    name="password_confirmation"
                                    required
                                    autocomplete="new-password"
                                    placeholder="Confirm your password"
                                />
                            </div>

                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                        <!-- Register Button -->
                        <button type="submit" class="btn-signin">
                            <span>{{ __('Register') }}</span>
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </button>
                    </form>

                    <!-- Already Registered Link -->
                    <div class="register-link text-center">
                        <span>Already registered? </span>
                        <a href="{{ route('login') }}">Sign in</a>
                    </div>

                    @include('auth.partials.footer')

                </div>

            </div>

        </div>
    </div>
</x-guest-layout>