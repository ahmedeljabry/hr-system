<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Database\Seeders\SuperAdminSeeder;

class SuperAdminSeederTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function seeder_creates_super_admin_user()
    {
        $this->seed(SuperAdminSeeder::class);

        $this->assertDatabaseHas('users', [
            'email' => 'admin@hr-system.com',
            'role' => 'super_admin',
        ]);
    }

    /** @test */
    public function seeder_is_idempotent()
    {
        $this->seed(SuperAdminSeeder::class);
        $this->seed(SuperAdminSeeder::class);

        $this->assertEquals(1, User::where('email', 'admin@hr-system.com')->count());
    }
}
