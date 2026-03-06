<?php

namespace Tests\Feature\Master;

use App\Models\Admin;
use App\Models\Vendor;
use App\Models\UserInfo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * CRUD tests for Vendor / Customer master module.
 *
 * Note: Vendor routes are registered as "customer" (admin.customer.*).
 */
class VendorCrudTest extends TestCase
{
    use RefreshDatabase;

    protected Admin $admin;
    protected string $prefix;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin  = Admin::factory()->create();
        $this->prefix = config('app.admin_prefix', 'rrkadminmanager');
        $this->actingAs($this->admin, 'admin');
    }

    // =====================================================================
    //  AUTH
    // =====================================================================

    /** @test */
    public function guest_cannot_access_customer_index()
    {
        $this->app['auth']->forgetGuards();

        $response = $this->get("/{$this->prefix}/master/customer");
        $response->assertRedirect();
    }

    // =====================================================================
    //  INDEX / CREATE / EDIT views
    // =====================================================================

    /** @test */
    public function admin_can_view_customer_index()
    {
        $response = $this->get("/{$this->prefix}/master/customer");
        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_view_customer_create_form()
    {
        $response = $this->get("/{$this->prefix}/master/customer/create");
        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_view_customer_edit_form()
    {
        $vendor = Vendor::factory()->create();

        $response = $this->get("/{$this->prefix}/master/customer/{$vendor->id}/edit");
        $response->assertStatus(200);
    }

    // =====================================================================
    //  STORE
    // =====================================================================

    /** @test */
    public function admin_can_store_vendor()
    {
        $response = $this->post("/{$this->prefix}/master/customer", [
            'first_name'      => 'Test Vendor',
            'last_name'       => 'Name',
            'email'           => 'vendor@test.com',
            'phone_number'    => '9876543210',
            'password'        => 'password123',
            'user_type'       => 1,
            'status'          => 1,
            'company'         => 'Test Company',
            'submission_type' => 1,
        ]);

        $response->assertRedirect(route('admin.customer.index'));
        $this->assertDatabaseHas('users', [
            'first_name' => 'Test Vendor',
            'user_type'  => 1,
        ]);
        $this->assertDatabaseHas('user_infos', ['company' => 'Test Company']);
    }

    /** @test */
    public function admin_can_store_vendor_with_commission()
    {
        $response = $this->post("/{$this->prefix}/master/customer", [
            'first_name'             => 'Commission Vendor',
            'last_name'              => 'Name',
            'email'                  => 'commission@test.com',
            'phone_number'           => '9876543211',
            'password'               => 'password123',
            'user_type'              => 1,
            'status'                 => 1,
            'vendor_commission'      => 10.5,
            'it_can_edit_on_billing' => 1,
            'joined_at'              => '2026-01-15',
            'remarks'                => 'Test remarks',
            'submission_type'        => 1,
        ]);

        $response->assertRedirect(route('admin.customer.index'));
        $this->assertDatabaseHas('users', ['first_name' => 'Commission Vendor']);
        $this->assertDatabaseHas('vendor_details', ['vendor_commission' => 10.5]);
    }

    /** @test */
    public function store_vendor_creates_user_info()
    {
        $this->post("/{$this->prefix}/master/customer", [
            'first_name'      => 'Info Vendor',
            'phone_number'    => '9876543212',
            'password'        => 'password123',
            'user_type'       => 1,
            'status'          => 1,
            'company'         => 'Info Co',
            'gst_number'      => 'GST123',
            'address'         => '123 Street',
            'submission_type' => 1,
        ]);

        $this->assertDatabaseHas('user_infos', [
            'admin_type'  => 2,
            'company'     => 'Info Co',
            'gst_number'  => 'GST123',
            'address'     => '123 Street',
        ]);
    }

    // =====================================================================
    //  UPDATE
    // =====================================================================

    /** @test */
    public function admin_can_update_vendor()
    {
        $vendor = Vendor::factory()->create();

        $response = $this->put("/{$this->prefix}/master/customer/{$vendor->id}", [
            'first_name'      => 'Updated Vendor',
            'last_name'       => 'Updated',
            'email'           => 'updated@test.com',
            'phone_number'    => $vendor->phone_number,
            'user_type'       => 1,
            'status'          => 1,
            'company'         => 'Updated Company',
            'submission_type' => 1,
        ]);

        $response->assertRedirect(route('admin.customer.index'));
        $this->assertDatabaseHas('users', [
            'id'         => $vendor->id,
            'first_name' => 'Updated Vendor',
        ]);
    }

    // =====================================================================
    //  DELETE
    // =====================================================================

    /** @test */
    public function admin_can_delete_vendor()
    {
        $vendor = Vendor::factory()->create();
        // Controller accesses user_info->image before deleting;
        // create a stub so the relationship is not null.
        UserInfo::create(['admin_type' => 2, 'user_id' => $vendor->id]);

        $response = $this->deleteJson("/{$this->prefix}/master/customer/{$vendor->id}");

        $response->assertOk();
        $response->assertJson(['status' => 200]);
        $this->assertSoftDeleted('users', ['id' => $vendor->id]);
    }

    // =====================================================================
    //  VALIDATION
    // =====================================================================

    /** @test */
    public function store_vendor_requires_first_name()
    {
        $response = $this->post("/{$this->prefix}/master/customer", [
            'first_name'      => '',
            'phone_number'    => '9876543210',
            'user_type'       => 1,
            'submission_type' => 1,
        ]);

        $response->assertSessionHasErrors('first_name');
    }

    /** @test */
    public function store_vendor_requires_phone_number()
    {
        $response = $this->post("/{$this->prefix}/master/customer", [
            'first_name'      => 'Test Vendor',
            'phone_number'    => '',
            'user_type'       => 1,
            'submission_type' => 1,
        ]);

        $response->assertSessionHasErrors('phone_number');
    }

    /** @test */
    public function store_vendor_rejects_duplicate_phone()
    {
        Vendor::factory()->create(['phone_number' => '9876543210']);

        $response = $this->post("/{$this->prefix}/master/customer", [
            'first_name'      => 'Duplicate',
            'phone_number'    => '9876543210',
            'user_type'       => 1,
            'submission_type' => 1,
        ]);

        $response->assertSessionHasErrors('phone_number');
    }

    /** @test */
    public function update_vendor_allows_own_phone_number()
    {
        $vendor = Vendor::factory()->create(['phone_number' => '9876543210']);

        $response = $this->put("/{$this->prefix}/master/customer/{$vendor->id}", [
            'first_name'      => 'Same Phone',
            'phone_number'    => '9876543210',
            'user_type'       => 1,
            'status'          => 1,
            'submission_type' => 1,
        ]);

        $response->assertRedirect(route('admin.customer.index'));
        $this->assertDatabaseHas('users', [
            'id'         => $vendor->id,
            'first_name' => 'Same Phone',
        ]);
    }
}
