<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class RegisterTest extends TestCase
{
    use RefreshDatabase, WithoutMiddleware;

    public function test_user_can_register()
    {
        $response = $this->post(route('register.perform'), [
            'username' => 'testuser',
            'email' => 'test@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/');
        $this->assertDatabaseHas('users', [
            'username' => 'testuser',
            'email' => 'test@test.com',
        ]);
        $this->assertAuthenticated();

        // Additional assertEquals assertions
        $user = User::where('username', 'testuser')->first();
        $this->assertEquals('testuser', $user->username);
        $this->assertEquals('test@test.com', $user->email);
        $this->assertTrue(\Hash::check('password123', $user->password));
        $this->assertEquals(1, User::count());

        // Check if user is authenticated
        $this->assertEquals($user->id, auth()->id());
    }
}
