<?php

namespace Tests\Feature;

use App\Models\Token;
use Tests\TestCase;

class TokenControllerTest extends TestCase
{
    /**
     * @var array
     */
    private $headers;

    public function setUp(): void
    {
        parent::setUp();
        $response = $this->postJson('/api/login', ['email' => 'admin@matchmove.io', 'password' => '123456']);
        $token = $response['access_token'];
        $this->headers = ['Authorization' => "Bearer $token"];
    }

    public function test_overview()
    {
        $response = $this->getJson('/api/tokens/overview', $this->headers);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'activeTokens',
                    'inActiveTokens'
                ],
            ]);
    }

    public function test_generate()
    {
        $payload = ['title' => 'Test Title'];
        $response = $this->postJson('/api/tokens/generate', $payload, $this->headers);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'title',
                    'token_hash',
                    'expire_at',
                    'updated_at',
                    'created_at',
                    'id'
                ],
            ]);
    }

    public function test_recall()
    {
        $token = Token::where('status', true)->first();
        $response = $this->putJson('/api/tokens/recall/' . $token->token_hash, [], $this->headers);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'title',
                    'token_hash',
                    'expire_at',
                    'status',
                    'updated_at',
                    'created_at',
                    'id'
                ],
            ]);
    }

    public function test_success_validate_token()
    {
        $token = Token::where('status', true)->first();
        $response = $this->getJson('/api/validate-token/' . $token->token_hash, $this->headers);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'status'
            ])->assertJson([
                'message' => 'valid token', 'status' => 'success'
            ]);
    }

    public function test_fail_validate_token()
    {
        $token = Token::where('status', false)->first();
        $response = $this->getJson('/api/validate-token/' . $token->token_hash, $this->headers);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'status'
            ])->assertJson([
                'message' => 'invalid token', 'status' => 'fail'
            ]);
    }
}
