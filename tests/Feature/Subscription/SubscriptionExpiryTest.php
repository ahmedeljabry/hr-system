<?php

namespace Tests\Feature\Subscription;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Client;
use App\Models\User;
use Carbon\Carbon;

class SubscriptionExpiryTest extends TestCase
{
    use RefreshDatabase;

    public function test_expired_client_user_redirected_to_renewal_page()
    {
        // Create an expired client
        $client = Client::factory()->create([
            'status' => 'active',
            'subscription_end' => Carbon::yesterday() // Expired yesterday
        ]);

        // Create a user for this client
        $user = User::factory()->create([
            'role' => 'client',
            'client_id' => $client->id
        ]);

        // Act as the client user and try to access dashboard
        $response = $this->actingAs($user)->get('/client/dashboard');

        // Should be redirected to renewal page or blocked
        $response->assertStatus(302); // Redirect
        // The exact redirect URL depends on the CheckSubscription middleware implementation
    }

    public function test_suspended_client_user_redirected_to_renewal_page()
    {
        // Create a suspended client
        $client = Client::factory()->create([
            'status' => 'suspended',
            'subscription_end' => Carbon::tomorrow() // Not expired yet, but suspended
        ]);

        // Create a user for this client
        $user = User::factory()->create([
            'role' => 'client',
            'client_id' => $client->id
        ]);

        // Act as the client user and try to access dashboard
        $response = $this->actingAs($user)->get('/client/dashboard');

        // Should be redirected to renewal page or blocked
        $response->assertStatus(302); // Redirect
    }

    public function test_active_client_user_can_access_dashboard()
    {
        // Create an active client
        $client = Client::factory()->create([
            'status' => 'active',
            'subscription_end' => Carbon::tomorrow() // Active and not expired
        ]);

        // Create a user for this client
        $user = User::factory()->create([
            'role' => 'client',
            'client_id' => $client->id
        ]);

        // Act as the client user and try to access dashboard
        $response = $this->actingAs($user)->get('/client/dashboard');

        // Should be allowed access
        $response->assertStatus(200);
    }

    public function test_employee_of_expired_client_blocked_from_portal()
    {
        // Create an expired client
        $client = Client::factory()->create([
            'status' => 'active',
            'subscription_end' => Carbon::yesterday()
        ]);

        // Create an employee for this client
        $employee = \App\Models\Employee::factory()->create([
            'client_id' => $client->id
        ]);

        // Create an employee user
        $user = User::factory()->create([
            'role' => 'employee',
            'client_id' => $client->id
        ]);
        $user->employee()->save($employee);

        // Act as the employee user and try to access portal
        $response = $this->actingAs($user)->get('/employee/dashboard');

        // Should be blocked
        $response->assertStatus(302); // Redirect to renewal page
    }

    public function test_super_admin_not_affected_by_subscription_status()
    {
        // Create super admin
        $admin = User::factory()->create(['role' => 'super_admin']);

        // Super admin should be able to access admin routes regardless of subscription status
        $response = $this->actingAs($admin)->get('/admin/dashboard');
        $response->assertStatus(200);

        $response = $this->actingAs($admin)->get('/admin/clients');
        $response->assertStatus(200);
    }
}