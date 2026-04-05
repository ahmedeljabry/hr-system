<?php

namespace Tests\Feature\Client;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SubscriptionExpiryTest extends TestCase
{
    use RefreshDatabase;

    public function test_expired_client_cannot_access_portal()
    {
        $client = \App\Models\Client::factory()->create([
            'status' => 'expired',
            'subscription_end' => now()->subDay(),
        ]);
        
        $clientUser = \App\Models\User::factory()->create([
            'role' => 'client',
            'client_id' => $client->id,
        ]);

        $response = $this->actingAs($clientUser)->get('/client/dashboard');
        
        $response->assertRedirect('http://localhost/subscription/renewal');
    }

    public function test_expired_employee_cannot_access_portal()
    {
        $client = \App\Models\Client::factory()->create([
            'status' => 'expired',
            'subscription_end' => now()->subDay(),
        ]);
        
        $employeeUser = \App\Models\User::factory()->create([
            'role' => 'employee',
            'client_id' => $client->id,
        ]);

        $response = $this->actingAs($employeeUser)->get('/employee/dashboard');
        
        $response->assertRedirect('http://localhost/subscription/renewal');
    }

    public function test_super_admin_bypasses_expiry()
    {
        $superAdmin = \App\Models\User::factory()->create([
            'role' => 'super_admin',
        ]);

        $response = $this->actingAs($superAdmin)->get('/admin/dashboard');
        
        $response->assertOk();
    }
}
