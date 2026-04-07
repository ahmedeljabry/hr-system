<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Client;
use App\Models\User;
use App\Models\Employee;

class ClientListTest extends TestCase
{
    use RefreshDatabase;

    private $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Create super admin user
        $this->admin = User::factory()->create(['role' => 'super_admin']);
    }

    public function test_client_list_shows_paginated_clients()
    {
        // Create 20 clients
        Client::factory()->count(20)->create(['status' => 'active']);

        // Act as admin and visit clients list
        $response = $this->actingAs($this->admin)->get('/admin/clients');

        // Assert response
        $response->assertStatus(200);
        $response->assertSee(__('messages.clients')); // Clients in Arabic
        $response->assertSee('pagination'); // Should have pagination links
    }

    public function test_client_list_includes_employee_count()
    {
        // Create a client with 3 employees
        $client = Client::factory()->create(['status' => 'active']);
        Employee::factory()->count(3)->create(['client_id' => $client->id]);

        // Act as admin
        $response = $this->actingAs($this->admin)->get('/admin/clients');

        // Assert employee count is shown
        $response->assertStatus(200);
        $response->assertSee('3'); // Employee count
    }

    public function test_status_dropdown_updates_immediately()
    {
        // Create a client
        $client = Client::factory()->create(['status' => 'active']);

        // Act as admin and update status via PATCH
        $response = $this->actingAs($this->admin)
            ->patch('/admin/clients/' . $client->id . '/status', [
                'status' => 'suspended'
            ]);

        // Assert redirect back with success message
        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Assert status was updated in database
        $client->refresh();
        $this->assertEquals('suspended', $client->status);
    }
}