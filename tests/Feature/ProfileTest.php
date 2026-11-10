<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    private function tinyPng(): string
    {
        return base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAIAAACQd1PeAAAADUlEQVR4nGP4z8AAAAMBAQDJ/pLvAAAAAElFTkSuQmCC',
            true
        );
    }

    public function test_profile_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/profile');

        $response->assertOk()
            ->assertSee('Your profile')
            ->assertSee('Display photo')
            ->assertSee('Update Password')
            ->assertSee('Staff');
    }

    public function test_profile_information_can_be_updated(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $user->refresh();

        $this->assertSame('Test User', $user->name);
        $this->assertSame('test@example.com', $user->email);
        $this->assertNull($user->email_verified_at);
    }

    public function test_profile_update_cannot_change_the_users_role(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user)
            ->patch('/profile', [
                'name' => 'Updated Admin',
                'email' => $user->email,
                'role' => 'staff',
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'role' => 'admin',
            'name' => 'Updated Admin',
        ]);
    }

    public function test_email_verification_status_is_unchanged_when_the_email_address_is_unchanged(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Test User',
                'email' => $user->email,
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $this->assertNotNull($user->refresh()->email_verified_at);
    }

    public function test_user_can_upload_replace_and_remove_their_display_photo(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/profile/photo', [
                'profile_photo' => UploadedFile::fake()->createWithContent('first-photo.png', $this->tinyPng()),
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $user->refresh();
        $firstPhoto = $user->profile_photo_path;
        $this->assertNotNull($firstPhoto);
        Storage::disk('public')->assertExists($firstPhoto);
        $this->get('/profile')
            ->assertOk()
            ->assertSee('src="'.asset('storage/'.$firstPhoto).'"', false)
            ->assertSee($user->name.' display photo');

        $this->post('/profile/photo', [
            'profile_photo' => UploadedFile::fake()->createWithContent('replacement-photo.png', $this->tinyPng()),
        ])->assertSessionHasNoErrors();

        $user->refresh();
        $replacementPhoto = $user->profile_photo_path;
        $this->assertNotSame($firstPhoto, $replacementPhoto);
        Storage::disk('public')->assertMissing($firstPhoto);
        Storage::disk('public')->assertExists($replacementPhoto);

        $this->delete('/profile/photo')
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $this->assertNull($user->refresh()->profile_photo_path);
        Storage::disk('public')->assertMissing($replacementPhoto);
    }

    public function test_profile_photo_upload_rejects_non_images_and_oversized_files(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $this->actingAs($user)
            ->from('/profile')
            ->post('/profile/photo', [
                'profile_photo' => UploadedFile::fake()->create('document.pdf', 100, 'application/pdf'),
            ])
            ->assertRedirect('/profile')
            ->assertSessionHasErrors('profile_photo');

        $this->post('/profile/photo', [
            'profile_photo' => UploadedFile::fake()->createWithContent(
                'large-photo.png',
                $this->tinyPng().str_repeat('0', 2 * 1024 * 1024)
            ),
        ])
            ->assertRedirect('/profile')
            ->assertSessionHasErrors('profile_photo');

        $this->assertNull($user->refresh()->profile_photo_path);
        $this->assertSame([], Storage::disk('public')->allFiles('profile-photos'));
    }

    public function test_user_can_delete_their_account(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $photoPath = UploadedFile::fake()
            ->createWithContent('account-photo.png', $this->tinyPng())
            ->store('profile-photos', 'public');
        $user->update(['profile_photo_path' => $photoPath]);

        $response = $this
            ->actingAs($user)
            ->delete('/profile', [
                'password' => 'password',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/');

        $this->assertGuest();
        $this->assertNull($user->fresh());
        Storage::disk('public')->assertMissing($photoPath);
    }

    public function test_correct_password_must_be_provided_to_delete_account(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from('/profile')
            ->delete('/profile', [
                'password' => 'wrong-password',
            ]);

        $response
            ->assertSessionHasErrorsIn('userDeletion', 'password')
            ->assertRedirect('/profile');

        $this->assertNotNull($user->fresh());
    }
}
