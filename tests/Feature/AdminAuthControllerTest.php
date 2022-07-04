<?php

namespace Tests\Feature;

use Tests\TestCase;

class AdminAuthControllerTest extends TestCase
{
    public function test_login_with_no_data()
    {
        $response = $this->postJson('/api/login');

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'email',
                    'password'
                ],
            ]);
    }

    public function test_login_with_only_email()
    {
        $response = $this->postJson('/api/login', ['email' => 'admin@matchmove.io']);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'password'
                ],
            ]);
    }

    public function test_login_with_only_password()
    {
        $response = $this->postJson('/api/login', ['password' => '123456']);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'email',
                ],
            ]);
    }

    public function test_login_with_invalid_data()
    {
        $response = $this->postJson('/api/login', ['email' => 'test@matchmove.io', 'password' => 'wrong_password']);

        $response->assertStatus(401)
            ->assertJsonStructure([
                'message',
            ]);
    }

    public function test_login_with_valid_data()
    {
        $response = $this->postJson('/api/login', ['email' => 'admin@matchmove.io', 'password' => '123456']);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'access_token',
                'token_type',
            ]);
    }
}
