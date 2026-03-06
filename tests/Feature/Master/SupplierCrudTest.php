<?php

namespace Tests\Feature\Master;

use App\Models\Admin;
use App\Models\Supplier;
use App\Models\UserInfo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * CRUD tests for Supplier master module.
 *
 * Supplier uses the `users` table with SupplierScope (user_type = 2)
 * and creates related user_infos + salary_details records.
 */
class SupplierCrudTest extends TestCase
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
    public function guest_cannot_access_supplier_index()
    {
        $this->app['auth']->forgetGuards();

        $response = $this->get("/{$this->prefix}/master/supplier");
        $response->assertRedirect();
    }

    // =====================================================================
    //  INDEX / CREATE / EDIT views
    // =====================================================================

    /** @test */
    public function admin_can_view_supplier_index()
    {
        $response = $this->get("/{$this->prefix}/master/supplier");
        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_view_supplier_create_form()
    {
        $response = $this->get("/{$this->prefix}/master/supplier/create");
        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_view_supplier_edit_form()
    {
        $supplier = Supplier::factory()->create();

        $response = $this->get("/{$this->prefix}/master/supplier/{$supplier->id}/edit");
        $response->assertStatus(200);
    }

    // =====================================================================
    //  STORE
    // =====================================================================

    /** @test */
    public function admin_can_store_supplier()
    {
        $response = $this->post("/{$this->prefix}/master/supplier", [
            'first_name'      => 'Test Supplier',
            'last_name'       => 'Name',
            'email'           => 'supplier@test.com',
            'phone_number'    => '9876543210',
            'password'        => 'password123',
            'status'          => 1,
            'salary_type'     => 1,
            'amount_type'     => 1,
            'amount'          => 5000,
            'submission_type' => 1,
        ]);

        $response->assertRedirect(route('admin.supplier.index'));
        $this->assertDatabaseHas('users', [
            'first_name' => 'Test Supplier',
            'user_type'  => 2,
        ]);
        $this->assertDatabaseHas('salary_details', [
            'salary_type' => 1,
            'amount_type' => 1,
        ]);
    }

    /** @test */
    public function admin_can_store_supplier_with_percentage()
    {
        $response = $this->post("/{$this->prefix}/master/supplier", [
            'first_name'      => 'Percent Supplier',
            'last_name'       => 'Name',
            'email'           => 'percent@test.com',
            'phone_number'    => '9876543211',
            'password'        => 'password123',
            'status'          => 1,
            'salary_type'     => 1,
            'amount_type'     => 2,
            'percentage'      => 15.5,
            'submission_type' => 1,
        ]);

        $response->assertRedirect(route('admin.supplier.index'));
        $this->assertDatabaseHas('users', ['first_name' => 'Percent Supplier']);
        $this->assertDatabaseHas('salary_details', ['amount_type' => 2]);
    }

    /** @test */
    public function store_supplier_creates_user_info()
    {
        $this->post("/{$this->prefix}/master/supplier", [
            'first_name'      => 'Info Supplier',
            'phone_number'    => '9876543212',
            'password'        => 'password123',
            'status'          => 1,
            'company'         => 'Supplier Co',
            'gst_number'      => 'GST456',
            'salary_type'     => 1,
            'amount_type'     => 1,
            'amount'          => 1000,
            'submission_type' => 1,
        ]);

        $this->assertDatabaseHas('user_infos', [
            'admin_type' => 2,
            'company'    => 'Supplier Co',
            'gst_number' => 'GST456',
        ]);
    }

    // =====================================================================
    //  UPDATE
    // =====================================================================

    /** @test */
    public function admin_can_update_supplier()
    {
        $supplier = Supplier::factory()->create();

        $response = $this->put("/{$this->prefix}/master/supplier/{$supplier->id}", [
            'first_name'      => 'Updated Supplier',
            'last_name'       => 'Updated',
            'email'           => 'updated-sup@test.com',
            'phone_number'    => $supplier->phone_number,
            'status'          => 1,
            'salary_type'     => 2,
            'amount_type'     => 1,
            'amount'          => 10000,
            'submission_type' => 1,
        ]);

        $response->assertRedirect(route('admin.supplier.index'));
        $this->assertDatabaseHas('users', [
            'id'         => $supplier->id,
            'first_name' => 'Updated Supplier',
        ]);
    }

    // =====================================================================
    //  DELETE
    // =====================================================================

    /** @test */
    public function admin_can_delete_supplier()
    {
        $supplier = Supplier::factory()->create();
        // Controller accesses user_info->image before deleting;
        // create a stub so the relationship is not null.
        UserInfo::create(['admin_type' => 2, 'user_id' => $supplier->id]);

        $response = $this->deleteJson("/{$this->prefix}/master/supplier/{$supplier->id}");

        $response->assertOk();
        $response->assertJson(['status' => 200]);
        $this->assertSoftDeleted('users', ['id' => $supplier->id]);
    }

    // =====================================================================
    //  VALIDATION
    // =====================================================================

    /** @test */
    public function store_supplier_requires_first_name()
    {
        $response = $this->post("/{$this->prefix}/master/supplier", [
            'first_name'      => '',
            'phone_number'    => '9876543210',
            'salary_type'     => 1,
            'amount_type'     => 1,
            'submission_type' => 1,
        ]);

        $response->assertSessionHasErrors('first_name');
    }

    /** @test */
    public function store_supplier_requires_salary_type()
    {
        $response = $this->post("/{$this->prefix}/master/supplier", [
            'first_name'      => 'Test Supplier',
            'phone_number'    => '9876543210',
            'amount_type'     => 1,
            'submission_type' => 1,
        ]);

        $response->assertSessionHasErrors('salary_type');
    }

    /** @test */
    public function store_supplier_requires_amount_type()
    {
        $response = $this->post("/{$this->prefix}/master/supplier", [
            'first_name'      => 'Test Supplier',
            'phone_number'    => '9876543210',
            'salary_type'     => 1,
            'submission_type' => 1,
        ]);

        $response->assertSessionHasErrors('amount_type');
    }

    /** @test */
    public function store_supplier_rejects_duplicate_phone()
    {
        Supplier::factory()->create(['phone_number' => '9876543210']);

        $response = $this->post("/{$this->prefix}/master/supplier", [
            'first_name'      => 'Duplicate',
            'phone_number'    => '9876543210',
            'salary_type'     => 1,
            'amount_type'     => 1,
            'submission_type' => 1,
        ]);

        $response->assertSessionHasErrors('phone_number');
    }
}
