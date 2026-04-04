<?php

namespace Tests\Feature\Client;

use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_shows_employee_count(): void
    {
        $client = Client::factory()->create(['status' => 'active']);
        $user = User::factory()->create(['role' => 'client', 'client_id' => $client->id]);
        Employee::factory()->count(5)->create(['client_id' => $client->id]);

        $response = $this->actingAs($user)->get('/client/dashboard');
        $response->assertStatus(200);
        $response->assertSeeText((string) 5);
        $response->assertSee(__('messages.subscription_active'));
    }

    public function test_dashboard_shows_expiry_warning_when_near(): void
    {
        $client = Client::factory()->create([
            'status' => 'active',
            'subscription_end' => now()->addDays(3),
        ]);
        $user = User::factory()->create(['role' => 'client', 'client_id' => $client->id]);

        $response = $this->actingAs($user)->get('/client/dashboard');
        $response->assertStatus(200);
        // Uses the exact translated string pattern
        $response->assertSee(__('messages.subscription_expiry_warning', ['days' => 3]));
    }

    public function test_dashboard_no_warning_when_far_from_expiry(): void
    {
        $client = Client::factory()->create([
            'status' => 'active',
            'subscription_end' => now()->addDays(60),
        ]);
        $user = User::factory()->create(['role' => 'client', 'client_id' => $client->id]);

        $response = $this->actingAs($user)->get('/client/dashboard');
        $response->assertStatus(200);
        $response->assertDontSee(__('messages.subscription_expiry_warning', ['days' => 60]));
    }
}
