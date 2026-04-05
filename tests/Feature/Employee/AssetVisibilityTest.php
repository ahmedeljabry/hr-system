<?php

namespace Tests\Feature\Employee;

use App\Models\Asset;
use App\Models\Client;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssetVisibilityTest extends TestCase
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

    public function test_employee_can_view_assigned_assets(): void
    {
        $asset = Asset::factory()->create([
            'client_id' => $this->employee->client_id,
            'employee_id' => $this->employee->id,
            'type' => 'Work Laptop',
            'serial_number' => 'L-888'
        ]);

        $response = $this->actingAs($this->employeeUser)->get('/employee/assets');
        $response->assertStatus(200);
        $response->assertSee('Work Laptop');
        $response->assertSee('L-888');
    }

    public function test_employee_cannot_view_others_assets(): void
    {
        $otherEmployee = Employee::factory()->create(['client_id' => $this->employee->client_id]);
        $otherAsset = Asset::factory()->create([
            'client_id' => $this->employee->client_id,
            'employee_id' => $otherEmployee->id,
            'type' => 'Other Laptop',
            'serial_number' => 'X-999'
        ]);

        $response = $this->actingAs($this->employeeUser)->get('/employee/assets');
        $response->assertDontSee('Other Laptop');
        $response->assertDontSee('X-999');
    }
}
