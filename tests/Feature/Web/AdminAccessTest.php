<?php

namespace Tests\Feature\Web;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Guest should be redirected on admin routes.
     */
    public function test_guest_is_redirected_from_admin_route()
    {
        $response = $this->get('/rrkadminmanager/warehouse-indent-request');
        $response->assertStatus(302);
    }

    /**
     * Admin guard can access admin routes.
     */
    public function test_admin_can_access_admin_route()
    {
        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'admin')
            ->get('/rrkadminmanager/warehouse-indent-request');

        $response->assertStatus(200);
    }

    /**
     * Web user guard should not pass admin-only middleware.
     */
    public function test_supplier_web_user_cannot_access_admin_route()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'web')
            ->get('/rrkadminmanager/warehouse-indent-request');

        $response->assertStatus(302);
    }
}
