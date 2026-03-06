<?php

namespace Tests\Feature\Web;

use App\Models\Admin;
use App\Models\Store;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MasterDataTablesRoutesTest extends TestCase
{
    use RefreshDatabase;

    public function test_warehouse_datatable_ajax_returns_success_even_when_location_relations_are_missing()
    {
        $admin = Admin::factory()->create();
        $this->actingAs($admin, 'admin');

        Warehouse::factory()->create([
            'city_id' => 999999,
            'state_id' => 999999,
            'country_id' => 999999,
        ]);

        $this->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->get('/rrkadminmanager/master/warehouse?draw=1&start=0&length=10')
            ->assertStatus(200);
    }

    public function test_store_datatable_ajax_returns_success_even_when_location_relations_are_missing()
    {
        $admin = Admin::factory()->create();
        $this->actingAs($admin, 'admin');

        $warehouse = Warehouse::factory()->create();

        Store::factory()->create([
            'warehouse_id' => $warehouse->id,
            'city_id' => 999999,
            'state_id' => 999999,
            'country_id' => 999999,
        ]);

        $this->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->get('/rrkadminmanager/master/store?draw=1&start=0&length=10')
            ->assertStatus(200);
    }
}
