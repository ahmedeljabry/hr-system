<?php

namespace Tests\Feature\Subscription;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Client;

class SubscriptionCheckTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function suspended_client_user_is_redirected_to_renewal_page()
    {
        $client = Client::create(['name' => 'Suspended Corp', 'status' => 'suspended']);
        $user = User::factory()->create([
            'role' => 'client',
            'client_id' => $client->id,
        ]);

        $response = $this->actingAs($user)->get('/client/dashboard');

        $response->assertStatus(302);
        // The middleware redirects to '/subscription/renewal'
        $response->assertRedirect(route('subscription.renewal'));
    }

    /** @test */
    public function active_client_user_can_access_dashboard()
    {
        $client = Client::create(['name' => 'Active Corp', 'status' => 'active']);
        $user = User::factory()->create([
            'role' => 'client',
            'client_id' => $client->id,
        ]);

        $response = $this->actingAs($user)->get('/client/dashboard');

        $response->assertStatus(200);
    }
}
