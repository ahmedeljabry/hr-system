<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Client;
use App\Models\User;
use App\Models\Employee;

class ClientDetailTest extends TestCase
{
    use RefreshDatabase;

    private $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Create super admin user
        $this->admin = User::factory()->create(['role' => 'super_admin']);
    }

    public function test_client_detail_shows_employees_of_correct_client()
    {
        // Create two clients
        $clientA = Client::factory()->create(['name' => 'Client A']);
        $clientB = Client::factory()->create(['name' => 'Client B']);

        // Create employees for each client
        $employeeA1 = Employee::factory()->create(['client_id' => $clientA->id, 'name_ar' => 'Employee A1', 'name_en' => 'Employee A1']);
        $employeeA2 = Employee::factory()->create(['client_id' => $clientA->id, 'name_ar' => 'Employee A2', 'name_en' => 'Employee A2']);
        $employeeB1 = Employee::factory()->create(['client_id' => $clientB->id, 'name_ar' => 'Employee B1', 'name_en' => 'Employee B1']);

        // Act as admin and visit Client A detail page
        $response = $this->actingAs($this->admin)->get('/admin/clients/' . $clientA->id);

        // Assert response shows Client A employees but not Client B's
        $response->assertStatus(200);
        $response->assertSee('Client A');
        $response->assertSee('Employee A1');
        $response->assertSee('Employee A2');
        $response->assertDontSee('Employee B1');
    }
}