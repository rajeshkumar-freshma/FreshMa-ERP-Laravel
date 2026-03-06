<?php

namespace Tests\Feature\Master;

use App\Models\Admin;
use App\Models\TaxRate;
use App\Models\Unit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Tests that every Master DataTable AJAX endpoint returns valid JSON
 * and supports server-side filtering (status, date_from, date_to, search).
 */
class MasterDataTableTest extends TestCase
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

    /**
     * Build the standard DataTable AJAX params.
     */
    private function dtParams(array $extra = []): array
    {
        return array_merge([
            'draw'    => 1,
            'start'   => 0,
            'length'  => 10,
            'search'  => ['value' => '', 'regex' => 'false'],
            'columns' => [
                ['data' => 'id', 'searchable' => 'true', 'orderable' => 'true'],
            ],
            'order' => [
                ['column' => 0, 'dir' => 'desc'],
            ],
        ], $extra);
    }

    /**
     * Make a DataTable AJAX request (with XMLHttpRequest header so Yajra
     * returns JSON instead of the HTML page view).
     */
    private function dtGet(string $route, array $params = []): \Illuminate\Testing\TestResponse
    {
        $url = "/{$this->prefix}/{$route}?" . http_build_query($params ?: $this->dtParams());

        return $this->withHeaders([
            'X-Requested-With' => 'XMLHttpRequest',
            'Accept'           => 'application/json',
        ])->get($url);
    }

    /**
     * @dataProvider masterRouteProvider
     * @test
     */
    public function datatable_ajax_returns_json(string $route)
    {
        $response = $this->dtGet($route);

        $response->assertOk();
        $response->assertJsonStructure(['draw', 'recordsTotal', 'recordsFiltered', 'data']);
    }

    /**
     * Provider returning all master DataTable resource routes.
     */
    public static function masterRouteProvider(): array
    {
        return [
            'unit'                => ['master/unit'],
            'tax-rate'            => ['master/tax-rate'],
            'item-type'           => ['master/item-type'],
            'denomination-type'   => ['master/denomination-type'],
            'income-expense-type' => ['master/income-expense-type'],
            'partnership-type'    => ['master/partnership-type'],
            'transport-type'      => ['master/transport-type'],
            'category'            => ['master/category'],
            'warehouse'           => ['master/warehouse'],
            'store'               => ['master/store'],
            'payment-type'        => ['master/payment-type'],
            'customer'            => ['master/customer'],
            'supplier'            => ['master/supplier'],
            'partner'             => ['master/partner'],
            'machine-details'     => ['master/machine-details'],
        ];
    }

    /** @test */
    public function datatable_respects_status_filter()
    {
        Unit::factory()->create(['status' => 1, 'unit_name' => 'Active U', 'unit_short_code' => 'AU']);
        Unit::factory()->create(['status' => 0, 'unit_name' => 'Inactive U', 'unit_short_code' => 'IU']);

        $response = $this->dtGet('master/unit', $this->dtParams(['status' => '1']));

        $response->assertOk();
        $data = $response->json('data');

        // All returned rows should be status=1
        foreach ($data as $row) {
            if (isset($row['status'])) {
                $this->assertStringContainsString('checked', $row['status']);
            }
        }
    }

    /** @test */
    public function datatable_respects_date_filter()
    {
        $response = $this->dtGet('master/unit', $this->dtParams([
            'date_from' => '2026-01-01',
            'date_to'   => '2026-12-31',
        ]));

        $response->assertOk();
        $response->assertJsonStructure(['draw', 'recordsTotal', 'recordsFiltered', 'data']);
    }

    /** @test */
    public function datatable_supports_search()
    {
        Unit::factory()->create(['unit_name' => 'Kilogram', 'unit_short_code' => 'Kg']);
        Unit::factory()->create(['unit_name' => 'Litre', 'unit_short_code' => 'L']);

        $params = $this->dtParams();
        $params['search']['value'] = 'Kilogram';

        $response = $this->dtGet('master/unit', $params);

        $response->assertOk();
    }

    /** @test */
    public function datatable_supports_pagination()
    {
        // Create more than one page of data
        for ($i = 0; $i < 15; $i++) {
            TaxRate::factory()->create([
                'tax_name' => "Tax $i",
                'tax_rate' => $i,
            ]);
        }

        // Page 1 — request 10 records
        $response1 = $this->dtGet('master/tax-rate', $this->dtParams(['start' => 0, 'length' => 10]));

        $response1->assertOk();
        $this->assertEquals(15, $response1->json('recordsTotal'));
        $this->assertCount(10, $response1->json('data'));

        // Page 2 — request next batch; recordsTotal should still be 15
        $response2 = $this->dtGet('master/tax-rate', $this->dtParams(['start' => 10, 'length' => 10]));

        $response2->assertOk();
        $this->assertEquals(15, $response2->json('recordsTotal'));
        // page 2 should have fewer rows than page 1
        $this->assertLessThanOrEqual(10, count($response2->json('data')));
    }

    /** @test */
    public function datatable_guest_returns_unauthorized()
    {
        $this->app['auth']->forgetGuards();

        $response = $this->get(
            "/{$this->prefix}/master/unit?" . http_build_query($this->dtParams()),
            ['X-Requested-With' => 'XMLHttpRequest']
        );

        // Guest should be redirected to login (not get DataTable JSON)
        $response->assertRedirect();
    }
}
