<?php

namespace Tests\Feature\Employee;

use App\Models\Client;
use App\Models\Employee;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskVisibilityTest extends TestCase
{
    use RefreshDatabase;

    private User $employeeUser;
    private Employee $employee;

    protected function setUp(): void
    {
        parent::setUp();
        $client = Client::factory()->create(['status' => 'active']);
        $this->employee = Employee::factory()->create(['client_id' => $client->id]);
        $this->employeeUser = User::factory()->create([
            'role' => 'employee',
            'client_id' => $client->id,
        ]);
        $this->employee->update(['user_id' => $this->employeeUser->id]);
    }

    public function test_employee_can_view_assigned_tasks(): void
    {
        $task = Task::factory()->create([
            'client_id' => $this->employee->client_id,
            'employee_id' => $this->employee->id,
            'title' => 'My Assigned Task'
        ]);

        $response = $this->actingAs($this->employeeUser)->get('/employee/tasks');
        $response->assertStatus(200);
        $response->assertSee('My Assigned Task');
    }

    public function test_employee_cannot_view_others_tasks(): void
    {
        $otherEmployee = Employee::factory()->create(['client_id' => $this->employee->client_id]);
        $otherTask = Task::factory()->create([
            'client_id' => $this->employee->client_id,
            'employee_id' => $otherEmployee->id,
            'title' => 'Not My Task'
        ]);

        $response = $this->actingAs($this->employeeUser)->get('/employee/tasks');
        $response->assertDontSee('Not My Task');
    }
}
