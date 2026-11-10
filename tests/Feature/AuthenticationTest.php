<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_open_authentication_pages(): void
    {
        $this->get(route('register'))->assertOk();
        $loginPage = $this->get(route('login'))
            ->assertOk()
            ->assertSee('NEMSU official logo')
            ->assertSee('images/nemsu-logo.png')
            ->assertSee('Student Information Management System')
            ->assertSee('Developed by: Ace R. Cabuga, MSCS')
            ->assertSee('Disclaimer:')
            ->assertSee('This website is an unofficial student project developed solely')
            ->assertSee('placeholder="Enter your password"', false)
            ->assertSee('<title>NEMSU Tandag | Student Information System</title>', false)
            ->assertSee('rel="icon"', false);

        $this->assertSame(1, substr_count($loginPage->getContent(), 'Forgot your password?'));
    }

    public function test_guest_is_redirected_to_login_from_dashboard(): void
    {
        $this->get(route('dashboard'))
            ->assertRedirect(route('login'));
    }

    public function test_user_can_register_and_logout(): void
    {
        $this->post(route('register'), [
            'name' => 'Jamie Student',
            'email' => 'jamie@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'admin',
        ])->assertRedirect(route('dashboard'));

        $user = User::where('email', 'jamie@example.com')->firstOrFail();

        $this->assertAuthenticatedAs($user);
        $this->assertSame('staff', $user->role);
        $this->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Jamie Student');

        $this->post(route('logout'))
            ->assertRedirect('/');

        $this->assertGuest();
    }

    public function test_user_can_log_in(): void
    {
        $user = User::factory()->create([
            'email' => 'jamie@example.com',
            'password' => 'password123',
        ]);

        $this->post(route('login'), [
            'email' => 'jamie@example.com',
            'password' => 'password123',
        ])->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_invalid_login_is_rejected(): void
    {
        $this->from(route('login'))
            ->post(route('login'), [
                'email' => 'missing@example.com',
                'password' => 'wrong-password',
            ])
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }
}
