<?php

namespace Tests\Feature\API;

use Tests\TestCase;

class AuthGuardsTest extends TestCase
{
    public function test_admin_web_routes_require_auth()
    {
        $response = $this->get('/rrkadminmanager/warehouse-indent-request');
        $response->assertStatus(302); // redirect to login
    }

    public function test_supplier_web_routes_require_auth()
    {
        $response = $this->get('/rrksupplier/order-request');
        $response->assertStatus(302); // redirect to login
    }

    public function test_supplier_api_requires_token()
    {
        $response = $this->postJson('/api/supplier/v1/dashboard', []);
        $response->assertStatus(401);
    }

    public function test_admin_api_requires_token()
    {
        $response = $this->postJson('/api/v1/dashboard', []);
        $response->assertStatus(401);
    }
}
