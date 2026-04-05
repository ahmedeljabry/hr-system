<?php

namespace Tests\Feature\Client;

use App\Models\Client;
use App\Models\Employee;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    private User $clientUser;
    private Client $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = Client::factory()->create(['status' => 'active']);
        $this->clientUser = User::factory()->create([
            'role' => 'client',
            'client_id' => $this->client->id,
        ]);
    }

    public function test_client_can_create_task(): void
    {
        $employee = Employee::factory()->create(['client_id' => $this->client->id]);

        $response = $this->actingAs($this->clientUser)->post('/client/tasks', [
            'title' => 'Sample Task',
            'employee_id' => $employee->id,
            'status' => 'todo',
            'due_date' => now()->addWeek()->format('Y-m-d'),
        ]);

        $response->assertRedirect('/client/tasks');
        $this->assertDatabaseHas('tasks', [
            'title' => 'Sample Task',
            'client_id' => $this->client->id,
            'employee_id' => $employee->id,
        ]);
    }

    public function test_tasks_are_scoped_to_client(): void
    {
        $otherClient = Client::factory()->create(['status' => 'active']);
        $otherTask = Task::factory()->create(['client_id' => $otherClient->id]);

        // Client should not see other tenant's tasks
        $response = $this->actingAs($this->clientUser)->get('/client/tasks');
        $response->assertDontSee($otherTask->title);
    }
}
