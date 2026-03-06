<?php

namespace Tests\Feature\Master;

use App\Models\Admin;
use App\Models\Partner;
use App\Models\UserInfo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * CRUD tests for Partner master module.
 *
 * Partner uses the `admins` table with PartnerScope (user_type IN [1,2,3]),
 * Spatie HasRoles with guard_name = 'admin', and creates related user_infos.
 */
class PartnerCrudTest extends TestCase
{
    use RefreshDatabase;

    protected Admin $admin;
    protected string $prefix;
    protected Role $role;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin  = Admin::factory()->create();
        $this->prefix = config('app.admin_prefix', 'rrkadminmanager');
        $this->actingAs($this->admin, 'admin');

        // Spatie role required by PartnerController::store / update
        $this->role = Role::create(['name' => 'Admin', 'guard_name' => 'admin']);

        // Clear Spatie permission cache for clean test runs
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
    }

    // =====================================================================
    //  AUTH
    // =====================================================================

    /** @test */
    public function guest_cannot_access_partner_index()
    {
        $this->app['auth']->forgetGuards();

        $response = $this->get("/{$this->prefix}/master/partner");
        $response->assertRedirect();
    }

    // =====================================================================
    //  INDEX / CREATE / EDIT views
    // =====================================================================

    /** @test */
    public function admin_can_view_partner_index()
    {
        $response = $this->get("/{$this->prefix}/master/partner");
        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_view_partner_create_form()
    {
        $response = $this->get("/{$this->prefix}/master/partner/create");
        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_view_partner_edit_form()
    {
        $partner = Partner::factory()->create();
        // Blade template accesses user_info->image_full_url
        UserInfo::create(['admin_type' => 1, 'admin_id' => $partner->id]);

        $response = $this->get("/{$this->prefix}/master/partner/{$partner->id}/edit");
        $response->assertStatus(200);
    }

    // =====================================================================
    //  STORE
    // =====================================================================

    /** @test */
    public function admin_can_store_partner()
    {
        $response = $this->post("/{$this->prefix}/master/partner", [
            'first_name'      => 'Test Partner',
            'last_name'       => 'Name',
            'email'           => 'partner@test.com',
            'phone_number'    => '9876543210',
            'password'        => 'password123',
            'user_type'       => 1,
            'user_code'       => 'P001',
            'status'          => 1,
            'role_id'         => $this->role->id,
            'submission_type' => 1,
        ]);

        $response->assertRedirect(route('admin.partner.index'));
        $this->assertDatabaseHas('admins', [
            'first_name' => 'Test Partner',
            'user_type'  => 1,
        ]);
        $this->assertDatabaseHas('user_infos', ['admin_type' => 1]);
    }

    /** @test */
    public function store_partner_assigns_spatie_role()
    {
        $this->post("/{$this->prefix}/master/partner", [
            'first_name'      => 'Role Partner',
            'phone_number'    => '9876543213',
            'password'        => 'password123',
            'user_type'       => 1,
            'status'          => 1,
            'role_id'         => $this->role->id,
            'submission_type' => 1,
        ]);

        $partner = Partner::where('first_name', 'Role Partner')->first();
        $this->assertNotNull($partner);
        $this->assertTrue($partner->hasRole('Admin'));
    }

    /** @test */
    public function store_partner_creates_user_info()
    {
        $this->post("/{$this->prefix}/master/partner", [
            'first_name'      => 'Info Partner',
            'phone_number'    => '9876543214',
            'password'        => 'password123',
            'user_type'       => 1,
            'status'          => 1,
            'role_id'         => $this->role->id,
            'company'         => 'Partner Co',
            'gst_number'      => 'GST789',
            'submission_type' => 1,
        ]);

        $this->assertDatabaseHas('user_infos', [
            'admin_type'  => 1,
            'company'     => 'Partner Co',
            'gst_number'  => 'GST789',
        ]);
    }

    // =====================================================================
    //  UPDATE
    // =====================================================================

    /** @test */
    public function admin_can_update_partner()
    {
        $partner = Partner::factory()->create();

        $response = $this->put("/{$this->prefix}/master/partner/{$partner->id}", [
            'first_name'      => 'Updated Partner',
            'last_name'       => 'Updated',
            'email'           => 'updated-partner@test.com',
            'phone_number'    => $partner->phone_number,
            'user_type'       => 1,
            'status'          => 1,
            'role_id'         => $this->role->id,
            'submission_type' => 1,
        ]);

        $response->assertRedirect(route('admin.partner.index'));
        $this->assertDatabaseHas('admins', [
            'id'         => $partner->id,
            'first_name' => 'Updated Partner',
        ]);
    }

    // =====================================================================
    //  DELETE
    // =====================================================================

    /** @test */
    public function admin_can_delete_partner()
    {
        $partner = Partner::factory()->create();
        // Controller accesses user_info->image before deleting;
        // create a stub so the relationship is not null.
        UserInfo::create(['admin_type' => 1, 'admin_id' => $partner->id]);

        $response = $this->deleteJson("/{$this->prefix}/master/partner/{$partner->id}");

        $response->assertOk();
        $response->assertJson(['status' => 200]);
        $this->assertSoftDeleted('admins', ['id' => $partner->id]);
    }

    // =====================================================================
    //  VALIDATION
    // =====================================================================

    /** @test */
    public function store_partner_requires_first_name()
    {
        $response = $this->post("/{$this->prefix}/master/partner", [
            'first_name'      => '',
            'phone_number'    => '9876543210',
            'role_id'         => $this->role->id,
            'submission_type' => 1,
        ]);

        $response->assertSessionHasErrors('first_name');
    }

    /** @test */
    public function store_partner_requires_phone_number()
    {
        $response = $this->post("/{$this->prefix}/master/partner", [
            'first_name'      => 'Test Partner',
            'phone_number'    => '',
            'role_id'         => $this->role->id,
            'submission_type' => 1,
        ]);

        $response->assertSessionHasErrors('phone_number');
    }

    /** @test */
    public function store_partner_rejects_duplicate_phone()
    {
        Partner::factory()->create(['phone_number' => '9876543210']);

        $response = $this->post("/{$this->prefix}/master/partner", [
            'first_name'      => 'Duplicate',
            'phone_number'    => '9876543210',
            'role_id'         => $this->role->id,
            'submission_type' => 1,
        ]);

        $response->assertSessionHasErrors('phone_number');
    }
}
