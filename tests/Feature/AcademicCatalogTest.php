<?php

namespace Tests\Feature;

use App\Models\College;
use App\Models\Department;
use App\Models\Program;
use App\Models\User;
use Database\Seeders\NemsuAcademicCatalogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AcademicCatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_catalog_seeder_creates_expected_hierarchy_and_is_idempotent(): void
    {
        $this->seed(NemsuAcademicCatalogSeeder::class);

        $this->assertDatabaseCount('colleges', 7);
        $this->assertDatabaseCount('departments', 13);
        $this->assertDatabaseCount('programs', 22);

        $this->seed(NemsuAcademicCatalogSeeder::class);

        $this->assertDatabaseCount('colleges', 7);
        $this->assertDatabaseCount('departments', 13);
        $this->assertDatabaseCount('programs', 22);
        $this->assertDatabaseHas('colleges', [
            'name' => 'College of Information Technology Education',
            'abbreviation' => 'CITE',
        ]);
        $this->assertDatabaseHas('programs', [
            'name' => 'Juris Doctor (JD)',
        ]);
    }

    public function test_staff_and_admin_can_browse_and_filter_programs(): void
    {
        $this->seed(NemsuAcademicCatalogSeeder::class);
        $staff = User::factory()->create(['role' => 'staff']);

        $this->actingAs($staff)
            ->get(route('academic-programs.index'))
            ->assertOk()
            ->assertSee('NEMSU Tandag Campus')
            ->assertSee('College of Law')
            ->assertSee('Juris Doctor (JD)');

        $college = College::query()
            ->where('abbreviation', 'CITE')
            ->firstOrFail();

        $this->get(route('academic-programs.index', [
            'college_id' => $college->id,
            'search' => 'Computer Science',
        ]))
            ->assertOk()
            ->assertSee('Bachelor of Science in Computer Science')
            ->assertDontSee('Juris Doctor (JD)');

        $this->get(route('academic-programs.index', ['search' => 'not-a-program']))
            ->assertOk()
            ->assertSee('No academic programs match');

        $this->actingAs(User::factory()->create(['role' => 'admin']))
            ->get(route('academic-programs.index'))
            ->assertOk();
    }

    public function test_unauthenticated_users_cannot_browse_the_catalog(): void
    {
        $this->get(route('academic-programs.index'))
            ->assertRedirect(route('login'));
    }
}
