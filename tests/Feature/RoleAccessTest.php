
<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_staff_cannot_access_admin_page(): void
    {
        $staff = User::factory()->create([
            'role' => 'staff',
        ]);

        $this->actingAs($staff)
            ->get('/admin')
            ->assertForbidden();
    }

    public function test_admin_can_access_admin_page(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->actingAs($admin)
            ->get('/admin')
            ->assertOk();
    }

    public function test_guest_cannot_access_admin_page(): void
    {
        $this->get('/admin')
            ->assertRedirect(route('login'));
    }
}
