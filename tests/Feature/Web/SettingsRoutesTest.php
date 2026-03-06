<?php

namespace Tests\Feature\Web;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingsRoutesTest extends TestCase
{
    use RefreshDatabase;

    public function test_settings_routes_require_auth()
    {
        $this->get('/account/settings')->assertStatus(302); // redirect to login
    }

    public function test_settings_routes_work_for_admin()
    {
        $admin = Admin::factory()->create();
        $this->actingAs($admin, 'admin')
            ->get('/account/settings')
            ->assertStatus(200);
    }
}
