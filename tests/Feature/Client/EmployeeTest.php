<?php

namespace Tests\Feature\Client;

use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class EmployeeTest extends TestCase
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

    public function test_client_can_view_employees_index(): void
    {
        Employee::factory()->count(3)->create(['client_id' => $this->client->id]);

        $response = $this->actingAs($this->clientUser)->get('/client/employees');
        $response->assertStatus(200);
        $response->assertViewIs('client.employees.index');
    }

    public function test_client_can_create_employee(): void
    {
        Storage::fake('private');

        $response = $this->actingAs($this->clientUser)->post('/client/employees', [
            'name_ar' => 'أحمد علي',
            'name_en' => 'Ahmed Ali',
            'position' => 'Developer',
            'national_id_number' => 'NID12345',
            'basic_salary' => 5000.00,
            'hire_date' => '2026-01-15',
            'gender' => 'male',
            'email' => 'ahmed@example.com',
            'password' => 'password123',
            'annual_leave_days' => 30,
            'national_id_image' => UploadedFile::fake()->image('id.jpg'),
        ]);

        $response->assertRedirect('/client/employees');
        $this->assertDatabaseHas('employees', [
            'name_ar' => 'أحمد علي',
            'name_en' => 'Ahmed Ali',
            'client_id' => $this->client->id,
            'national_id_number' => 'NID12345',
        ]);
    }

    public function test_client_can_update_employee(): void
    {
        $employee = Employee::factory()->create(['client_id' => $this->client->id]);

        $response = $this->actingAs($this->clientUser)->put("/client/employees/{$employee->id}", [
            'name_ar' => 'Updated Name Ar',
            'name_en' => 'Updated Name En',
            'position' => $employee->position,
            'national_id_number' => $employee->national_id_number,
            'basic_salary' => 6000,
            'hire_date' => $employee->hire_date->format('Y-m-d'),
            'gender' => $employee->gender,
            'email' => $employee->email,
            'annual_leave_days' => $employee->annual_leave_days,
        ]);

        $response->assertRedirect('/client/employees');
        $this->assertDatabaseHas('employees', [
            'id' => $employee->id,
            'name_ar' => 'Updated Name Ar',
            'name_en' => 'Updated Name En'
        ]);
    }

    public function test_client_can_delete_employee(): void
    {
        $employee = Employee::factory()->create(['client_id' => $this->client->id]);

        $response = $this->actingAs($this->clientUser)->delete("/client/employees/{$employee->id}");

        $response->assertRedirect('/client/employees');
        $this->assertDatabaseMissing('employees', ['id' => $employee->id]);
    }

    public function test_client_cannot_access_other_tenants_employees(): void
    {
        $otherClient = Client::factory()->create(['status' => 'active']);
        $otherEmployee = Employee::factory()->create(['client_id' => $otherClient->id]);

        $response = $this->actingAs($this->clientUser)->get("/client/employees/{$otherEmployee->id}");
        $response->assertStatus(404);
    }

    public function test_duplicate_national_id_within_same_client_rejected(): void
    {
        Employee::factory()->create([
            'client_id' => $this->client->id,
            'national_id_number' => 'DUP123',
        ]);

        $response = $this->actingAs($this->clientUser)->post('/client/employees', [
            'name_ar' => 'Duplicate',
            'name_en' => 'Duplicate',
            'position' => 'Tester',
            'national_id_number' => 'DUP123',
            'basic_salary' => 3000,
            'hire_date' => '2026-01-01',
            'gender' => 'male',
            'email' => 'dup@example.com',
            'password' => 'password123',
            'annual_leave_days' => 30,
        ]);

        $response->assertSessionHasErrors('national_id_number');
    }

    public function test_employee_requires_mandatory_fields(): void
    {
        $response = $this->actingAs($this->clientUser)->post('/client/employees', []);
        $response->assertSessionHasErrors(['name_ar', 'name_en', 'position', 'national_id_number', 'basic_salary', 'hire_date', 'gender', 'email', 'password', 'annual_leave_days']);
    }

    public function test_client_can_view_employee_details(): void
    {
        $employee = Employee::factory()->create(['client_id' => $this->client->id]);

        $response = $this->actingAs($this->clientUser)->get("/client/employees/{$employee->id}");
        $response->assertStatus(200);
        $response->assertViewIs('client.employees.show');
        $response->assertSee($employee->name); // Uses the name accessor
    }

    public function test_client_can_download_employee_file(): void
    {
        Storage::fake('private');
        $file = UploadedFile::fake()->create('contract.pdf', 100, 'application/pdf');
        
        $employee = Employee::factory()->create([
            'client_id' => $this->client->id,
            'contract_image' => $file->store('contracts', 'private')
        ]);

        $response = $this->actingAs($this->clientUser)->get("/client/files/employees/{$employee->id}/contract");
        $response->assertStatus(200);
    }

    public function test_client_cannot_download_other_tenant_file(): void
    {
        Storage::fake('private');
        $file = UploadedFile::fake()->create('contract.pdf', 100, 'application/pdf');

        $otherClient = Client::factory()->create(['status' => 'active']);
        $otherEmployee = Employee::factory()->create([
            'client_id' => $otherClient->id,
            'contract_image' => $file->store('contracts', 'private')
        ]);

        $response = $this->actingAs($this->clientUser)->get("/client/files/employees/{$otherEmployee->id}/contract");
        $response->assertStatus(404);
    }
}
