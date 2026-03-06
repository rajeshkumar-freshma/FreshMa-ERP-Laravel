<?php

namespace Tests\Feature\Master;

use App\Models\Admin;
use App\Models\Category;
use App\Models\DenominationType;
use App\Models\IncomeExpenseType;
use App\Models\ItemType;
use App\Models\MachineData;
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

/**
 * Tests the generic MasterStatusController (POST master/status-change)
 * that handles status toggling for ALL master entities.
 */
class MasterStatusToggleTest extends TestCase
{
    use RefreshDatabase;

    protected Admin $admin;
    protected string $url;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = Admin::factory()->create();
        $this->url   = route('admin.master.statuschange');

        // Authenticate admin so model boot events (resolveActorId) use correct ID
        $this->actingAs($this->admin, 'admin');
    }

    // ─── Helpers ─────────────────────────────────────────────────────

    private function toggleStatus(string $entity, int $id, int $statusValue): \Illuminate\Testing\TestResponse
    {
        return $this->actingAs($this->admin, 'admin')
            ->postJson($this->url, [
                'entity'       => $entity,
                'id'           => $id,
                'status_value' => $statusValue,
            ]);
    }

    // ─── Tests ───────────────────────────────────────────────────────

    /** @test */
    public function guest_cannot_toggle_status()
    {
        $unit = Unit::factory()->create(['status' => 1]);

        $this->app['auth']->forgetGuards();

        $response = $this->postJson($this->url, [
            'entity'       => 'unit',
            'id'           => $unit->id,
            'status_value' => 0,
        ]);

        $response->assertUnauthorized();
    }

    /** @test */
    public function rejects_invalid_entity()
    {
        $response = $this->toggleStatus('nonexistent_entity', 1, 0);

        $response->assertOk();
        $response->assertJson(['status' => 400]);
    }

    /** @test */
    public function rejects_missing_fields()
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->postJson($this->url, []);

        $response->assertStatus(422);
    }

    /** @test */
    public function can_toggle_unit_status()
    {
        $unit = Unit::factory()->create(['status' => 1]);

        $response = $this->toggleStatus('unit', $unit->id, 0);

        $response->assertJson(['status' => 200]);
        $this->assertDatabaseHas('units', ['id' => $unit->id, 'status' => 0]);
    }

    /** @test */
    public function can_toggle_category_status()
    {
        $category = Category::factory()->create(['status' => 1]);

        $response = $this->toggleStatus('category', $category->id, 0);

        $response->assertJson(['status' => 200]);
        $this->assertDatabaseHas('categories', ['id' => $category->id, 'status' => 0]);
    }

    /** @test */
    public function can_toggle_tax_rate_status()
    {
        $taxRate = TaxRate::factory()->create(['status' => 1]);

        $response = $this->toggleStatus('tax_rate', $taxRate->id, 0);

        $response->assertJson(['status' => 200]);
        $this->assertDatabaseHas('tax_rates', ['id' => $taxRate->id, 'status' => 0]);
    }

    /** @test */
    public function can_toggle_item_type_status()
    {
        $itemType = ItemType::factory()->create(['status' => 1]);

        $response = $this->toggleStatus('item_type', $itemType->id, 0);

        $response->assertJson(['status' => 200]);
        $this->assertDatabaseHas('item_types', ['id' => $itemType->id, 'status' => 0]);
    }

    // Note: denomination_type is skipped — the table has no 'status' column.

    /** @test */
    public function can_toggle_income_expense_type_status()
    {
        $type = IncomeExpenseType::factory()->create(['status' => 1]);

        $response = $this->toggleStatus('income_expense_type', $type->id, 0);

        $response->assertJson(['status' => 200]);
        $this->assertDatabaseHas('income_expense_types', ['id' => $type->id, 'status' => 0]);
    }

    /** @test */
    public function can_toggle_partnership_type_status()
    {
        $type = PartnershipType::factory()->create(['status' => 1]);

        $response = $this->toggleStatus('partnership_type', $type->id, 0);

        $response->assertJson(['status' => 200]);
        $this->assertDatabaseHas('partnership_types', ['id' => $type->id, 'status' => 0]);
    }

    /** @test */
    public function can_toggle_transport_type_status()
    {
        $type = TransportType::factory()->create(['status' => 1]);

        $response = $this->toggleStatus('transport_type', $type->id, 0);

        $response->assertJson(['status' => 200]);
        $this->assertDatabaseHas('transport_types', ['id' => $type->id, 'status' => 0]);
    }

    /** @test */
    public function can_toggle_payment_type_status()
    {
        $type = PaymentType::factory()->create(['status' => 1]);

        $response = $this->toggleStatus('payment_type', $type->id, 0);

        $response->assertJson(['status' => 200]);
        $this->assertDatabaseHas('payment_types', ['id' => $type->id, 'status' => 0]);
    }

    /** @test */
    public function can_toggle_store_status()
    {
        $store = Store::factory()->create(['status' => 1]);

        $response = $this->toggleStatus('store', $store->id, 0);

        $response->assertJson(['status' => 200]);
        $this->assertDatabaseHas('stores', ['id' => $store->id, 'status' => 0]);
    }

    /** @test */
    public function can_toggle_status_back_to_active()
    {
        $unit = Unit::factory()->create(['status' => 0, 'unit_name' => 'Off Unit', 'unit_short_code' => 'OFF']);

        $response = $this->toggleStatus('unit', $unit->id, 1);

        $response->assertJson(['status' => 200]);
        $this->assertDatabaseHas('units', ['id' => $unit->id, 'status' => 1]);
    }
}
