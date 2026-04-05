<?php

namespace Tests\Feature\Client;

use App\Models\Asset;
use App\Models\Client;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssetTest extends TestCase
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

    public function test_client_can_create_asset(): void
    {
        $employee = Employee::factory()->create(['client_id' => $this->client->id]);

        $response = $this->actingAs($this->clientUser)->post('/client/assets', [
            'type' => 'Laptop',
            'serial_number' => 'ABC-123',
            'employee_id' => $employee->id,
            'assigned_date' => now()->format('Y-m-d'),
        ]);

        $response->assertRedirect('/client/assets');
        $this->assertDatabaseHas('assets', [
            'type' => 'Laptop',
            'serial_number' => 'ABC-123',
            'client_id' => $this->client->id,
            'employee_id' => $employee->id,
        ]);
    }

    public function test_serial_number_unique_within_client(): void
    {
        Asset::factory()->create([
            'client_id' => $this->client->id,
            'serial_number' => 'DUP-001',
        ]);

        $response = $this->actingAs($this->clientUser)->post('/client/assets', [
            'type' => 'Phone',
            'serial_number' => 'DUP-001',
            'assigned_date' => now()->format('Y-m-d'),
        ]);

        $response->assertSessionHasErrors('serial_number');
    }

    public function test_assets_are_scoped_to_client(): void
    {
        $otherClient = Client::factory()->create(['status' => 'active']);
        $otherAsset = Asset::factory()->create(['client_id' => $otherClient->id, 'serial_number' => 'OTHER-001']);

        $response = $this->actingAs($this->clientUser)->get('/client/assets');
        $response->assertDontSee('OTHER-001');
    }
}
