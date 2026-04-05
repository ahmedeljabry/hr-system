<?php

namespace Tests\Feature\Client;

use App\Models\Attendance;
use App\Models\Client;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendanceTest extends TestCase
{
    use RefreshDatabase;

    private User $clientUser;
    private Client $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = Client::factory()->create(['status' => 'active', 'subscription_end' => now()->addDays(30)]);
        $this->clientUser = User::factory()->create([
            'role' => 'client',
            'client_id' => $this->client->id,
        ]);
    }

    public function test_client_can_view_attendance_index(): void
    {
        Employee::factory()->count(3)->create(['client_id' => $this->client->id]);

        $response = $this->actingAs($this->clientUser)->get('/client/attendance');
        $response->assertStatus(200);
        $response->assertViewIs('client.attendance.index');
    }

    public function test_client_can_bulk_record_attendance(): void
    {
        $this->withoutExceptionHandling();
        $employees = Employee::factory()->count(2)->create(['client_id' => $this->client->id]);
        $date = now()->subDay()->format('Y-m-d');

        $response = $this->actingAs($this->clientUser)->post('/client/attendance', [
            'date' => $date,
            'attendance' => [
                $employees[0]->id => ['status' => 'present', 'notes' => 'On time'],
                $employees[1]->id => ['status' => 'late', 'notes' => 'Traffic'],
            ],
        ]);

        $response->assertRedirect(); // Should redirect back or to index

        $this->assertDatabaseHas('attendance', [
            'client_id' => $this->client->id,
            'employee_id' => $employees[0]->id,
            'date' => $date . ' 00:00:00',
            'status' => 'present',
            'notes' => 'On time',
        ]);

        $this->assertDatabaseHas('attendance', [
            'client_id' => $this->client->id,
            'employee_id' => $employees[1]->id,
            'date' => $date . ' 00:00:00',
            'status' => 'late',
            'notes' => 'Traffic',
        ]);
    }

    public function test_client_cannot_record_future_attendance(): void
    {
        $employee = Employee::factory()->create(['client_id' => $this->client->id]);
        $futureDate = now()->addDay()->format('Y-m-d');

        $response = $this->actingAs($this->clientUser)->post('/client/attendance', [
            'date' => $futureDate,
            'attendance' => [
                $employee->id => ['status' => 'present'],
            ],
        ]);

        $response->assertSessionHasErrors('date');
    }

    public function test_attendance_is_scoped_to_client(): void
    {
        $otherClient = Client::factory()->create(['status' => 'active']);
        $otherEmployee = Employee::factory()->create(['client_id' => $otherClient->id]);
        $date = now()->format('Y-m-d');

        // Verify that client cannot record attendance for other client's employee
        $response = $this->actingAs($this->clientUser)->post('/client/attendance', [
            'date' => $date,
            'attendance' => [
                $otherEmployee->id => ['status' => 'present'],
            ],
        ]);

        $this->assertDatabaseMissing('attendance', [
            'employee_id' => $otherEmployee->id,
            'date' => $date,
        ]);
    }
}
