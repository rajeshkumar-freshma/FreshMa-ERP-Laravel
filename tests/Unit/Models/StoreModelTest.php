<?php

namespace Tests\Unit\Models;

use App\Models\Store;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreModelTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $admin = \App\Models\Admin::factory()->create();
        $this->actingAs($admin, 'admin');
    }

    public function test_factory_creates_valid_store()
    {
        $store = Store::factory()->create();

        $this->assertDatabaseHas('stores', [
            'id' => $store->id,
            'store_name' => $store->store_name,
        ]);

        $this->assertNotNull($store->warehouse);
    }

    public function test_soft_delete()
    {
        $store = Store::factory()->create();
        $store->delete();

        $this->assertSoftDeleted('stores', ['id' => $store->id]);
    }
}
