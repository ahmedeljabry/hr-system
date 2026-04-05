<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class RouteAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_can_access_admin_routes()
    {
        // Create super admin user
        $admin = User::factory()->create(['role' => 'super_admin']);

        // Act as admin and try to access admin dashboard
        $response = $this->actingAs($admin)->get('/admin/dashboard');

        // Assert access granted
        $response->assertStatus(200);
    }

    public function test_non_super_admin_cannot_access_admin_routes()
    {
        // Create regular client user
        $client = User::factory()->create(['role' => 'client']);

        // Act as client and try to access admin dashboard
        $response = $this->actingAs($client)->get('/admin/dashboard');

        // Assert access denied with 403
        $response->assertStatus(403);
    }
}