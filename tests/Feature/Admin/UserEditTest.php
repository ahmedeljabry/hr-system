<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class UserEditTest extends TestCase
{
    use RefreshDatabase;

    private $admin;
    private $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create super admin and regular user
        $this->admin = User::factory()->create(['role' => 'super_admin']);
        $this->user = User::factory()->create(['role' => 'client', 'name' => 'John Doe', 'email' => 'john@example.com']);
    }

    public function test_super_admin_can_edit_user_name_and_email()
    {
        // Act as admin and update user
        $response = $this->actingAs($this->admin)
            ->patch('/admin/users/' . $this->user->id, [
                'name' => 'Jane Smith',
                'email' => 'jane@example.com'
            ]);

        // Assert redirect with success message
        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Assert user was updated
        $this->user->refresh();
        $this->assertEquals('Jane Smith', $this->user->name);
        $this->assertEquals('jane@example.com', $this->user->email);
    }

    public function test_email_validation_prevents_duplicates()
    {
        // Create another user with different email
        $anotherUser = User::factory()->create(['email' => 'existing@example.com']);

        // Try to update our test user with the existing email
        $response = $this->actingAs($this->admin)
            ->patch('/admin/users/' . $this->user->id, [
                'name' => 'Jane Smith',
                'email' => 'existing@example.com' // Duplicate email
            ]);

        // Assert validation error
        $response->assertRedirect();
        $response->assertSessionHasErrors('email');

        // Assert user was not updated
        $this->user->refresh();
        $this->assertEquals('John Doe', $this->user->name);
        $this->assertEquals('john@example.com', $this->user->email);
    }
}