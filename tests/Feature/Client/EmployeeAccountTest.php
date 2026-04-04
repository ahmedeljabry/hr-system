<?php

namespace Tests\Feature\Client;

use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EmployeeAccountTest extends TestCase
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

    public function test_client_can_create_employee_account(): void
    {
        $employee = Employee::factory()->create(['client_id' => $this->client->id, 'user_id' => null]);

        $response = $this->actingAs($this->clientUser)
            ->post("/client/employees/{$employee->id}/create-account", [
                'email' => 'emp@test.com',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('users', [
            'email' => 'emp@test.com',
            'role' => 'employee',
        ]);
        $this->assertNotNull($employee->fresh()->user_id);
    }

    public function test_cannot_create_duplicate_account(): void
    {
        $user = User::factory()->create(['role' => 'employee']);
        $employee = Employee::factory()->create([
            'client_id' => $this->client->id,
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($this->clientUser)
            ->post("/client/employees/{$employee->id}/create-account", [
                'email' => 'new@test.com',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }
}
