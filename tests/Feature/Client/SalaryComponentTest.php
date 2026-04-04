<?php

namespace Tests\Feature\Client;

use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use App\Models\Employee;
use App\Models\SalaryComponent;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SalaryComponentTest extends TestCase
{
    use RefreshDatabase;

    private User $clientUser;
    private Client $client;
    private Employee $employee;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = Client::factory()->create(['status' => 'active']);
        $this->clientUser = User::factory()->create([
            'role' => 'client',
            'client_id' => $this->client->id,
        ]);
        $this->employee = Employee::factory()->create(['client_id' => $this->client->id]);
    }

    public function test_client_can_view_salary_components(): void
    {
        SalaryComponent::factory()->count(3)->create(['employee_id' => $this->employee->id]);

        $response = $this->actingAs($this->clientUser)
            ->get("/client/employees/{$this->employee->id}/salary-components");
        $response->assertStatus(200);
    }

    public function test_client_can_add_salary_component(): void
    {
        $response = $this->actingAs($this->clientUser)
            ->post("/client/employees/{$this->employee->id}/salary-components", [
                'type' => 'allowance',
                'name' => 'Housing',
                'amount' => 1500,
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('salary_components', [
            'employee_id' => $this->employee->id,
            'type' => 'allowance',
            'name' => 'Housing',
            'amount' => 1500,
        ]);
    }

    public function test_client_can_update_salary_component(): void
    {
        $component = SalaryComponent::factory()->create([
            'employee_id' => $this->employee->id,
            'amount' => 1500,
        ]);

        $response = $this->actingAs($this->clientUser)
            ->put("/client/employees/{$this->employee->id}/salary-components/{$component->id}", [
                'type' => $component->type,
                'name' => $component->name,
                'amount' => 2000,
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('salary_components', [
            'id' => $component->id,
            'amount' => 2000,
        ]);
    }

    public function test_client_can_delete_salary_component(): void
    {
        $component = SalaryComponent::factory()->create([
            'employee_id' => $this->employee->id,
        ]);

        $response = $this->actingAs($this->clientUser)
            ->delete("/client/employees/{$this->employee->id}/salary-components/{$component->id}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('salary_components', ['id' => $component->id]);
    }

    public function test_client_cannot_access_other_tenant_components(): void
    {
        $otherClient = Client::factory()->create(['status' => 'active']);
        $otherEmployee = Employee::factory()->create(['client_id' => $otherClient->id]);

        $response = $this->actingAs($this->clientUser)
            ->get("/client/employees/{$otherEmployee->id}/salary-components");
        $response->assertStatus(404);
    }

    public function test_salary_component_requires_valid_data(): void
    {
        $response = $this->actingAs($this->clientUser)
            ->post("/client/employees/{$this->employee->id}/salary-components", []);
        $response->assertSessionHasErrors(['type', 'name', 'amount']);
    }
}
