<?php

namespace Tests\Feature\Employee;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Client;
use App\Models\Employee;
use App\Models\User;
use App\Models\Announcement;

class AnnouncementVisibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_employee_sees_own_client_announcements()
    {
        $clientA = Client::factory()->create(['status' => 'active', 'subscription_end' => now()->addDays(30)]);
        $userA = User::factory()->create(['role' => 'employee', 'client_id' => $clientA->id]);
        Employee::factory()->create(['client_id' => $clientA->id, 'user_id' => $userA->id]);
        
        $announcement = Announcement::factory()->create(['client_id' => $clientA->id, 'title' => 'Client A Note']);

        $response = $this->actingAs($userA)->get('/employee/announcements');

        $response->assertStatus(200);
        $response->assertSee('Client A Note');
    }

    public function test_employee_cannot_see_other_client_announcements()
    {
        $clientA = Client::factory()->create(['status' => 'active', 'subscription_end' => now()->addDays(30)]);
        Announcement::factory()->create(['client_id' => $clientA->id, 'title' => 'Client A Note']);

        $clientB = Client::factory()->create(['status' => 'active', 'subscription_end' => now()->addDays(30)]);
        $userB = User::factory()->create(['role' => 'employee', 'client_id' => $clientB->id]);
        Employee::factory()->create(['client_id' => $clientB->id, 'user_id' => $userB->id]);
        
        $response = $this->actingAs($userB)->get('/employee/announcements');

        $response->assertStatus(200);
        $response->assertDontSee('Client A Note');
    }
}
