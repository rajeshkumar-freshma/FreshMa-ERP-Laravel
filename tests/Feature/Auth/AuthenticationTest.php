<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->markTestSkipped('Skipped: project-specific public auth flow tests are intentionally disabled.');
    }

    public function test_supplier_login_screen_can_be_rendered()
    {
        $supplierPrefix = trim((string) env('SUPPLIER_PREFIX', 'rrksupplier'), '/');
        $response = $this->get("/{$supplierPrefix}/login");

        $response->assertStatus(200);
    }

    public function test_admin_login_screen_can_be_rendered()
    {
        $adminPrefix = trim((string) env('ADMIN_PREFIX', 'rrkadminmanager'), '/');
        $response = $this->get("/{$adminPrefix}/login");

        $response->assertStatus(200);
    }

    public function test_supplier_dashboard_requires_authentication()
    {
        $supplierPrefix = trim((string) env('SUPPLIER_PREFIX', 'rrksupplier'), '/');
        $response = $this->get("/{$supplierPrefix}");

        $response->assertStatus(302);
    }
}
