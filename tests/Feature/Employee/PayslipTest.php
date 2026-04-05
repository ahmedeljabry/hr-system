<?php

namespace Tests\Feature\Employee;

use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use App\Models\Employee;
use App\Models\PayrollRun;
use App\Models\Payslip;
use App\Models\PayslipLineItem;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PayslipTest extends TestCase
{
    use RefreshDatabase;

    private User $employeeUser;
    private Employee $employee;
    private Client $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = Client::factory()->create(['status' => 'active', 'subscription_end' => now()->addDays(30)]);
        $this->employeeUser = User::factory()->create(['role' => 'employee', 'client_id' => $this->client->id]);
        $this->employee = Employee::factory()->create([
            'client_id' => $this->client->id,
            'user_id' => $this->employeeUser->id,
        ]);
    }

    public function test_employee_can_view_payslip_list(): void
    {
        $run = PayrollRun::factory()->confirmed()->create(['client_id' => $this->client->id]);
        Payslip::factory()->create([
            'payroll_run_id' => $run->id,
            'employee_id' => $this->employee->id,
        ]);

        $response = $this->actingAs($this->employeeUser)->get('/employee/payslips');
        $response->assertStatus(200);
    }

    public function test_employee_can_view_own_payslip_detail(): void
    {
        $run = PayrollRun::factory()->confirmed()->create(['client_id' => $this->client->id]);
        $payslip = Payslip::factory()->create([
            'payroll_run_id' => $run->id,
            'employee_id' => $this->employee->id,
        ]);
        PayslipLineItem::create([
            'payslip_id' => $payslip->id,
            'component_name' => 'Housing',
            'type' => 'allowance',
            'amount' => 1500,
        ]);

        $response = $this->actingAs($this->employeeUser)->get("/employee/payslips/{$payslip->id}");
        $response->assertStatus(200);
        $response->assertSee('Housing');
        $response->assertSee('1,500.00');
    }

    public function test_employee_cannot_view_other_payslip(): void
    {
        $otherEmployee = Employee::factory()->create(['client_id' => $this->client->id]);
        $run = PayrollRun::factory()->confirmed()->create(['client_id' => $this->client->id]);
        $payslip = Payslip::factory()->create([
            'payroll_run_id' => $run->id,
            'employee_id' => $otherEmployee->id,
        ]);

        $response = $this->actingAs($this->employeeUser)->get("/employee/payslips/{$payslip->id}");
        $response->assertStatus(404);
    }
}
