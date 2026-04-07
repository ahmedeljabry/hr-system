<?php

namespace Tests\Feature\Integration;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Client;
use App\Models\User;
use App\Models\Employee;
use App\Models\Task;
use App\Models\Asset;
use App\Models\Announcement;

class CompleteUserJourneyTest extends TestCase
{
    use RefreshDatabase;

    private $client;
    private $clientUser;
    private $employee;
    private $employeeUser;
    private $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Create super admin
        $this->admin = User::factory()->create(['role' => 'super_admin']);

        // Create client with active subscription
        $this->client = Client::factory()->create([
            'status' => 'active',
            'subscription_end' => now()->addDays(30)
        ]);

        // Create client user
        $this->clientUser = User::factory()->create([
            'role' => 'client',
            'client_id' => $this->client->id
        ]);

        // Create employee
        $this->employee = Employee::factory()->create([
            'client_id' => $this->client->id
        ]);

        // Create employee user
        $this->employeeUser = User::factory()->create([
            'role' => 'employee',
            'client_id' => $this->client->id
        ]);
        $this->employeeUser->employee()->save($this->employee);
    }

    public function test_super_admin_complete_journey()
    {
        // 1. Admin logs in and sees dashboard with stats
        $response = $this->actingAs($this->admin)->get('/admin/dashboard');
        $response->assertStatus(200);
        $response->assertSee(__('messages.super_admin_dashboard') ?: 'Super Admin Dashboard');

        // 2. Admin views clients list
        $response = $this->actingAs($this->admin)->get('/admin/clients');
        $response->assertStatus(200);
        // Table is dynamic, check JSON
        $response = $this->actingAs($this->admin)->getJson('/admin/clients');
        $response->assertJsonFragment(['name' => $this->client->name]);

        // 3. Admin views client details
        $response = $this->actingAs($this->admin)->get('/admin/clients/' . $this->client->id);
        $response->assertStatus(200);
        $response->assertSee($this->employee->name);

        // 4. Admin edits user
        $response = $this->actingAs($this->admin)->get('/admin/users/' . $this->clientUser->id . '/edit');
        $response->assertStatus(200);
        $response->assertSee(__('messages.edit_user') ?: 'Edit User'); 

        // 5. Admin updates user
        $response = $this->actingAs($this->admin)
            ->patch('/admin/users/' . $this->clientUser->id, [
                'name' => 'Updated Name',
                'email' => 'updated' . time() . '@example.com' // Ensure unique email
            ]);
        $response->assertRedirect();
        $response->assertSessionHas('success');
    }

    public function test_client_complete_journey()
    {
        // 1. Client logs in and sees dashboard
        $response = $this->actingAs($this->clientUser)->get('/client/dashboard');
        $response->assertStatus(200);
        
        // 2. Client views employees
        $response = $this->actingAs($this->clientUser)->get('/client/employees');
        $response->assertStatus(200);
        $response->assertSee($this->employee->name);

        // 3. Client creates a task
        $response = $this->actingAs($this->clientUser)
            ->post('/client/tasks', [
                'title' => 'Test Task',
                'description' => 'Test Description',
                'employee_id' => $this->employee->id,
                'status' => 'todo',
                'due_date' => now()->addDays(7)->format('Y-m-d')
            ]);
        $response->assertRedirect();
        $response->assertSessionHas('success');

        // 4. Client views tasks
        $response = $this->actingAs($this->clientUser)->get('/client/tasks');
        $response->assertStatus(200);
        $response->assertSee('Test Task');

        // 5. Client creates an announcement
        $response = $this->actingAs($this->clientUser)
            ->post('/client/announcements', [
                'title' => 'Test Announcement',
                'body' => 'Test announcement body'
            ]);
        $response->assertRedirect();
        $response->assertSessionHas('success');

        // 6. Client views announcements
        $response = $this->actingAs($this->clientUser)->get('/client/announcements');
        $response->assertStatus(200);
        $response->assertSee('Test Announcement');
    }

    public function test_employee_complete_journey()
    {
        // 1. Employee logs in and sees dashboard
        $response = $this->actingAs($this->employeeUser)->get('/employee/dashboard');
        $response->assertStatus(200);

        // 2. Employee views profile
        $response = $this->actingAs($this->employeeUser)->get('/employee/profile');
        $response->assertStatus(200);
        $response->assertSee($this->employee->name);

        // 3. Employee views tasks
        $response = $this->actingAs($this->employeeUser)->get('/employee/tasks');
        $response->assertStatus(200);

        // 4. Employee views assets
        $response = $this->actingAs($this->employeeUser)->get('/employee/assets');
        $response->assertStatus(200);

        // 5. Employee views payslips
        $response = $this->actingAs($this->employeeUser)->get('/employee/payslips');
        $response->assertStatus(200);

        // 6. Employee views announcements
        $response = $this->actingAs($this->employeeUser)->get('/employee/announcements');
        $response->assertStatus(200);
    }

    public function test_cross_tenant_data_isolation()
    {
        // Create second client and employee
        $client2 = Client::factory()->create([
            'status' => 'active',
            'subscription_end' => now()->addDays(30)
        ]);
        $employee2 = Employee::factory()->create(['client_id' => $client2->id]);

        // Create data for client 2
        Task::factory()->create([
            'client_id' => $client2->id,
            'employee_id' => $employee2->id,
            'title' => 'Client 2 Task'
        ]);

        Asset::factory()->create([
            'client_id' => $client2->id,
            'employee_id' => $employee2->id,
            'description' => 'Client 2 Asset'
        ]);

        Announcement::factory()->create([
            'client_id' => $client2->id,
            'title' => 'Client 2 Announcement'
        ]);

        // Client 1 should not see Client 2's data
        $response = $this->actingAs($this->clientUser)->get('/client/tasks');
        $response->assertDontSee('Client 2 Task');

        $response = $this->actingAs($this->clientUser)->get('/client/assets');
        $response->assertDontSee('Client 2 Asset');

        $response = $this->actingAs($this->clientUser)->get('/client/announcements');
        $response->assertDontSee('Client 2 Announcement');
    }

    public function test_file_upload_and_access()
    {
        // Test that file upload functionality exists (we can't easily test actual file uploads in this context)
        // But we can test that the routes exist and are accessible

        // Client should be able to access import page
        $response = $this->actingAs($this->clientUser)->get('/client/employees/import');
        $response->assertStatus(200);

        // Employee should be able to access profile (where documents would be viewed)
        $response = $this->actingAs($this->employeeUser)->get('/employee/profile');
        $response->assertStatus(200);
        $response->assertSee(__('messages.document_not_available'));
    }
}