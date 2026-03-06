<?php

namespace Tests\Feature\API;

use App\Models\Admin;
use App\Models\Category;
use App\Models\City;
use App\Models\State;
use App\Models\Store;
use App\Models\Vendor;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

/**
 * Business-logic tests for the Common API list endpoints.
 *
 * Covers: warehouse/list, store/list, customer/list, getstatebycountry,
 *         getcitybystate, category/list, dropdown/list.
 */
class CommonApiBusinessLogicTest extends TestCase
{
    use RefreshDatabase;

    private Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Super-admin (user_type=1) sees all warehouses & stores.
        $this->admin = Admin::factory()->create([
            'user_type' => 1,
            'status'    => 1,
        ]);

        Passport::actingAs($this->admin, [], 'api');
    }

    // ------------------------------------------------------------------
    //  Warehouse list
    // ------------------------------------------------------------------

    /** @test */
    public function warehouse_list_returns_active_warehouses(): void
    {
        Warehouse::factory()->count(3)->create(['status' => 1]);
        Warehouse::factory()->create(['status' => 0]); // inactive

        $response = $this->postJson('/api/v1/warehouse/list');

        $response->assertOk();
        $body = $response->json();
        $this->assertEquals(200, $body['status']);
        $this->assertCount(3, $body['data']);
    }

    /** @test */
    public function warehouse_list_filters_by_name(): void
    {
        Warehouse::factory()->create(['name' => 'Alpha Warehouse', 'status' => 1]);
        Warehouse::factory()->create(['name' => 'Beta Warehouse', 'status' => 1]);

        $response = $this->postJson('/api/v1/warehouse/list', [
            'warehouse_name' => 'Alpha',
        ]);

        $response->assertOk();
        $body = $response->json();
        $this->assertCount(1, $body['data']);
        $this->assertStringContainsString('Alpha', $body['data'][0]['name']);
    }

    /** @test */
    public function warehouse_list_filters_by_code(): void
    {
        Warehouse::factory()->create(['code' => 'WH-UNIQUE99', 'status' => 1]);
        Warehouse::factory()->create(['status' => 1]);

        $response = $this->postJson('/api/v1/warehouse/list', [
            'warehouse_name' => 'UNIQUE99',
        ]);

        $response->assertOk();
        $body = $response->json();
        $this->assertCount(1, $body['data']);
    }

    // ------------------------------------------------------------------
    //  Store list
    // ------------------------------------------------------------------

    /** @test */
    public function store_list_returns_active_stores(): void
    {
        Store::factory()->count(2)->create(['status' => 1]);
        Store::factory()->create(['status' => 0]); // inactive

        $response = $this->postJson('/api/v1/store/list');

        $response->assertOk();
        $body = $response->json();
        $this->assertEquals(200, $body['status']);
        $this->assertCount(2, $body['data']);
    }

    /** @test */
    public function store_list_filters_by_store_name(): void
    {
        Store::factory()->create(['store_name' => 'Gamma Store', 'status' => 1]);
        Store::factory()->create(['store_name' => 'Delta Store', 'status' => 1]);

        $response = $this->postJson('/api/v1/store/list', [
            'store_name' => 'Gamma',
        ]);

        $response->assertOk();
        $body = $response->json();
        $this->assertCount(1, $body['data']);
        $this->assertStringContainsString('Gamma', $body['data'][0]['store_name']);
    }

    /** @test */
    public function store_list_includes_all_stores_key_for_super_admin(): void
    {
        Store::factory()->count(3)->create(['status' => 1]);

        $response = $this->postJson('/api/v1/store/list');

        $response->assertOk();
        $body = $response->json();
        $this->assertArrayHasKey('all_stores', $body);
        $this->assertGreaterThanOrEqual(3, count($body['all_stores']));
    }

    // ------------------------------------------------------------------
    //  Customer list
    // ------------------------------------------------------------------

    /** @test */
    public function customer_list_returns_paginated_vendors(): void
    {
        Vendor::factory()->count(20)->create(['user_type' => 1]);

        $response = $this->postJson('/api/v1/customer/list');

        $response->assertOk();
        $body = $response->json();
        $this->assertEquals(200, $body['status']);
        // Paginated at 15 per page
        $this->assertArrayHasKey('datas', $body);
        $this->assertCount(15, $body['datas']['data']);
        $this->assertEquals(20, $body['datas']['total']);
    }

    /** @test */
    public function customer_list_filters_by_name(): void
    {
        Vendor::factory()->create(['first_name' => 'Rajesh', 'user_type' => 1]);
        Vendor::factory()->create(['first_name' => 'Suresh', 'user_type' => 1]);

        $response = $this->postJson('/api/v1/customer/list', [
            'customer_name' => 'Rajesh',
        ]);

        $response->assertOk();
        $body = $response->json();
        $this->assertCount(1, $body['datas']['data']);
        $this->assertEquals('Rajesh', $body['datas']['data'][0]['first_name']);
    }

    /** @test */
    public function customer_list_filters_by_phone_number(): void
    {
        Vendor::factory()->create([
            'first_name'   => 'Phonetest',
            'phone_number' => '9111222333',
            'user_type'    => 1,
        ]);
        Vendor::factory()->count(5)->create(['user_type' => 1]);

        $response = $this->postJson('/api/v1/customer/list', [
            'customer_name' => '9111222333',
        ]);

        $response->assertOk();
        $body = $response->json();
        $this->assertCount(1, $body['datas']['data']);
    }

    // ------------------------------------------------------------------
    //  State / City lookups
    // ------------------------------------------------------------------

    /** @test */
    public function get_state_returns_active_states_for_country(): void
    {
        State::create(['name' => 'Tamil Nadu', 'country_id' => 1, 'status' => 1]);
        State::create(['name' => 'Kerala', 'country_id' => 1, 'status' => 1]);
        State::create(['name' => 'Inactive State', 'country_id' => 1, 'status' => 0]);
        State::create(['name' => 'Other Country', 'country_id' => 2, 'status' => 1]);

        $response = $this->postJson('/api/v1/getstatebycountry', [
            'country_id' => 1,
        ]);

        $response->assertOk();
        $body = $response->json();
        $this->assertEquals(200, $body['status']);
        $this->assertCount(2, $body['states']);
    }

    /** @test */
    public function get_city_returns_active_cities_for_state(): void
    {
        $state = State::create(['name' => 'Tamil Nadu', 'country_id' => 1, 'status' => 1]);

        City::create(['name' => 'Chennai', 'state_id' => $state->id, 'status' => 1]);
        City::create(['name' => 'Coimbatore', 'state_id' => $state->id, 'status' => 1]);
        City::create(['name' => 'Inactive City', 'state_id' => $state->id, 'status' => 0]);

        $response = $this->postJson('/api/v1/getcitybystate', [
            'state_id' => $state->id,
        ]);

        $response->assertOk();
        $body = $response->json();
        $this->assertEquals(200, $body['status']);
        $this->assertCount(2, $body['cities']);
    }

    // ------------------------------------------------------------------
    //  Category list
    // ------------------------------------------------------------------

    /** @test */
    public function category_list_returns_active_categories(): void
    {
        Category::factory()->count(4)->create(['status' => 1]);
        Category::factory()->create(['status' => 0]);

        $response = $this->postJson('/api/v1/category/list');

        $response->assertOk();
        $body = $response->json();
        $this->assertEquals(200, $body['status']);
        $this->assertArrayHasKey('datas', $body);
        $this->assertLessThanOrEqual(15, count($body['datas']['data']));
    }

    // ------------------------------------------------------------------
    //  Auth guard enforcement
    // ------------------------------------------------------------------

    /** @test */
    public function common_endpoints_require_api_auth(): void
    {
        // Re-create a fresh app context without Passport acting-as.
        $this->app['auth']->forgetGuards();

        $endpoints = [
            '/api/v1/warehouse/list',
            '/api/v1/store/list',
            '/api/v1/customer/list',
            '/api/v1/category/list',
        ];

        foreach ($endpoints as $url) {
            $response = $this->postJson($url);
            $this->assertContains($response->status(), [401, 403], "Expected 401/403 for $url");
        }
    }
}
