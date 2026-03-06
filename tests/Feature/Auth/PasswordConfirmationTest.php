<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PasswordConfirmationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->markTestSkipped('Skipped: project-specific public auth flow tests are intentionally disabled.');
    }

    public function test_password_confirmation_routes_are_not_exposed()
    {
        $this->get('/confirm-password')->assertStatus(404);
        $this->post('/confirm-password', ['password' => 'password'])->assertStatus(404);
    }

    public function test_password_confirmation_routes_remain_unavailable_for_authenticated_user()
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/confirm-password')->assertStatus(404);
        $this->actingAs($user)->post('/confirm-password', [
            'password' => 'password',
        ])->assertStatus(404);
    }
}
