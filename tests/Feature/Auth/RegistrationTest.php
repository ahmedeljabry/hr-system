<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Client;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_client_can_register_with_valid_data()
    {
        $response = $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'company_name' => 'Acme Corp',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/client/dashboard');

        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
            'role' => 'client',
        ]);

        $user = User::where('email', 'john@example.com')->first();
        $this->assertNotNull($user->client_id);

        $this->assertDatabaseHas('clients', [
            'id' => $user->client_id,
            'name' => 'Acme Corp',
            'status' => 'active',
        ]);
        
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function registration_fails_with_duplicate_email()
    {
        User::factory()->create([
            'email' => 'duplicate@example.com',
        ]);

        $response = $this->from('/register')->post('/register', [
            'name' => 'Other User',
            'email' => 'duplicate@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'company_name' => 'Other Corp',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/register');
        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function registration_fails_with_validation_errors()
    {
        // 1. Empty fields
        $response = $this->from('/register')->post('/register', []);
        $response->assertSessionHasErrors(['name', 'email', 'password', 'company_name']);

        // 2. Short password
        $response = $this->from('/register')->post('/register', [
            'name' => 'Short Pass',
            'email' => 'short@example.com',
            'password' => 'short',
            'password_confirmation' => 'short',
            'company_name' => 'Short Corp',
        ]);
        $response->assertSessionHasErrors('password');

        // 3. Mismatched password confirmation
        $response = $this->from('/register')->post('/register', [
            'name' => 'Mismatched',
            'email' => 'mismatch@example.com',
            'password' => 'password123',
            'password_confirmation' => 'other_password',
            'company_name' => 'Mismatch Corp',
        ]);
        $response->assertSessionHasErrors('password');
    }
}
