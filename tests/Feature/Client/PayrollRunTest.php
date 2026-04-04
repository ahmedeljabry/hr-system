<?php

namespace Tests\Feature\Client;

use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use App\Models\Employee;
use App\Models\SalaryComponent;
use App\Models\PayrollRun;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PayrollRunTest extends TestCase
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

    public function test_client_can_view_run_payroll_form(): void
    {
        $response = $this->actingAs($this->clientUser)->get('/client/payroll/run');
        $response->assertStatus(200);
    }

    public function test_client_can_run_payroll(): void
    {
        $employee = Employee::factory()->create(['client_id' => $this->client->id, 'basic_salary' => 5000]);
        SalaryComponent::factory()->create(['employee_id' => $employee->id, 'type' => 'allowance', 'name' => 'Housing', 'amount' => 1500]);
        SalaryComponent::factory()->create(['employee_id' => $employee->id, 'type' => 'deduction', 'name' => 'Insurance', 'amount' => 300]);

        $month = now()->startOfMonth()->format('Y-m-d');
        $response = $this->actingAs($this->clientUser)->post('/client/payroll/run', ['month' => $month]);

        $response->assertRedirect();
        $this->assertDatabaseHas('payroll_runs', [
            'client_id' => $this->client->id,
            'status' => 'draft',
        ]);
        $run = \App\Models\PayrollRun::where('client_id', $this->client->id)->first();
        $this->assertEquals($month, $run->month->format('Y-m-d'));
        $this->assertDatabaseHas('payslips', [
            'employee_id' => $employee->id,
            'basic_salary' => 5000,
            'total_allowances' => 1500,
            'total_deductions' => 300,
            'net_salary' => 6200,
        ]);
        $this->assertDatabaseHas('payslip_line_items', [
            'component_name' => 'Housing',
            'type' => 'allowance',
            'amount' => 1500,
        ]);
    }

    public function test_client_can_confirm_draft_run(): void
    {
        $run = PayrollRun::factory()->create(['client_id' => $this->client->id, 'status' => 'draft']);

        $response = $this->actingAs($this->clientUser)
            ->post("/client/payroll/{$run->id}/confirm");

        $response->assertRedirect();
        $this->assertDatabaseHas('payroll_runs', [
            'id' => $run->id,
            'status' => 'confirmed',
        ]);
    }

    public function test_duplicate_confirmed_run_prevented(): void
    {
        $month = now()->startOfMonth()->format('Y-m-d');
        PayrollRun::factory()->confirmed()->create([
            'client_id' => $this->client->id,
            'month' => $month,
        ]);
        Employee::factory()->create(['client_id' => $this->client->id]);

        $response = $this->actingAs($this->clientUser)->post('/client/payroll/run', ['month' => $month]);
        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_future_month_prevented(): void
    {
        Employee::factory()->create(['client_id' => $this->client->id]);
        $futureMonth = now()->addMonths(2)->startOfMonth()->format('Y-m-d');

        $response = $this->actingAs($this->clientUser)->post('/client/payroll/run', ['month' => $futureMonth]);
        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_client_cannot_access_other_tenant_payroll(): void
    {
        $otherClient = Client::factory()->create(['status' => 'active']);
        $run = PayrollRun::factory()->create(['client_id' => $otherClient->id]);

        $response = $this->actingAs($this->clientUser)->get("/client/payroll/{$run->id}");
        $response->assertStatus(404);
    }
}
