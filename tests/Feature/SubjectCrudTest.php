<?php

namespace Tests\Feature;

use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubjectCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_manage_subjects(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->actingAs($admin);

        $this->get(route('subjects.index'))->assertOk();

        $this->post(route('subjects.store'), [
            'subject_code' => 'IT101',
            'subject_name' => 'Introduction to Computing',
            'units' => 3,
        ])->assertRedirect(route('subjects.index'));

        $this->assertDatabaseHas('subjects', [
            'subject_code' => 'IT101',
        ]);

        $subject = Subject::where('subject_code', 'IT101')
            ->firstOrFail();

        $this->get(route('subjects.show', $subject))->assertOk();
        $this->get(route('subjects.edit', $subject))->assertOk();

        $this->put(route('subjects.update', $subject), [
            'subject_code' => 'IT101',
            'subject_name' => 'Updated Computing',
            'units' => 4,
        ])->assertRedirect(route('subjects.index'));

        $this->assertDatabaseHas('subjects', [
            'subject_code' => 'IT101',
            'subject_name' => 'Updated Computing',
            'units' => 4,
        ]);

        $this->delete(route('subjects.destroy', $subject))
            ->assertRedirect(route('subjects.index'));

        $this->assertDatabaseMissing('subjects', [
            'id' => $subject->id,
        ]);
    }

    public function test_staff_cannot_modify_subjects(): void
    {
        $staff = User::factory()->create([
            'role' => 'staff',
        ]);

        $subject = Subject::create([
            'subject_code' => 'IT102',
            'subject_name' => 'Programming 1',
            'units' => 3,
        ]);

        $this->actingAs($staff);

        $this->get(route('subjects.index'))
            ->assertOk();

        $this->get(route('subjects.show', $subject))
            ->assertOk();

        $this->get(route('subjects.create'))
            ->assertForbidden();

        $this->post(route('subjects.store'), [
            'subject_code' => 'IT103',
            'subject_name' => 'Database Systems',
            'units' => 3,
        ])->assertForbidden();

        $this->put(route('subjects.update', $subject), [
            'subject_code' => 'IT102',
            'subject_name' => 'Changed Name',
            'units' => 4,
        ])->assertForbidden();

        $this->delete(route('subjects.destroy', $subject))
            ->assertForbidden();
    }

    public function test_duplicate_subject_code_is_rejected(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        Subject::create([
            'subject_code' => 'IT101',
            'subject_name' => 'Introduction to Computing',
            'units' => 3,
        ]);

        $this->actingAs($admin)
            ->post(route('subjects.store'), [
                'subject_code' => 'IT101',
                'subject_name' => 'Another Subject',
                'units' => 3,
            ])
            ->assertSessionHasErrors('subject_code');
    }

    public function test_invalid_subject_data_is_rejected(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->from(route('subjects.create'))
            ->post(route('subjects.store'), [
                'subject_code' => '',
                'subject_name' => str_repeat('N', 256),
                'units' => 13,
            ])
            ->assertRedirect(route('subjects.create'))
            ->assertSessionHasErrors([
                'subject_code',
                'subject_name',
                'units',
            ]);

        $this->post(route('subjects.store'), [
            'subject_code' => str_repeat('C', 31),
            'subject_name' => 'Valid subject',
            'units' => 0,
        ])->assertSessionHasErrors(['subject_code', 'units']);

        $this->assertDatabaseCount('subjects', 0);
    }

    public function test_missing_subject_pages_return_not_found(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));

        $this->get('/subjects/999999')->assertNotFound();
        $this->get('/subjects/999999/edit')->assertNotFound();
        $this->put('/subjects/999999', [
            'subject_code' => 'IT999',
            'subject_name' => 'Missing Subject',
            'units' => 3,
        ])->assertNotFound();
        $this->delete('/subjects/999999')->assertNotFound();
    }
}
