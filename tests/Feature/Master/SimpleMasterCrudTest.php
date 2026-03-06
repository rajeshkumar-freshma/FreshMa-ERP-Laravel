<?php

namespace Tests\Feature\Master;

use App\Models\Admin;
use App\Models\Category;
use App\Models\DenominationType;
use App\Models\IncomeExpenseType;
use App\Models\ItemType;
use App\Models\PartnershipType;
use App\Models\TaxRate;
use App\Models\TransportType;
use App\Models\Unit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Feature tests for all simple Master CRUD controllers.
 *
 * Covers: Unit, TaxRate, ItemType, DenominationType, IncomeExpenseType,
 *         PartnershipType, TransportType, Category.
 */
class SimpleMasterCrudTest extends TestCase
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
    //  UNIT
    // =====================================================================

    /** @test */
    public function guest_cannot_access_unit_index()
    {
        $this->app['auth']->forgetGuards();

        $response = $this->get("/{$this->prefix}/master/unit");
        $response->assertRedirect();
    }

    /** @test */
    public function admin_can_view_unit_index()
    {
        $response = $this->get("/{$this->prefix}/master/unit");
        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_view_unit_create_form()
    {
        $response = $this->get("/{$this->prefix}/master/unit/create");
        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_store_unit()
    {
        $response = $this->post("/{$this->prefix}/master/unit", [
            'unit_name'       => 'Kilogram',
            'unit_short_code' => 'Kg',
            'base_unit'       => 'Kilo',
            'allow_decimal'   => 2,
            'operator'        => '*',
            'operation_value' => 1,
            'status'          => 1,
            'default'         => 1,
            'submission_type' => 1,
        ]);

        $response->assertRedirect(route('admin.unit.index'));
        $this->assertDatabaseHas('units', ['unit_name' => 'Kilogram']);
    }

    /** @test */
    public function admin_can_view_unit_edit_form()
    {
        $unit = Unit::factory()->create();

        $response = $this->get("/{$this->prefix}/master/unit/{$unit->id}/edit");
        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_update_unit()
    {
        $unit = Unit::factory()->create();

        $response = $this->put("/{$this->prefix}/master/unit/{$unit->id}", [
            'unit_name'       => 'Gram',
            'unit_short_code' => 'g',
            'base_unit'       => 'Gram',
            'allow_decimal'   => 3,
            'operator'        => '/',
            'operation_value' => 1000,
            'status'          => 1,
            'default'         => 0,
            'submission_type' => 1,
        ]);

        $response->assertRedirect(route('admin.unit.index'));
        $this->assertDatabaseHas('units', ['id' => $unit->id, 'unit_name' => 'Gram']);
    }

    /** @test */
    public function admin_can_delete_unit()
    {
        $unit = Unit::factory()->create();

        $response = $this->deleteJson("/{$this->prefix}/master/unit/{$unit->id}");

        $response->assertOk();
        $response->assertJson(['status' => 200]);
        $this->assertSoftDeleted('units', ['id' => $unit->id]);
    }

    /** @test */
    public function admin_can_view_unit_show()
    {
        $unit = Unit::factory()->create();

        $response = $this->get("/{$this->prefix}/master/unit/{$unit->id}");
        $response->assertStatus(200);
    }

    // =====================================================================
    //  TAX RATE
    // =====================================================================

    /** @test */
    public function guest_cannot_access_tax_rate_index()
    {
        $this->app['auth']->forgetGuards();

        $response = $this->get("/{$this->prefix}/master/tax-rate");
        $response->assertRedirect();
    }

    /** @test */
    public function admin_can_view_tax_rate_index()
    {
        $response = $this->get("/{$this->prefix}/master/tax-rate");
        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_store_tax_rate()
    {
        $response = $this->post("/{$this->prefix}/master/tax-rate", [
            'tax_name'        => 'GST 18%',
            'tax_rate'        => '18',
            'status'          => 1,
            'submission_type' => 1,
        ]);

        $response->assertRedirect(route('admin.tax-rate.index'));
        $this->assertDatabaseHas('tax_rates', ['tax_name' => 'GST 18%']);
    }

    /** @test */
    public function admin_can_update_tax_rate()
    {
        $taxRate = TaxRate::factory()->create();

        $response = $this->put("/{$this->prefix}/master/tax-rate/{$taxRate->id}", [
            'tax_name'        => 'GST 28%',
            'tax_rate'        => '28',
            'status'          => 1,
            'submission_type' => 1,
        ]);

        $response->assertRedirect(route('admin.tax-rate.index'));
        $this->assertDatabaseHas('tax_rates', ['id' => $taxRate->id, 'tax_name' => 'GST 28%']);
    }

    /** @test */
    public function admin_can_delete_tax_rate()
    {
        $taxRate = TaxRate::factory()->create();

        $response = $this->deleteJson("/{$this->prefix}/master/tax-rate/{$taxRate->id}");

        $response->assertOk();
        $response->assertJson(['status' => 200]);
        $this->assertSoftDeleted('tax_rates', ['id' => $taxRate->id]);
    }

    // =====================================================================
    //  ITEM TYPE
    // =====================================================================

    /** @test */
    public function guest_cannot_access_item_type_index()
    {
        $this->app['auth']->forgetGuards();

        $response = $this->get("/{$this->prefix}/master/item-type");
        $response->assertRedirect();
    }

    /** @test */
    public function admin_can_view_item_type_index()
    {
        $response = $this->get("/{$this->prefix}/master/item-type");
        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_store_item_type()
    {
        $response = $this->post("/{$this->prefix}/master/item-type", [
            'name'            => 'Physical',
            'status'          => 1,
            'submission_type' => 1,
        ]);

        $response->assertRedirect(route('admin.item-type.index'));
        $this->assertDatabaseHas('item_types', ['name' => 'Physical']);
    }

    /** @test */
    public function admin_can_update_item_type()
    {
        $itemType = ItemType::factory()->create();

        $response = $this->put("/{$this->prefix}/master/item-type/{$itemType->id}", [
            'name'            => 'Digital',
            'status'          => 1,
            'submission_type' => 1,
        ]);

        $response->assertRedirect(route('admin.item-type.index'));
        $this->assertDatabaseHas('item_types', ['id' => $itemType->id, 'name' => 'Digital']);
    }

    /** @test */
    public function admin_can_delete_item_type()
    {
        $itemType = ItemType::factory()->create();

        $response = $this->deleteJson("/{$this->prefix}/master/item-type/{$itemType->id}");

        $response->assertOk();
        $response->assertJson(['status' => 200]);
        $this->assertSoftDeleted('item_types', ['id' => $itemType->id]);
    }

    // =====================================================================
    //  DENOMINATION TYPE
    // =====================================================================

    /** @test */
    public function admin_can_view_denomination_type_index()
    {
        $response = $this->get("/{$this->prefix}/master/denomination-type");
        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_store_denomination_type()
    {
        $response = $this->post("/{$this->prefix}/master/denomination-type", [
            'denomination_value' => 500,
            'description'        => 'Five Hundred',
        ]);

        $response->assertRedirect(route('admin.denomination-type.index'));
        $this->assertDatabaseHas('denomination_types', ['value' => 500]);
    }

    /** @test */
    public function admin_can_update_denomination_type()
    {
        $denom = DenominationType::factory()->create();

        $response = $this->put("/{$this->prefix}/master/denomination-type/{$denom->id}", [
            'denomination_value' => 1000,
            'description'        => 'One Thousand',
        ]);

        $response->assertRedirect(route('admin.denomination-type.index'));
        $this->assertDatabaseHas('denomination_types', ['id' => $denom->id, 'value' => 1000]);
    }

    /** @test */
    public function admin_can_delete_denomination_type()
    {
        $denom = DenominationType::factory()->create();

        $response = $this->deleteJson("/{$this->prefix}/master/denomination-type/{$denom->id}");

        $response->assertOk();
        $response->assertJson(['status' => 200]);
        $this->assertSoftDeleted('denomination_types', ['id' => $denom->id]);
    }

    // =====================================================================
    //  INCOME EXPENSE TYPE
    // =====================================================================

    /** @test */
    public function admin_can_view_income_expense_type_index()
    {
        $response = $this->get("/{$this->prefix}/master/income-expense-type");
        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_store_income_expense_type()
    {
        $response = $this->post("/{$this->prefix}/master/income-expense-type", [
            'name'            => 'Electricity',
            'type'            => 1,
            'status'          => 1,
            'submission_type' => 1,
        ]);

        $response->assertRedirect(route('admin.income-expense-type.index'));
        $this->assertDatabaseHas('income_expense_types', ['name' => 'Electricity']);
    }

    /** @test */
    public function admin_can_update_income_expense_type()
    {
        $type = IncomeExpenseType::factory()->create();

        $response = $this->put("/{$this->prefix}/master/income-expense-type/{$type->id}", [
            'name'            => 'Water Bill',
            'type'            => 2,
            'status'          => 1,
            'submission_type' => 1,
        ]);

        $response->assertRedirect(route('admin.income-expense-type.index'));
        $this->assertDatabaseHas('income_expense_types', ['id' => $type->id, 'name' => 'Water Bill']);
    }

    /** @test */
    public function admin_can_delete_income_expense_type()
    {
        $type = IncomeExpenseType::factory()->create();

        $response = $this->deleteJson("/{$this->prefix}/master/income-expense-type/{$type->id}");

        $response->assertOk();
        $response->assertJson(['status' => 200]);
        $this->assertSoftDeleted('income_expense_types', ['id' => $type->id]);
    }

    // =====================================================================
    //  PARTNERSHIP TYPE
    // =====================================================================

    /** @test */
    public function admin_can_view_partnership_type_index()
    {
        $response = $this->get("/{$this->prefix}/master/partnership-type");
        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_store_partnership_type()
    {
        $response = $this->post("/{$this->prefix}/master/partnership-type", [
            'partnership_name'       => 'Equity',
            'partnership_percentage' => 25,
            'status'                 => 1,
            'submission_type'        => 1,
        ]);

        $response->assertRedirect(route('admin.partnership-type.index'));
        $this->assertDatabaseHas('partnership_types', ['partnership_name' => 'Equity']);
    }

    /** @test */
    public function admin_can_update_partnership_type()
    {
        $type = PartnershipType::factory()->create();

        $response = $this->put("/{$this->prefix}/master/partnership-type/{$type->id}", [
            'partnership_name'       => 'Revenue Share',
            'partnership_percentage' => 50,
            'status'                 => 1,
            'submission_type'        => 1,
        ]);

        $response->assertRedirect(route('admin.partnership-type.index'));
        $this->assertDatabaseHas('partnership_types', ['id' => $type->id, 'partnership_name' => 'Revenue Share']);
    }

    /** @test */
    public function admin_can_delete_partnership_type()
    {
        $type = PartnershipType::factory()->create();

        $response = $this->deleteJson("/{$this->prefix}/master/partnership-type/{$type->id}");

        $response->assertOk();
        $response->assertJson(['status' => 200]);
        $this->assertSoftDeleted('partnership_types', ['id' => $type->id]);
    }

    // =====================================================================
    //  TRANSPORT TYPE
    // =====================================================================

    /** @test */
    public function admin_can_view_transport_type_index()
    {
        $response = $this->get("/{$this->prefix}/master/transport-type");
        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_store_transport_type()
    {
        $response = $this->post("/{$this->prefix}/master/transport-type", [
            'transport_type'  => 'Truck',
            'status'          => 1,
            'submission_type' => 1,
        ]);

        $response->assertRedirect(route('admin.transport-type.index'));
        $this->assertDatabaseHas('transport_types', ['transport_type' => 'Truck']);
    }

    /** @test */
    public function admin_can_update_transport_type()
    {
        $type = TransportType::factory()->create();

        $response = $this->put("/{$this->prefix}/master/transport-type/{$type->id}", [
            'transport_type'  => 'Ship',
            'status'          => 1,
            'submission_type' => 1,
        ]);

        $response->assertRedirect(route('admin.transport-type.index'));
        $this->assertDatabaseHas('transport_types', ['id' => $type->id, 'transport_type' => 'Ship']);
    }

    /** @test */
    public function admin_can_delete_transport_type()
    {
        $type = TransportType::factory()->create();

        $response = $this->deleteJson("/{$this->prefix}/master/transport-type/{$type->id}");

        $response->assertOk();
        $response->assertJson(['status' => 200]);
        $this->assertSoftDeleted('transport_types', ['id' => $type->id]);
    }

    // =====================================================================
    //  CATEGORY
    // =====================================================================

    /** @test */
    public function admin_can_view_category_index()
    {
        $response = $this->get("/{$this->prefix}/master/category");
        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_view_category_create_form()
    {
        $response = $this->get("/{$this->prefix}/master/category/create");
        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_store_category()
    {
        $response = $this->post("/{$this->prefix}/master/category", [
            'name'            => 'Electronics',
            'slug'            => 'electronics',
            'status'          => 1,
            'is_featured'     => 0,
            'submission_type' => 1,
        ]);

        $response->assertRedirect(route('admin.category.index'));
        $this->assertDatabaseHas('categories', ['name' => 'Electronics']);
    }

    /** @test */
    public function admin_can_store_child_category()
    {
        $parent = Category::factory()->create(['is_featured' => 0]);

        $response = $this->post("/{$this->prefix}/master/category", [
            'name'            => 'Smartphones',
            'slug'            => 'smartphones',
            'parent_id'       => $parent->id,
            'status'          => 1,
            'is_featured'     => 0,
            'submission_type' => 1,
        ]);

        $response->assertRedirect(route('admin.category.index'));
        $this->assertDatabaseHas('categories', ['name' => 'Smartphones', 'parent_id' => $parent->id]);
    }

    /** @test */
    public function admin_can_update_category()
    {
        $category = Category::factory()->create(['is_featured' => 0]);

        $response = $this->put("/{$this->prefix}/master/category/{$category->id}", [
            'name'            => 'Updated Category',
            'slug'            => 'updated-category',
            'status'          => 1,
            'is_featured'     => 0,
            'submission_type' => 1,
        ]);

        $response->assertRedirect(route('admin.category.index'));
        $this->assertDatabaseHas('categories', ['id' => $category->id, 'name' => 'Updated Category']);
    }

    /** @test */
    public function admin_can_delete_category()
    {
        $category = Category::factory()->create(['is_featured' => 0]);

        $response = $this->deleteJson("/{$this->prefix}/master/category/{$category->id}");

        $response->assertOk();
        $response->assertJson(['status' => 200]);
        $this->assertSoftDeleted('categories', ['id' => $category->id]);
    }
}
