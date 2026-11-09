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
        $this->get(route('login'))->assertOk();
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