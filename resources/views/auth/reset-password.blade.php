<x-guest-layout>
    @php
        $bgImageExists = file_exists(public_path('images/nemsu-tandag-administration.webp'));
    @endphp

    <div class="login-page" @if($bgImageExists) data-bg-image="true" @endif x-data="{
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
                    <!-- Academic Icon -->
                    <svg class="logo-academic" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 0112 21.75 12.083 12.083 0 01-12 21.75L12 14z"></path>
                    </svg>

                    <h1 class="brand-title">
                        NORTHEASTERN MINDANAO<br>STATE UNIVERSITY
                    </h1>

                    <div class="brand-accent-line"></div>

                    <p class="brand-subtitle">NEMSU TANDAG CAMPUS</p>

                    <p class="brand-system">Student Information Management System</p>

                    <span class="school-year-badge">SCHOOL YEAR 2026–2027</span>

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

            <!-- ===== RIGHT SIDE: Reset Password Card ===== -->
            <div class="login-right">

                <div class="login-card">

                    <!-- Session Status -->
                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <!-- Card Header -->
                    <div class="login-card-header text-center">
                        <svg class="login-logo" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 0112 21.75 12.083 12.083 0 01-12 21.75L12 14z"></path>
                        </svg>

                        <h2 class="login-title">Reset your password</h2>
                        <p class="login-subtitle">
                            Choose a new password for your account.
                        </p>

                        <div class="login-divider"></div>
                    </div>

                    <!-- Reset Password Form -->
                    <form method="POST" action="{{ route('password.store') }}">
                        @csrf

                        <!-- Password Reset Token -->
                        <input type="hidden" name="token" value="{{ $request->route('token') }}">

                        <!-- Email Address -->
                        <div class="form-group">
                            <label for="email" class="form-label-with-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
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
                                    :value="old('email', $request->email)"
                                    required
                                    autofocus
                                    autocomplete="username"
                                />
                            </div>

                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Password -->
                        <div class="form-group mt-4">
                            <label for="password" class="form-label-with-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m0 4a9 9 0 100-18 9 9 0 000 18z"></path>
                                </svg>
                                New Password
                            </label>

                            <div class="input-with-icon">
                                <div class="input-icon">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m0 4a9 9 0 100-18 9 9 0 000 18z"></path>
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
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m0 4a9 9 0 100-18 9 9 0 000 18z"></path>
                                </svg>
                                Confirm New Password
                            </label>

                            <div class="input-with-icon">
                                <div class="input-icon">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m0 4a9 9 0 100-18 9 9 0 000 18z"></path>
                                    </svg>
                                </div>
                                <x-text-input
                                    id="password_confirmation"
                                    class="block mt-1 w-full form-input-with-icon pr-12"
                                    type="password"
                                    name="password_confirmation"
                                    required
                                    autocomplete="new-password"
                                />
                            </div>

                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                        <!-- Reset Password Button -->
                        <button type="submit" class="btn-signin">
                            <span>{{ __('Reset Password') }}</span>
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

                </div>

            </div>

        </div>
    </div>
</x-guest-layout>