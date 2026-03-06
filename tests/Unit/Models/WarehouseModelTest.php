<?php

namespace Tests\Unit\Models;

use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WarehouseModelTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $admin = \App\Models\Admin::factory()->create();
        $this->actingAs($admin, 'admin');
    }

    public function test_factory_creates_valid_warehouse()
    {
        $warehouse = Warehouse::factory()->create();

        $this->assertDatabaseHas('warehouses', [
            'id' => $warehouse->id,
            'name' => $warehouse->name,
        ]);

        $this->assertNotNull($warehouse->created_by_details);
    }

    public function test_soft_delete()
    {
        $warehouse = Warehouse::factory()->create();
        $warehouse->delete();

        $this->assertSoftDeleted('warehouses', ['id' => $warehouse->id]);
    }
}
