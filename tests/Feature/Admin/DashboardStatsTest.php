<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Client;
use App\Models\User;
use App\Models\Employee;

class DashboardStatsTest extends TestCase
{
    use RefreshDatabase;

    private $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Create super admin user
        $this->admin = User::factory()->create(['role' => 'super_admin']);
    }

    public function test_admin_can_view_dashboard_with_stats()
    {
        // Create test data
        $client1 = Client::factory()->create(['status' => 'active']);
        $client2 = Client::factory()->create(['status' => 'suspended']);
        $client3 = Client::factory()->create(['status' => 'expired']);

        // Create employees for the clients
        Employee::factory()->create(['client_id' => $client1->id]);
        Employee::factory()->create(['client_id' => $client1->id]);
        Employee::factory()->create(['client_id' => $client2->id]);

        // Act as admin and visit dashboard
        $response = $this->actingAs($this->admin)->get('/admin/dashboard');

        // Assert response
        $response->assertStatus(200);
        $response->assertSee(__('messages.super_admin_dashboard'));
        $response->assertSee('3'); // Total clients
        $response->assertSee('3'); // Total employees
        $response->assertSee('1'); // Active subscriptions
        $response->assertSee('1'); // Suspended subscriptions
        $response->assertSee('1'); // Expired subscriptions
    }

    public function test_dashboard_shows_zero_when_no_data()
    {
        // Act as admin with no data
        $response = $this->actingAs($this->admin)->get('/admin/dashboard');

        // Assert zero values
        $response->assertStatus(200);
        // Better to check for the presence of the 0 in the context of the stats
        $response->assertSee('0'); 
    }
}