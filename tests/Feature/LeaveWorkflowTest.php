<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeaveWorkflowTest extends TestCase
{
    use RefreshDatabase;

    private Client $client;

    private User $clientUser;

    private User $employeeUser;

    private Employee $employee;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = Client::factory()->create([
            'status' => 'active',
            'subscription_end' => now()->addDays(30),
        ]);

        $this->clientUser = User::factory()->create([
            'role' => 'client',
            'client_id' => $this->client->id,
        ]);

        $this->employeeUser = User::factory()->create([
            'role' => 'employee',
            'client_id' => $this->client->id,
        ]);

        $this->employee = Employee::factory()->create([
            'client_id' => $this->client->id,
            'user_id' => $this->employeeUser->id,
        ]);
    }

    public function test_leave_approval_creates_employee_notification(): void
    {
        $leaveRequest = $this->createPendingLeave();

        $response = $this->actingAs($this->clientUser)->post(
            route('client.leaves.approve', [
                'client_slug' => $this->client->slug,
                'leaveRequest' => $leaveRequest,
            ])
        );

        $response->assertRedirect();

        $this->assertDatabaseHas('leave_requests', [
            'id' => $leaveRequest->id,
            'status' => 'approved',
        ]);

        $this->assertDatabaseHas('notifications', [
            'employee_id' => $this->employee->id,
            'type' => 'leave_request_approved',
            'related_id' => $leaveRequest->id,
        ]);
    }

    public function test_leave_rejection_creates_employee_notification(): void
    {
        $leaveRequest = $this->createPendingLeave();

        $response = $this->actingAs($this->clientUser)->post(
            route('client.leaves.reject', [
                'client_slug' => $this->client->slug,
                'leaveRequest' => $leaveRequest,
            ])
        );

        $response->assertRedirect();

        $this->assertDatabaseHas('leave_requests', [
            'id' => $leaveRequest->id,
            'status' => 'rejected',
        ]);

        $this->assertDatabaseHas('notifications', [
            'employee_id' => $this->employee->id,
            'type' => 'leave_request_rejected',
            'related_id' => $leaveRequest->id,
        ]);
    }

    public function test_dashboard_shows_leave_banner_until_employee_records_return(): void
    {
        Carbon::setTestNow(Carbon::create(2026, 4, 13, 9, 30, 0));

        $leaveRequest = $this->createApprovedLeave([
            'start_date' => now()->subDay()->toDateString(),
            'end_date' => now()->addDay()->toDateString(),
        ]);

        $dashboardResponse = $this->actingAs($this->employeeUser)->get(
            route('employee.dashboard', [
                'client_slug' => $this->client->slug,
                'employee_slug' => $this->employee->slug,
            ])
        );

        $dashboardResponse
            ->assertOk()
            ->assertSee(__('messages.currently_on_leave_banner_title'));

        $resumeResponse = $this->actingAs($this->employeeUser)->post(
            route('employee.leaves.resume', [
                'client_slug' => $this->client->slug,
                'employee_slug' => $this->employee->slug,
                'leaveRequest' => $leaveRequest,
            ])
        );

        $resumeResponse->assertRedirect();

        $leaveRequest->refresh();

        $this->assertTrue($leaveRequest->resumed_at->equalTo(now()));
        $this->assertTrue($leaveRequest->resumption_recorded_at->equalTo(now()));

        $this->assertDatabaseHas('notifications', [
            'client_id' => $this->client->id,
            'type' => 'leave_return_recorded',
            'related_id' => $leaveRequest->id,
        ]);

        $dashboardAfterResume = $this->actingAs($this->employeeUser)->get(
            route('employee.dashboard', [
                'client_slug' => $this->client->slug,
                'employee_slug' => $this->employee->slug,
            ])
        );

        $dashboardAfterResume
            ->assertOk()
            ->assertDontSee(__('messages.currently_on_leave_banner_title'));

        Carbon::setTestNow();
    }

    public function test_client_can_adjust_leave_return_timestamp(): void
    {
        Carbon::setTestNow(Carbon::create(2026, 4, 13, 13, 0, 0));

        $leaveRequest = $this->createApprovedLeave([
            'start_date' => now()->subDays(3)->toDateString(),
            'end_date' => now()->subDay()->toDateString(),
            'resumed_at' => now()->subHour(),
            'resumption_recorded_at' => now()->subHour(),
        ]);

        $adjustedReturnAt = now()->subDays(1)->setTime(8, 15);

        $response = $this->actingAs($this->clientUser)->put(
            route('client.leaves.resume', [
                'client_slug' => $this->client->slug,
                'leaveRequest' => $leaveRequest,
            ]),
            [
                'resumed_at' => $adjustedReturnAt->format('Y-m-d H:i:s'),
            ]
        );

        $response->assertRedirect();

        $leaveRequest->refresh();

        $this->assertTrue($leaveRequest->resumed_at->equalTo($adjustedReturnAt));
        $this->assertDatabaseHas('notifications', [
            'employee_id' => $this->employee->id,
            'type' => 'leave_return_updated',
            'related_id' => $leaveRequest->id,
        ]);

        Carbon::setTestNow();
    }

    private function createPendingLeave(array $overrides = []): LeaveRequest
    {
        $leaveType = $this->client->leaveTypes()->firstOrFail();

        return LeaveRequest::create(array_merge([
            'employee_id' => $this->employee->id,
            'leave_type_id' => $leaveType->id,
            'client_id' => $this->client->id,
            'start_date' => now()->addDay()->toDateString(),
            'end_date' => now()->addDays(2)->toDateString(),
            'status' => 'pending',
        ], $overrides));
    }

    private function createApprovedLeave(array $overrides = []): LeaveRequest
    {
        $leaveType = $this->client->leaveTypes()->firstOrFail();

        return LeaveRequest::create(array_merge([
            'employee_id' => $this->employee->id,
            'leave_type_id' => $leaveType->id,
            'client_id' => $this->client->id,
            'start_date' => now()->subDay()->toDateString(),
            'end_date' => now()->toDateString(),
            'status' => 'approved',
            'reviewed_at' => now()->subDays(2),
        ], $overrides));
    }
}
