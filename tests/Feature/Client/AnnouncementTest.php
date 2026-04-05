<?php

namespace Tests\Feature\Client;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Client;
use App\Models\User;
use App\Models\Announcement;

class AnnouncementTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = Client::factory()->create(['status' => 'active', 'subscription_end' => now()->addDays(30)]);
        $this->user = User::factory()->create(['role' => 'client', 'client_id' => $this->client->id]);
    }

    public function test_client_can_create_announcement()
    {
        $response = $this->actingAs($this->user)->post('/client/announcements', [
            'title' => 'New Policy',
            'body' => 'Please read the new policy.'
        ]);

        $response->assertRedirect(route('client.announcements.index'));
        $this->assertDatabaseHas('announcements', [
            'client_id' => $this->client->id,
            'title' => 'New Policy',
            'body' => 'Please read the new policy.'
        ]);
    }

    public function test_client_can_view_announcements_index()
    {
        Announcement::factory()->count(2)->create(['client_id' => $this->client->id]);

        $response = $this->actingAs($this->user)->get('/client/announcements');

        $response->assertStatus(200);
        $this->assertCount(2, $response->viewData('announcements'));
    }

    public function test_client_can_delete_announcement()
    {
        $announcement = Announcement::factory()->create(['client_id' => $this->client->id]);

        $response = $this->actingAs($this->user)->delete('/client/announcements/' . $announcement->id);

        $response->assertRedirect(route('client.announcements.index'));
        $this->assertDatabaseMissing('announcements', ['id' => $announcement->id]);
    }
}
