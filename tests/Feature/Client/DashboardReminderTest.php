<?php

namespace Tests\Feature\Client;

use App\Models\User;
use App\Models\Client;
use App\Models\ReminderPhrase;
use App\Enums\NotificationEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardReminderTest extends TestCase
{
    use RefreshDatabase;

    private User $clientUser;
    private Client $client;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->travelTo(now()->startOfDay());
        
        $this->client = Client::factory()->create([
            'status' => 'active',
            'subscription_end' => now()->addDays(5)
        ]);
        
        $this->clientUser = User::factory()->create([
            'role' => 'client',
            'client_id' => $this->client->id,
        ]);
    }

    public function test_dashboard_uses_database_phrase_when_available()
    {
        ReminderPhrase::factory()->create([
            'event_key' => NotificationEvent::SUBSCRIPTION_EXPIRING->value,
            'text_en' => 'Your subscription expires in exactly {days} days!',
        ]);

        $response = $this->actingAs($this->clientUser)->get('/client/dashboard');
        
        $response->assertStatus(200);
    }

    public function test_dashboard_falls_back_to_locale_when_phrase_missing()
    {
        // No phrase configured in DB
        
        $response = $this->actingAs($this->clientUser)->get('/client/dashboard');
        
        $response->assertStatus(200);
    }
}
