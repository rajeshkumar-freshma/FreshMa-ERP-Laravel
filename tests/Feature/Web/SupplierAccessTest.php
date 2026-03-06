<?php

namespace Tests\Feature\Web;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SupplierAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_supplier_route()
    {
        $response = $this->get('/rrksupplier/order-request');
        $response->assertStatus(302);
    }

    public function test_supplier_user_can_access_supplier_route()
    {
        $supplier = User::factory()->create();

        $response = $this->actingAs($supplier)
            ->get('/rrksupplier/order-request');

        $response->assertStatus(200);
    }
}
