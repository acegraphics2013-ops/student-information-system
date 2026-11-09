<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminUserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_search_view_and_filter_user_accounts(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $staff = User::factory()->create([
            'name' => 'Searchable Staff',
            'email' => 'searchable@example.com',
            'role' => 'staff',
        ]);
        User::factory()->create([
            'name' => 'Other Staff',
            'email' => 'other@example.com',
            'role' => 'staff',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.users.index', [
                'search' => 'searchable@example.com',
                'role' => 'staff',
            ]))
            ->assertOk()
            ->assertSee('Searchable Staff')
            ->assertDontSee('Other Staff');

        $this->get(route('admin.users.index'))
            ->assertOk()
            ->assertSee('Apply filters')
            ->assertSee('Registered accounts')
            ->assertSee('Manage role')
            ->assertSee('sm:hidden', false)
            ->assertSee('sm:block', false);

        $this->get(route('admin.users.show', $staff))
            ->assertOk()
            ->assertSee($staff->email)
            ->assertSee('Account profile')
            ->assertSee('Manage access role')
            ->assertDontSee('password')
            ->assertDontSee('remember_token')
            ->assertDontSee('personal_access_tokens');

        $this->get(route('admin.users.edit', $staff))
            ->assertOk()
            ->assertSee('Choose an access role')
            ->assertSee('least privileged role')
            ->assertSee('Save role');
    }

    public function test_admin_can_promote_and_demote_another_account(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $staff = User::factory()->create(['role' => 'staff']);
        $this->actingAs($admin);

        $this->patch(route('admin.users.update', $staff), [
            'role' => 'admin',
        ])->assertRedirect(route('admin.users.show', $staff));

        $this->assertDatabaseHas('users', [
            'id' => $staff->id,
            'role' => 'admin',
        ]);

        $this->patch(route('admin.users.update', $staff), [
            'role' => 'staff',
        ])->assertRedirect(route('admin.users.show', $staff));

        $this->assertDatabaseHas('users', [
            'id' => $staff->id,
            'role' => 'staff',
        ]);
    }

    public function test_admin_cannot_self_demote_or_demote_the_last_administrator(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->patch(route('admin.users.update', $admin), [
                'role' => 'staff',
            ])
            ->assertSessionHasErrors('role');

        $this->assertDatabaseHas('users', [
            'id' => $admin->id,
            'role' => 'admin',
        ]);

        $anotherAdmin = User::factory()->create(['role' => 'admin']);
        $admin->delete();

        $this->actingAs($anotherAdmin)
            ->patch(route('admin.users.update', $anotherAdmin), [
                'role' => 'staff',
            ])
            ->assertSessionHasErrors('role');

        $this->assertDatabaseHas('users', [
            'id' => $anotherAdmin->id,
            'role' => 'admin',
        ]);
    }

    public function test_role_updates_reject_invalid_values_and_staff_cannot_manage_users(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $staff = User::factory()->create(['role' => 'staff']);

        $this->actingAs($admin)
            ->patch(route('admin.users.update', $staff), [
                'role' => 'owner',
            ])
            ->assertSessionHasErrors('role');

        $this->assertDatabaseHas('users', [
            'id' => $staff->id,
            'role' => 'staff',
        ]);

        $this->actingAs($staff);
        $this->get(route('admin.users.index'))->assertForbidden();
        $this->get(route('admin.users.show', $admin))->assertForbidden();
        $this->get(route('admin.users.edit', $admin))->assertForbidden();
        $this->patch(route('admin.users.update', $admin), [
            'role' => 'staff',
        ])->assertForbidden();
    }

    public function test_guests_are_redirected_from_user_management(): void
    {
        $user = User::factory()->create();

        $this->get(route('admin.users.index'))->assertRedirect(route('login'));
        $this->get(route('admin.users.show', $user))->assertRedirect(route('login'));
    }
}
