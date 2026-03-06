<?php

namespace Tests\Feature\API;

use Tests\TestCase;

class AuthEndpointsValidationTest extends TestCase
{
    public function test_login_requires_credentials_payload(): void
    {
        $response = $this->postJson('/api/v1/login', []);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'status',
                'message',
                'errors',
            ]);
    }

    public function test_login_requires_password_when_email_is_sent(): void
    {
        $response = $this->postJson('/api/v1/login', [
            'email' => 'ops@example.com',
        ]);

        $response->assertStatus(422)
            ->assertJsonPath('errors.password.0', 'The password field is required when email is present.');
    }

    public function test_verify_otp_requires_phone_and_otp(): void
    {
        $response = $this->postJson('/api/v1/verify-otp', []);

        $response->assertStatus(422)
            ->assertJsonPath('message', 'Validation failed');
    }

    public function test_verify_token_requires_api_token(): void
    {
        $response = $this->postJson('/api/v1/verify_token', []);

        $response->assertStatus(422)
            ->assertJsonPath('errors.api_token.0', 'The api token field is required.');
    }

    public function test_save_token_requires_api_authentication(): void
    {
        $response = $this->postJson('/api/v1/save-token', [
            'fcmToken' => 'token-value',
        ]);

        $response->assertStatus(401);
    }

    public function test_api_auth_routes_are_rate_limited(): void
    {
        $client = $this->withServerVariables(['REMOTE_ADDR' => '10.10.10.10']);

        for ($i = 0; $i < 20; $i++) {
            $client->postJson('/api/v1/login', [
                'email' => 'nobody@example.com',
                'password' => 'not-valid',
            ]);
        }

        $response = $client->postJson('/api/v1/login', [
            'email' => 'nobody@example.com',
            'password' => 'not-valid',
        ]);

        $response->assertStatus(429);
    }
}
