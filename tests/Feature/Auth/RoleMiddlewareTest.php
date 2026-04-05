<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Client;

class RoleMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function client_cannot_access_admin_routes()
    {
        $user = User::factory()->create(['role' => 'client']);
        
        $response = $this->actingAs($user)->get('/admin/dashboard');

        $response->assertStatus(403);
    }

    /** @test */
    public function employee_cannot_access_client_dashboard()
    {
        $user = User::factory()->create(['role' => 'employee']);
        
        $response = $this->actingAs($user)->get('/client/dashboard');

        $response->assertStatus(403);
    }

    /** @test */
    public function super_admin_can_access_all_dashboards()
    {
        $user = User::factory()->create(['role' => 'super_admin']);
        
        $this->actingAs($user)->get('/admin/dashboard')->assertStatus(200);
        $resp = $this->actingAs($user)->get('/client/dashboard');
        file_put_contents('debug_test.txt', $resp->content());
        $resp->assertRedirect('/admin/dashboard');
        $this->actingAs($user)->get('/employee/dashboard')->assertRedirect('/admin/dashboard');
    }

    /** @test */
    public function unauthenticated_users_are_redirected_to_login()
    {
        $this->get('/admin/dashboard')->assertRedirect('/login');
        $this->get('/client/dashboard')->assertRedirect('/login');
        $this->get('/employee/dashboard')->assertRedirect('/login');
    }
}
