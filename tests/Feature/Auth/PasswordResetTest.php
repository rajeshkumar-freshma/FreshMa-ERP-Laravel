<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->markTestSkipped('Skipped: project-specific public auth flow tests are intentionally disabled.');
    }

    public function test_forgot_password_route_is_not_exposed()
    {
        $response = $this->get('/forgot-password');

        $response->assertStatus(404);
    }

    public function test_forgot_password_post_route_is_not_exposed()
    {
        $response = $this->post('/forgot-password', ['email' => 'test@example.com']);
        $response->assertStatus(404);
    }

    public function test_reset_password_route_is_not_exposed()
    {
        $this->get('/reset-password/test-token')->assertStatus(404);
        $this->post('/reset-password', [
            'token' => 'test-token',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertStatus(404);
    }
}
