<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminUserManagementTest extends TestCase
{
    use RefreshDatabase;

    private function tinyPng(): string
    {
        return base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAIAAACQd1PeAAAADUlEQVR4nGP4z8AAAAMBAQDJ/pLvAAAAAElFTkSuQmCC',
            true
        );
    }

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
            ->assertSee('Add user')
            ->assertSee('Edit')
            ->assertSee('sm:hidden', false)
            ->assertSee('sm:block', false);

        $this->get(route('admin.users.show', $staff))
            ->assertOk()
            ->assertSee($staff->email)
            ->assertSee('Account profile')
            ->assertSee('Edit account')
            ->assertDontSee('type="password"', false)
            ->assertDontSee('remember_token')
            ->assertDontSee('personal_access_tokens');

        $this->get(route('admin.users.edit', $staff))
            ->assertOk()
            ->assertSee('Access role')
            ->assertSee('Profile photo')
            ->assertSee('enctype="multipart/form-data"', false)
            ->assertSee('least privileged role')
            ->assertSee('Save changes');

        $this->get(route('admin.users.create'))
            ->assertOk()
            ->assertSee('Create user account')
            ->assertSee('Confirm temporary password');
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

    public function test_admin_can_create_an_account_and_assign_its_role(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->post(route('admin.users.store'), [
                'name' => 'New Staff Member',
                'email' => 'new.staff@example.com',
                'role' => 'staff',
                'password' => 'secure-pass-123',
                'password_confirmation' => 'secure-pass-123',
            ])
            ->assertRedirect();

        $user = User::where('email', 'new.staff@example.com')->firstOrFail();
        $this->assertSame('New Staff Member', $user->name);
        $this->assertSame('staff', $user->role);
        $this->assertTrue(password_verify('secure-pass-123', $user->password));
    }

    public function test_admin_can_upload_replace_and_remove_any_users_profile_photo(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['role' => 'admin']);
        $staff = User::factory()->create(['role' => 'staff']);

        $this->actingAs($admin)
            ->patch(route('admin.users.update', $staff), [
                'role' => 'staff',
                'profile_photo' => UploadedFile::fake()->createWithContent('staff-photo.png', $this->tinyPng()),
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('admin.users.show', $staff));

        $staff->refresh();
        $firstPhoto = $staff->profile_photo_path;
        $this->assertNotNull($firstPhoto);
        Storage::disk('public')->assertExists($firstPhoto);
        $this->get(route('admin.users.show', $staff))
            ->assertOk()
            ->assertSee('src="'.asset('storage/'.$firstPhoto).'"', false);

        $this->patch(route('admin.users.update', $staff), [
            'role' => 'staff',
            'profile_photo' => UploadedFile::fake()->createWithContent('replacement-photo.png', $this->tinyPng()),
        ])->assertSessionHasNoErrors();

        $staff->refresh();
        $replacementPhoto = $staff->profile_photo_path;
        $this->assertNotSame($firstPhoto, $replacementPhoto);
        Storage::disk('public')->assertMissing($firstPhoto);
        Storage::disk('public')->assertExists($replacementPhoto);

        $this->patch(route('admin.users.update', $staff), [
            'role' => 'staff',
            'remove_profile_photo' => '1',
        ])->assertSessionHasNoErrors();

        $this->assertNull($staff->refresh()->profile_photo_path);
        Storage::disk('public')->assertMissing($replacementPhoto);
    }

    public function test_admin_user_creation_accepts_an_optional_profile_photo(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->post(route('admin.users.store'), [
                'name' => 'Photo Staff Member',
                'email' => 'photo.staff@example.com',
                'role' => 'staff',
                'password' => 'secure-pass-123',
                'password_confirmation' => 'secure-pass-123',
                'profile_photo' => UploadedFile::fake()->createWithContent('new-user-photo.png', $this->tinyPng()),
            ])
            ->assertSessionHasNoErrors();

        $user = User::where('email', 'photo.staff@example.com')->firstOrFail();
        $this->assertNotNull($user->profile_photo_path);
        Storage::disk('public')->assertExists($user->profile_photo_path);
    }

    public function test_account_creation_rejects_duplicate_email_invalid_role_and_unconfirmed_password(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        User::factory()->create(['email' => 'taken@example.com']);

        $this->actingAs($admin)
            ->from(route('admin.users.create'))
            ->post(route('admin.users.store'), [
                'name' => 'Invalid New User',
                'email' => 'taken@example.com',
                'role' => 'owner',
                'password' => 'secure-pass-123',
                'password_confirmation' => 'does-not-match',
            ])
            ->assertRedirect(route('admin.users.create'))
            ->assertSessionHasErrors(['email', 'role', 'password']);

        $this->assertDatabaseMissing('users', ['name' => 'Invalid New User']);
    }

    public function test_admin_can_edit_account_details_and_optionally_change_password(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $staff = User::factory()->create(['role' => 'staff']);
        $staff->createToken('test-token');
        DB::table('sessions')->insert([
            'id' => 'user-session',
            'user_id' => $staff->id,
            'payload' => 'test-session',
            'last_activity' => now()->timestamp,
        ]);
        DB::table('password_reset_tokens')->insert([
            'email' => $staff->email,
            'token' => 'test-reset-token',
            'created_at' => now(),
        ]);

        $this->actingAs($admin)
            ->patch(route('admin.users.update', $staff), [
                'name' => 'Updated Staff',
                'email' => 'updated.staff@example.com',
                'role' => 'staff',
                'password' => 'another-secure-pass',
                'password_confirmation' => 'another-secure-pass',
            ])
            ->assertRedirect(route('admin.users.show', $staff));

        $staff->refresh();
        $this->assertSame('Updated Staff', $staff->name);
        $this->assertSame('updated.staff@example.com', $staff->email);
        $this->assertTrue(password_verify('another-secure-pass', $staff->password));
    }

    public function test_admin_can_delete_another_account_but_not_self_or_last_admin(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $staff = User::factory()->create(['role' => 'staff']);

        $this->actingAs($admin)
            ->delete(route('admin.users.destroy', $admin))
            ->assertSessionHasErrors('account');
        $this->assertDatabaseHas('users', ['id' => $admin->id]);

        $this->delete(route('admin.users.destroy', $staff))
            ->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseMissing('users', ['id' => $staff->id]);
        $this->assertDatabaseMissing('personal_access_tokens', ['tokenable_id' => $staff->id]);
        $this->assertDatabaseMissing('sessions', ['id' => 'user-session']);
        $this->assertDatabaseMissing('password_reset_tokens', ['email' => $staff->email]);

        $this->delete(route('admin.users.destroy', $admin))
            ->assertSessionHasErrors('account');
        $this->assertDatabaseHas('users', ['id' => $admin->id, 'role' => 'admin']);
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
        $this->get(route('admin.users.create'))->assertForbidden();
        $this->get(route('admin.users.show', $admin))->assertForbidden();
        $this->get(route('admin.users.edit', $admin))->assertForbidden();
        $this->post(route('admin.users.store'), [
            'name' => 'Unauthorized User',
            'email' => 'unauthorized@example.com',
            'role' => 'staff',
            'password' => 'secure-pass-123',
            'password_confirmation' => 'secure-pass-123',
        ])->assertForbidden();
        $this->patch(route('admin.users.update', $admin), [
            'role' => 'staff',
        ])->assertForbidden();
        $this->delete(route('admin.users.destroy', $admin))->assertForbidden();
    }

    public function test_guests_are_redirected_from_user_management(): void
    {
        $user = User::factory()->create();

        $this->get(route('admin.users.index'))->assertRedirect(route('login'));
        $this->get(route('admin.users.create'))->assertRedirect(route('login'));
        $this->get(route('admin.users.show', $user))->assertRedirect(route('login'));
    }
}
