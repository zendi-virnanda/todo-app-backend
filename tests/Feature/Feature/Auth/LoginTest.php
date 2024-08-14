<?php

namespace Tests\Feature\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    public function testLoginSuccessfully()
    {
        $response = $this->postJson('/api/auth/login', [
            'email' => 'zendifirnanda05@gmail.com',
            'password' => 'admin1234',
        ]);
        $response->assertStatus(200);
    }
}
