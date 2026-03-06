<?php

namespace Tests\Unit\Models;

use App\Models\Admin;
use App\Models\ItemType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemTypeModelTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $admin = Admin::factory()->create();
        $this->actingAs($admin, 'admin');
    }

    public function test_factory_creates_valid_item_type()
    {
        $itemType = ItemType::factory()->create();

        $this->assertDatabaseHas('item_types', [
            'id' => $itemType->id,
            'name' => $itemType->name,
        ]);
    }

    public function test_soft_delete()
    {
        $itemType = ItemType::factory()->create();
        $itemType->delete();

        $this->assertSoftDeleted('item_types', ['id' => $itemType->id]);
    }
}
