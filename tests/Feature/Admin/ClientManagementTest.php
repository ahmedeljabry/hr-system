<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Client;

class ClientManagementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function super_admin_can_view_all_clients()
    {
        $admin = User::factory()->create(['role' => 'super_admin']);
        $clientA = Client::create(['name' => 'Company A', 'status' => 'active']);
        $clientB = Client::create(['name' => 'Company B', 'status' => 'suspended']);

        $response = $this->actingAs($admin)->get('/admin/clients');

        $response->assertStatus(200);
        $response->assertSee('Company A');
        $response->assertSee('Company B');
    }

    /** @test */
    public function other_roles_cannot_view_admin_clients_list()
    {
        $clientUser = User::factory()->create(['role' => 'client']);
        
        $this->actingAs($clientUser)->get('/admin/clients')->assertStatus(403);
    }

    /** @test */
    public function super_admin_can_update_client_status()
    {
        $admin = User::factory()->create(['role' => 'super_admin']);
        $client = Client::create(['name' => 'Test Corp', 'status' => 'active']);

        $response = $this->actingAs($admin)->patch("/admin/clients/{$client->id}/status", [
            'status' => 'suspended',
        ]);

        $response->assertStatus(302);
        $this->assertEquals('suspended', $client->fresh()->status);
    }

    /** @test */
    public function super_admin_can_update_subscription_end_date()
    {
        $admin = User::factory()->create(['role' => 'super_admin']);
        $client = Client::create(['name' => 'Test Corp', 'status' => 'active']);
        $futureDate = now()->addYear()->format('Y-m-d');

        $response = $this->actingAs($admin)->patch("/admin/clients/{$client->id}/subscription", [
            'subscription_end' => $futureDate,
        ]);

        $response->assertStatus(302);
        $this->assertEquals($futureDate, $client->fresh()->subscription_end->format('Y-m-d'));
    }
}
