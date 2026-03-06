<?php

namespace Tests\Feature\API;

use App\Models\Admin;
use App\Models\Store;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

/**
 * Business-logic tests for the Dashboard API endpoint.
 *
 * The dashboard aggregates CashRegister, Sales, Purchase, and User data.
 * These tests verify the JSON structure and basic aggregation logic.
 */
class DashboardApiBusinessLogicTest extends TestCase
{
    use RefreshDatabase;

    private Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = Admin::factory()->create([
            'user_type' => 1,
            'status'    => 1,
        ]);

        Passport::actingAs($this->admin, [], 'api');
    }

    /** @test */
    public function dashboard_returns_expected_json_structure(): void
    {
        // Create minimal supporting data so the query doesn't fail.
        Warehouse::factory()->create(['status' => 1]);
        Store::factory()->create(['status' => 1]);

        $response = $this->postJson('/api/v1/dashboard');

        $response->assertOk();
        $body = $response->json();
        $this->assertEquals(200, $body['status']);
        $this->assertStringContainsString($this->admin->first_name, $body['message']);

        // Verify core structure keys
        $this->assertArrayHasKey('data', $body);
        $this->assertArrayHasKey('sales_order', $body);
        $this->assertArrayHasKey('purchase_order', $body);
        $this->assertArrayHasKey('incomeexpense', $body);

        // Verify nested data keys
        $data = $body['data'];
        $this->assertArrayHasKey('payment_details', $data);
        $this->assertArrayHasKey('sales_orders_total_amount', $data);
        $this->assertArrayHasKey('sales_orders_count', $data);
        $this->assertArrayHasKey('purchase_orders_total_amount', $data);
        $this->assertArrayHasKey('purchase_orders_count', $data);
        $this->assertArrayHasKey('customer_count', $data);
        $this->assertArrayHasKey('supplier_count', $data);
    }

    /** @test */
    public function dashboard_includes_12_months_chart_data(): void
    {
        $response = $this->postJson('/api/v1/dashboard');

        $response->assertOk();
        $body = $response->json();

        $salesOrder    = $body['sales_order'];
        $purchaseOrder = $body['purchase_order'];

        $this->assertArrayHasKey('months', $salesOrder);
        $this->assertArrayHasKey('count', $salesOrder);
        $this->assertCount(12, $salesOrder['months']);
        $this->assertCount(12, $salesOrder['count']);

        $this->assertArrayHasKey('months', $purchaseOrder);
        $this->assertArrayHasKey('count', $purchaseOrder);
        $this->assertCount(12, $purchaseOrder['months']);
        $this->assertCount(12, $purchaseOrder['count']);
    }

    /** @test */
    public function dashboard_counts_customers_and_suppliers(): void
    {
        // user_type=1 → customers, user_type=2 → suppliers  (User model / users table)
        for ($i = 0; $i < 3; $i++) {
            \App\Models\User::factory()->create([
                'user_type'    => 1,
                'status'       => 1,
                'phone_number' => '91000000' . (10 + $i),
                'email'        => "cust{$i}@test.com",
            ]);
        }
        for ($i = 0; $i < 2; $i++) {
            \App\Models\User::factory()->create([
                'user_type'    => 2,
                'status'       => 1,
                'phone_number' => '92000000' . (10 + $i),
                'email'        => "sup{$i}@test.com",
            ]);
        }

        $response = $this->postJson('/api/v1/dashboard');

        $response->assertOk();
        $body = $response->json();
        $this->assertEquals(3, $body['data']['customer_count']);
        $this->assertEquals(2, $body['data']['supplier_count']);
    }

    /** @test */
    public function dashboard_includes_branch_wise_reports(): void
    {
        Store::factory()->create(['status' => 1]);
        Warehouse::factory()->create(['status' => 1]);

        $response = $this->postJson('/api/v1/dashboard');

        $response->assertOk();
        $body = $response->json();
        $data = $body['data'];

        $this->assertArrayHasKey('storeWiseSalesOrdersCount', $data);
        $this->assertArrayHasKey('storeWiseProductTransferCount', $data);
        $this->assertArrayHasKey('branchwiseIncomeAndExpenseData', $data);
    }

    /** @test */
    public function dashboard_returns_zero_totals_when_no_orders_exist(): void
    {
        $response = $this->postJson('/api/v1/dashboard');

        $response->assertOk();
        $body = $response->json();

        $this->assertEquals(0, $body['data']['sales_orders_total_amount']);
        $this->assertEquals(0, $body['data']['sales_orders_count']);
        $this->assertEquals(0, $body['data']['purchase_orders_total_amount']);
        $this->assertEquals(0, $body['data']['purchase_orders_count']);
    }

    /** @test */
    public function dashboard_requires_authentication(): void
    {
        $this->app['auth']->forgetGuards();

        $response = $this->postJson('/api/v1/dashboard');

        $this->assertContains($response->status(), [401, 403]);
    }
}
