<?php

namespace Tests\Feature\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    public function testRegisterSuccess()
    {
        // Generate a new User instance with fake data
        $userData = User::factory()->make()->toArray();

        // Add the password and password_confirmation fields
        $userData['password'] = 'test1234';
        $userData['password_confirmation'] = 'test1234';
        $response = $this->postJson('/api/auth/register', $userData);

        $response->assertStatus(200);
    }

    public function testRegisterFail()
    {
        $response = $this->postJson('/api/auth/register', [
            'email' => 'test@me.com',
            'password' => 'test1234',
            'password_confirmation' => 'test1234',
            'name' => 'test'
        ]);
        $response->assertStatus(422);
    }
}
