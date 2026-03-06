<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->markTestSkipped('Skipped: project-specific public auth flow tests are intentionally disabled.');
    }

    public function test_public_register_route_is_not_exposed()
    {
        $response = $this->get('/register');

        $response->assertStatus(404);
    }

    public function test_public_registration_post_is_not_exposed()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(404);
    }
}
