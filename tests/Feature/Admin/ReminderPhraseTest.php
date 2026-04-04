<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Enums\NotificationEvent;

class ReminderPhraseTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_reminder_phrases()
    {
        $admin = User::factory()->create(['role' => 'super_admin']);
        
        $response = $this->actingAs($admin)->get('/admin/reminder-phrases');
        $response->assertStatus(200);
    }
}
