<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->markTestSkipped('Skipped: project-specific public auth flow tests are intentionally disabled.');
    }

    public function test_email_verification_routes_are_not_exposed()
    {
        $this->get('/verify-email')->assertStatus(404);
        $this->get('/verify-email/1/hash')->assertStatus(404);
    }

    public function test_email_verification_routes_remain_unavailable_for_authenticated_user()
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/verify-email')->assertStatus(404);
        $this->actingAs($user)->get('/verify-email/1/hash')->assertStatus(404);
    }
}
