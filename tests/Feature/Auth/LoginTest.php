<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use Illuminate\Support\Facades\RateLimiter;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function client_can_login_and_redirect_to_dashboard()
    {
        $client = Client::create(['name' => 'Test Corp', 'status' => 'active']);
        $user = User::factory()->create([
            'email' => 'client@example.com',
            'password' => bcrypt('password123'),
            'role' => 'client',
            'client_id' => $client->id,
        ]);

        $response = $this->post('/login', [
            'email' => 'client@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/client/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function admin_can_login_and_redirect_to_admin_dashboard()
    {
        $user = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password123'),
            'role' => 'super_admin',
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/admin/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function login_fails_with_invalid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => 'user@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /** @test */
    public function login_is_throttled_after_too_many_attempts()
    {
        $email = 'throttle@example.com';
        
        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', ['email' => $email, 'password' => 'wrong']);
        }

        $response = $this->post('/login', ['email' => $email, 'password' => 'wrong']);
        
        $response->assertStatus(302);
        $response->assertSessionHasErrors('email');
        // Check for throttle message part (varies by translation but standard contains "seconds" or "ثانية")
        $this->assertTrue(collect(session('errors')->get('email'))->contains(function ($val) {
            return str_contains($val, 'seconds') || str_contains($val, 'ثانية') || str_contains($val, 'محاولات');
        }));
    }
}
