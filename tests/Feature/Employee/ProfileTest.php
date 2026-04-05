<?php

namespace Tests\Feature\Employee;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Client;
use App\Models\Employee;
use App\Models\User;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $employee;
    private $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = Client::factory()->create(['status' => 'active', 'subscription_end' => now()->addDays(30)]);
        
        $this->user = User::factory()->create([
            'role' => 'employee',
            'client_id' => $this->client->id
        ]);

        $this->employee = Employee::factory()->create([
            'client_id' => $this->client->id,
            'user_id' => $this->user->id,
            'position' => 'Software Engineer',
            'hire_date' => '2023-01-01',
            'basic_salary' => 5000,
        ]);
    }

    public function test_employee_can_view_own_profile()
    {
        $response = $this->actingAs($this->user)->get('/employee/profile');

        $response->assertStatus(200);
        $response->assertSee($this->employee->name);
        $response->assertSee('Software Engineer');
        $response->assertSee('2023-01-01');
        $response->assertSee('5,000.00'); // the format number_format(basic_salary, 2)
    }

    public function test_profile_shows_document_not_available_when_no_files()
    {
        $this->employee->update([
            'national_id_image' => null,
            'contract_image' => null,
        ]);

        $response = $this->actingAs($this->user)->get('/employee/profile');

        $response->assertStatus(200);
        $response->assertSee(__('Document not available'));
    }
}
