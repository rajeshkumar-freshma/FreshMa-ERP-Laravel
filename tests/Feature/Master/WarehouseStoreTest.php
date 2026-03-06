<?php

namespace Tests\Feature\Master;

use App\Models\Admin;
use App\Models\Store;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Feature tests for Warehouse and Store CRUD operations.
 *
 * Note: "show" views are not tested because they render Blade templates
 * that reference seeded lookup data (cities, states, countries).
 */
class WarehouseStoreTest extends TestCase
{
    use RefreshDatabase;

    protected Admin $admin;
    protected string $prefix;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin  = Admin::factory()->create();
        $this->prefix = config('app.admin_prefix', 'rrkadminmanager');

        // Authenticate so model boot events (resolveActorId) use correct admin ID
        $this->actingAs($this->admin, 'admin');
    }

    // =====================================================================
    //  WAREHOUSE
    // =====================================================================

    /** @test */
    public function guest_cannot_access_warehouse_index()
    {
        $this->app['auth']->forgetGuards();

        $response = $this->get("/{$this->prefix}/master/warehouse");
        $response->assertRedirect();
    }

    /** @test */
    public function admin_can_view_warehouse_index()
    {
        $response = $this->get("/{$this->prefix}/master/warehouse");
        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_view_warehouse_create_form()
    {
        $response = $this->get("/{$this->prefix}/master/warehouse/create");
        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_store_warehouse()
    {
        $response = $this->post("/{$this->prefix}/master/warehouse", [
            'name'            => 'Main Warehouse',
            'code'            => 'WH-001',
            'phone_number'    => '9876543210',
            'email'           => 'wh@test.com',
            'start_date'      => '2026-01-01',
            'address'         => '123 Main St',
            'city_id'         => 1,
            'state_id'        => 1,
            'country_id'      => 1,
            'pincode'         => '600001',
            'status'          => 1,
            'is_default'      => 0,
            'submission_type' => 1,
        ]);

        $response->assertRedirect(route('admin.warehouse.index'));
        $this->assertDatabaseHas('warehouses', ['name' => 'Main Warehouse']);
    }

    /** @test */
    public function admin_can_view_warehouse_edit_form()
    {
        $warehouse = Warehouse::factory()->create();

        $response = $this->get("/{$this->prefix}/master/warehouse/{$warehouse->id}/edit");
        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_update_warehouse()
    {
        $warehouse = Warehouse::factory()->create();

        $response = $this->put("/{$this->prefix}/master/warehouse/{$warehouse->id}", [
            'name'            => 'Updated Warehouse',
            'code'            => 'WH-UPD',
            'phone_number'    => '8888888888',
            'email'           => 'updated@test.com',
            'start_date'      => '2026-02-01',
            'address'         => '456 Other St',
            'city_id'         => 1,
            'state_id'        => 1,
            'country_id'      => 1,
            'pincode'         => '600002',
            'status'          => 1,
            'is_default'      => 0,
            'submission_type' => 1,
        ]);

        $response->assertRedirect(route('admin.warehouse.index'));
        $this->assertDatabaseHas('warehouses', ['id' => $warehouse->id, 'name' => 'Updated Warehouse']);
    }

    /** @test */
    public function admin_can_delete_warehouse()
    {
        $warehouse = Warehouse::factory()->create();

        $response = $this->deleteJson("/{$this->prefix}/master/warehouse/{$warehouse->id}");

        $response->assertOk();
        $response->assertJson(['status' => 200]);
        $this->assertSoftDeleted('warehouses', ['id' => $warehouse->id]);
    }

    /** @test */
    public function admin_can_set_default_warehouse()
    {
        $warehouse = Warehouse::factory()->create(['is_default' => 0]);

        $response = $this->postJson("/{$this->prefix}/master/warehouse/default-change", [
            'warehouse_id' => $warehouse->id,
            'value'         => 1,
            'default'       => 1,
        ]);

        $response->assertOk();
        $response->assertJson(['status' => 200]);
        $this->assertDatabaseHas('warehouses', ['id' => $warehouse->id, 'is_default' => 1]);
    }

    /** @test */
    public function store_warehouse_with_default_resets_previous_default()
    {
        // Create an existing default warehouse
        $existing = Warehouse::factory()->create(['is_default' => 1, 'status' => 1]);
        $this->assertDatabaseHas('warehouses', ['id' => $existing->id, 'is_default' => 1]);

        // Store a new warehouse as default
        $response = $this->post("/{$this->prefix}/master/warehouse", [
            'name'            => 'New Default WH',
            'code'            => 'WH-DEF',
            'phone_number'    => '1111111111',
            'email'           => 'def@test.com',
            'start_date'      => '2026-01-01',
            'address'         => '1 Default St',
            'city_id'         => 1,
            'state_id'        => 1,
            'country_id'      => 1,
            'pincode'         => '600010',
            'status'          => 1,
            'is_default'      => 1,
            'submission_type' => 1,
        ]);

        $response->assertRedirect(route('admin.warehouse.index'));

        // Previous default should be reset to 0
        $this->assertDatabaseHas('warehouses', ['id' => $existing->id, 'is_default' => 0]);
        // New warehouse should be the default
        $this->assertDatabaseHas('warehouses', ['name' => 'New Default WH', 'is_default' => 1]);
    }

    /** @test */
    public function store_warehouse_rejects_default_with_inactive_status()
    {
        $response = $this->post("/{$this->prefix}/master/warehouse", [
            'name'            => 'Bad Default WH',
            'code'            => 'WH-BAD',
            'phone_number'    => '2222222222',
            'email'           => 'bad@test.com',
            'start_date'      => '2026-01-01',
            'address'         => '2 Bad St',
            'city_id'         => 1,
            'state_id'        => 1,
            'country_id'      => 1,
            'pincode'         => '600011',
            'status'          => 0,
            'is_default'      => 1,
            'submission_type' => 1,
        ]);

        // Should redirect back with warning, not create the warehouse as default
        $response->assertRedirect();
        $this->assertDatabaseMissing('warehouses', ['name' => 'Bad Default WH']);
    }

    /** @test */
    public function update_warehouse_with_default_resets_previous_default()
    {
        $whA = Warehouse::factory()->create(['is_default' => 1, 'status' => 1]);
        $whB = Warehouse::factory()->create(['is_default' => 0, 'status' => 1]);

        // Update whB to become default
        $response = $this->put("/{$this->prefix}/master/warehouse/{$whB->id}", [
            'name'            => $whB->name,
            'code'            => $whB->code,
            'phone_number'    => '9876500000',
            'email'           => $whB->email,
            'start_date'      => '2026-01-01',
            'address'         => $whB->address ?? '1 St',
            'city_id'         => 1,
            'state_id'        => 1,
            'country_id'      => 1,
            'pincode'         => '600001',
            'status'          => 1,
            'is_default'      => 1,
        ]);

        $response->assertRedirect(route('admin.warehouse.index'));

        // whA should no longer be default
        $this->assertDatabaseHas('warehouses', ['id' => $whA->id, 'is_default' => 0]);
        // whB should now be default
        $this->assertDatabaseHas('warehouses', ['id' => $whB->id, 'is_default' => 1]);
    }

    /** @test */
    public function update_warehouse_prevents_removing_default()
    {
        $wh = Warehouse::factory()->create(['is_default' => 1, 'status' => 1]);

        // Try to update and remove default
        $response = $this->put("/{$this->prefix}/master/warehouse/{$wh->id}", [
            'name'            => $wh->name,
            'code'            => $wh->code,
            'phone_number'    => '9876500001',
            'email'           => $wh->email,
            'start_date'      => '2026-01-01',
            'address'         => $wh->address ?? '1 St',
            'city_id'         => 1,
            'state_id'        => 1,
            'country_id'      => 1,
            'pincode'         => '600001',
            'status'          => 1,
            'is_default'      => 0,
        ]);

        // Should redirect back with warning
        $response->assertRedirect();
        // Default should remain unchanged
        $this->assertDatabaseHas('warehouses', ['id' => $wh->id, 'is_default' => 1]);
    }

    /** @test */
    public function default_warehouse_update_resets_all_previous_defaults()
    {
        $whA = Warehouse::factory()->create(['is_default' => 1, 'status' => 1]);
        $whB = Warehouse::factory()->create(['is_default' => 0, 'status' => 1]);

        $response = $this->postJson("/{$this->prefix}/master/warehouse/default-change", [
            'warehouse_id' => $whB->id,
            'value'        => 1,
            'default'      => 1,
        ]);

        $response->assertOk();
        $response->assertJson(['status' => 200]);
        $this->assertDatabaseHas('warehouses', ['id' => $whA->id, 'is_default' => 0]);
        $this->assertDatabaseHas('warehouses', ['id' => $whB->id, 'is_default' => 1]);
    }

    /** @test */
    public function default_warehouse_update_rejects_disabled_warehouse()
    {
        $wh = Warehouse::factory()->create(['is_default' => 0, 'status' => 0]);

        $response = $this->postJson("/{$this->prefix}/master/warehouse/default-change", [
            'warehouse_id' => $wh->id,
            'value'        => 1,
            'default'      => 1,
        ]);

        $response->assertJson(['status' => 400]);
        $this->assertDatabaseHas('warehouses', ['id' => $wh->id, 'is_default' => 0]);
    }

    // =====================================================================
    //  STORE
    // =====================================================================

    /** @test */
    public function guest_cannot_access_store_index()
    {
        $this->app['auth']->forgetGuards();

        $response = $this->get("/{$this->prefix}/master/store");
        $response->assertRedirect();
    }

    /** @test */
    public function admin_can_view_store_index()
    {
        $response = $this->get("/{$this->prefix}/master/store");
        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_view_store_create_form()
    {
        $response = $this->get("/{$this->prefix}/master/store/create");
        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_store_store()
    {
        $warehouse = Warehouse::factory()->create();

        $response = $this->post("/{$this->prefix}/master/store", [
            'store_name'      => 'Main Store',
            'store_code'      => 'ST001',
            'warehouse_id'    => $warehouse->id,
            'phone_number'    => '9876543210',
            'email'           => 'store@test.com',
            'start_date'      => '2026-01-01',
            'gst_number'      => 'GST123456',
            'address'         => '789 Store St',
            'city_id'         => 1,
            'state_id'        => 1,
            'country_id'      => 1,
            'pincode'         => '600003',
            'status'          => 1,
            'submission_type' => 1,
        ]);

        $response->assertRedirect(route('admin.store.index'));
        $this->assertDatabaseHas('stores', ['store_name' => 'Main Store']);
    }

    /** @test */
    public function admin_can_update_store()
    {
        $store = Store::factory()->create();

        $response = $this->put("/{$this->prefix}/master/store/{$store->id}", [
            'store_name'      => 'Updated Store',
            'store_code'      => $store->store_code,
            'warehouse_id'    => $store->warehouse_id,
            'phone_number'    => '7777777777',
            'email'           => 'updated-store@test.com',
            'start_date'      => '2026-02-01',
            'gst_number'      => 'GST654321',
            'address'         => '321 Updated St',
            'city_id'         => 1,
            'state_id'        => 1,
            'country_id'      => 1,
            'pincode'         => '600004',
            'status'          => 1,
            'submission_type' => 1,
        ]);

        $response->assertRedirect(route('admin.store.index'));
        $this->assertDatabaseHas('stores', ['id' => $store->id, 'store_name' => 'Updated Store']);
    }

    /** @test */
    public function admin_can_delete_store()
    {
        $store = Store::factory()->create();

        $response = $this->deleteJson("/{$this->prefix}/master/store/{$store->id}");

        $response->assertOk();
        $response->assertJson(['status' => 200]);
        $this->assertSoftDeleted('stores', ['id' => $store->id]);
    }
}
