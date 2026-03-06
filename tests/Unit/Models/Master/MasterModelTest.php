<?php

namespace Tests\Unit\Models\Master;

use App\Models\Admin;
use App\Models\Category;
use App\Models\DenominationType;
use App\Models\IncomeExpenseType;
use App\Models\ItemType;
use App\Models\MachineData;
use App\Models\Partner;
use App\Models\PartnershipType;
use App\Models\PaymentType;
use App\Models\Store;
use App\Models\Supplier;
use App\Models\TaxRate;
use App\Models\TransportType;
use App\Models\Unit;
use App\Models\Vendor;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MasterModelTest extends TestCase
{
    use RefreshDatabase;

    protected Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = Admin::factory()->create();
        $this->actingAs($this->admin, 'admin');
    }

    // ─── Category ────────────────────────────────────────────────────

    /** @test */
    public function category_can_be_created()
    {
        $category = Category::factory()->create();

        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }

    /** @test */
    public function category_has_children_relationship()
    {
        $parent = Category::factory()->create();
        $child  = Category::factory()->child($parent)->create();

        $children = $parent->getCategory;

        $this->assertTrue($children->contains($child));
    }

    /** @test */
    public function category_has_recursive_children()
    {
        $parent     = Category::factory()->create();
        $child      = Category::factory()->child($parent)->create();
        $grandchild = Category::factory()->create(['parent_id' => $child->id]);

        $parent->load('getChildrenCategory');

        $this->assertTrue($parent->getChildrenCategory->contains($child));
    }

    /** @test */
    public function category_has_parent_relationship()
    {
        $parent = Category::factory()->create();
        $child  = Category::factory()->child($parent)->create();

        $this->assertEquals($parent->id, $child->getParent->id);
    }

    /** @test */
    public function category_soft_deletes()
    {
        $category = Category::factory()->create();
        $category->delete();

        $this->assertSoftDeleted('categories', ['id' => $category->id]);
    }

    /** @test */
    public function category_active_scope_filters_correctly()
    {
        Category::factory()->create(['status' => 1]);
        Category::factory()->create(['status' => 0]);

        $this->assertCount(1, Category::active()->get());
    }

    // ─── Unit ────────────────────────────────────────────────────────

    /** @test */
    public function unit_can_be_created()
    {
        $unit = Unit::factory()->create();

        $this->assertDatabaseHas('units', ['id' => $unit->id]);
    }

    /** @test */
    public function unit_soft_deletes()
    {
        $unit = Unit::factory()->create();
        $unit->delete();

        $this->assertSoftDeleted('units', ['id' => $unit->id]);
    }

    /** @test */
    public function unit_active_scope_returns_only_active()
    {
        Unit::factory()->create(['status' => 1, 'unit_name' => 'Active Unit', 'unit_short_code' => 'AU']);
        Unit::factory()->create(['status' => 0, 'unit_name' => 'Inactive Unit', 'unit_short_code' => 'IU']);

        $this->assertCount(1, Unit::active()->get());
    }

    /** @test */
    public function unit_has_created_by_details_relationship()
    {
        $unit = Unit::factory()->create();

        $this->assertNotNull($unit->created_by_details);
        $this->assertInstanceOf(Admin::class, $unit->created_by_details);
    }

    // ─── TaxRate ─────────────────────────────────────────────────────

    /** @test */
    public function tax_rate_can_be_created()
    {
        $taxRate = TaxRate::factory()->create();

        $this->assertDatabaseHas('tax_rates', ['id' => $taxRate->id]);
    }

    /** @test */
    public function tax_rate_soft_deletes()
    {
        $taxRate = TaxRate::factory()->create();
        $taxRate->delete();

        $this->assertSoftDeleted('tax_rates', ['id' => $taxRate->id]);
    }

    /** @test */
    public function tax_rate_active_scope()
    {
        TaxRate::factory()->create(['status' => 1, 'tax_name' => 'GST Active', 'tax_rate' => '5']);
        TaxRate::factory()->create(['status' => 0, 'tax_name' => 'GST Inactive', 'tax_rate' => '10']);

        $this->assertCount(1, TaxRate::active()->get());
    }

    /** @test */
    public function tax_rate_auto_sets_created_by()
    {
        $taxRate = TaxRate::factory()->create();

        $this->assertEquals($this->admin->id, $taxRate->created_by);
        $this->assertEquals($this->admin->id, $taxRate->updated_by);
    }

    // ─── ItemType ────────────────────────────────────────────────────

    /** @test */
    public function item_type_can_be_created()
    {
        $itemType = ItemType::factory()->create();

        $this->assertDatabaseHas('item_types', ['id' => $itemType->id]);
    }

    /** @test */
    public function item_type_soft_deletes()
    {
        $itemType = ItemType::factory()->create();
        $itemType->delete();

        $this->assertSoftDeleted('item_types', ['id' => $itemType->id]);
    }

    /** @test */
    public function item_type_auto_sets_created_by()
    {
        $itemType = ItemType::factory()->create();

        $this->assertEquals($this->admin->id, $itemType->created_by);
    }

    // ─── DenominationType ────────────────────────────────────────────

    /** @test */
    public function denomination_type_can_be_created()
    {
        $denom = DenominationType::factory()->create();

        $this->assertDatabaseHas('denomination_types', ['id' => $denom->id]);
    }

    /** @test */
    public function denomination_type_auto_generates_code()
    {
        $denom = DenominationType::factory()->create();

        $this->assertNotNull($denom->denomination_code);
        $this->assertStringStartsWith('DN', $denom->denomination_code);
    }

    /** @test */
    public function denomination_type_soft_deletes()
    {
        $denom = DenominationType::factory()->create();
        $denom->delete();

        $this->assertSoftDeleted('denomination_types', ['id' => $denom->id]);
    }

    // ─── IncomeExpenseType ───────────────────────────────────────────

    /** @test */
    public function income_expense_type_can_be_created()
    {
        $type = IncomeExpenseType::factory()->create();

        $this->assertDatabaseHas('income_expense_types', ['id' => $type->id]);
    }

    /** @test */
    public function income_expense_type_active_scope()
    {
        IncomeExpenseType::factory()->create(['status' => 1]);
        IncomeExpenseType::factory()->create(['status' => 0, 'name' => 'Inactive Type']);

        $this->assertCount(1, IncomeExpenseType::active()->get());
    }

    // ─── PartnershipType ─────────────────────────────────────────────

    /** @test */
    public function partnership_type_can_be_created()
    {
        $type = PartnershipType::factory()->create();

        $this->assertDatabaseHas('partnership_types', ['id' => $type->id]);
    }

    /** @test */
    public function partnership_type_soft_deletes()
    {
        $type = PartnershipType::factory()->create();
        $type->delete();

        $this->assertSoftDeleted('partnership_types', ['id' => $type->id]);
    }

    // ─── TransportType ───────────────────────────────────────────────

    /** @test */
    public function transport_type_can_be_created()
    {
        $type = TransportType::factory()->create();

        $this->assertDatabaseHas('transport_types', ['id' => $type->id]);
    }

    /** @test */
    public function transport_type_active_scope()
    {
        TransportType::factory()->create(['status' => 1]);
        TransportType::factory()->create(['status' => 0, 'transport_type' => 'Inactive']);

        $this->assertCount(1, TransportType::active()->get());
    }

    // ─── PaymentType ─────────────────────────────────────────────────

    /** @test */
    public function payment_type_can_be_created()
    {
        $type = PaymentType::factory()->create();

        $this->assertDatabaseHas('payment_types', ['id' => $type->id]);
    }

    /** @test */
    public function payment_type_active_scope()
    {
        PaymentType::factory()->create(['status' => 1]);
        PaymentType::factory()->create(['status' => 0, 'payment_type' => 'Inactive Pay', 'slug' => 'inactive-pay']);

        $this->assertCount(1, PaymentType::active()->get());
    }

    // ─── Warehouse ───────────────────────────────────────────────────

    /** @test */
    public function warehouse_can_be_created()
    {
        $warehouse = Warehouse::factory()->create();

        $this->assertDatabaseHas('warehouses', ['id' => $warehouse->id]);
    }

    /** @test */
    public function warehouse_soft_deletes()
    {
        $warehouse = Warehouse::factory()->create();
        $warehouse->delete();

        $this->assertSoftDeleted('warehouses', ['id' => $warehouse->id]);
    }

    /** @test */
    public function warehouse_active_scope()
    {
        Warehouse::factory()->create(['status' => 1]);
        Warehouse::factory()->create(['status' => 0]);

        $this->assertCount(1, Warehouse::active()->get());
    }

    /** @test */
    public function warehouse_has_city_state_country_relations()
    {
        $warehouse = Warehouse::factory()->create();

        // Relations should not throw; they return null or objects
        $this->assertNull($warehouse->city);   // city_id=1 may not exist in test DB
        $this->assertNull($warehouse->state);
        $this->assertNull($warehouse->country);
    }

    // ─── Store ───────────────────────────────────────────────────────

    /** @test */
    public function store_can_be_created()
    {
        $store = Store::factory()->create();

        $this->assertDatabaseHas('stores', ['id' => $store->id]);
    }

    /** @test */
    public function store_soft_deletes()
    {
        $store = Store::factory()->create();
        $store->delete();

        $this->assertSoftDeleted('stores', ['id' => $store->id]);
    }

    /** @test */
    public function store_active_scope()
    {
        Store::factory()->create(['status' => 1]);
        Store::factory()->create(['status' => 0]);

        $this->assertCount(1, Store::active()->get());
    }

    /** @test */
    public function store_belongs_to_warehouse()
    {
        $warehouse = Warehouse::factory()->create();
        $store = Store::factory()->create(['warehouse_id' => $warehouse->id]);

        $this->assertEquals($warehouse->id, $store->warehouse->id);
    }

    // ─── Auto-audit (created_by / updated_by) ───────────────────────

    /** @test */
    public function models_auto_set_audit_fields_on_create()
    {
        $models = [
            Unit::factory()->create(['unit_name' => 'Audit Unit', 'unit_short_code' => 'AUT']),
            TaxRate::factory()->create(['tax_name' => 'Audit Tax', 'tax_rate' => '3']),
            ItemType::factory()->create(['name' => 'Audit Item']),
            IncomeExpenseType::factory()->create(['name' => 'Audit IE']),
            PartnershipType::factory()->create(['partnership_name' => 'Audit PT']),
            TransportType::factory()->create(['transport_type' => 'Audit TT']),
            PaymentType::factory()->create(['payment_type' => 'Audit Pay', 'slug' => 'audit-pay']),
        ];

        foreach ($models as $model) {
            $this->assertEquals(
                $this->admin->id,
                $model->created_by,
                get_class($model) . ' did not auto-set created_by'
            );
        }
    }
}
