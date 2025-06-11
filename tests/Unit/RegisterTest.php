<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class RegisterTest extends TestCase
{
    use RefreshDatabase, WithoutMiddleware;

    public function test_user_can_register()
    {
        $response = $this->post(route('register.perform'), [
            'username' => 'testuser',
            'email' => 'testuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->dump(); // Tambahkan ini untuk melihat error validasi
        $response->assertRedirect('/');
        $this->assertDatabaseHas('users', [
            'username' => 'testuser',
            'email' => 'testuser@example.com',
        ]);
        $this->assertAuthenticated();
    }
}
