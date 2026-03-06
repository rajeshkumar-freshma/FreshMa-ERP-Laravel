<?php

namespace Tests\Feature\API;

use App\Models\Admin;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Passport;
use Tests\TestCase;

/**
 * Business-logic tests for the Customer API CRUD endpoints.
 *
 * Covers: customer/store (create) and customer/update.
 * The CustomerController writes to the `users` table via the Vendor model.
 */
class CustomerApiCrudTest extends TestCase
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

    // ------------------------------------------------------------------
    //  Customer store (create)
    // ------------------------------------------------------------------

    /** @test */
    public function create_customer_with_valid_data_succeeds(): void
    {
        $response = $this->postJson('/api/v1/customer/store', [
            'first_name'   => 'Arun',
            'phone_number' => '9876501234',
            'user_code'    => 'CUST-001',
        ]);

        $response->assertOk();
        $body = $response->json();
        $this->assertEquals(200, $body['status']);
        $this->assertStringContainsString('Added successfully', $body['message']);

        // Verify database record
        $this->assertDatabaseHas('users', [
            'first_name'   => 'Arun',
            'phone_number' => '9876501234',
            'user_code'    => 'CUST-001',
            'user_type'    => 1,
            'status'       => 1,
        ]);
    }

    /** @test */
    public function create_customer_sets_password_as_hashed_phone(): void
    {
        $this->postJson('/api/v1/customer/store', [
            'first_name'   => 'Kumar',
            'phone_number' => '9123456789',
            'user_code'    => 'CUST-KUM',
        ]);

        $customer = Vendor::where('user_code', 'CUST-KUM')->first();
        $this->assertNotNull($customer);
        // Password should be the hashed phone number
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('9123456789', $customer->password));
    }

    /** @test */
    public function create_customer_fails_with_duplicate_phone(): void
    {
        Vendor::factory()->create(['phone_number' => '9111111111', 'user_type' => 1]);

        $response = $this->postJson('/api/v1/customer/store', [
            'first_name'   => 'Duplicate',
            'phone_number' => '9111111111',
            'user_code'    => 'CUST-DUP',
        ]);

        $response->assertOk();
        $body = $response->json();
        $this->assertEquals(422, $body['status']);
        $this->assertStringContainsString('phone number', $body['message']);
    }

    /** @test */
    public function create_customer_fails_with_duplicate_user_code(): void
    {
        Vendor::factory()->create(['user_code' => 'CODE-EXISTS', 'user_type' => 1]);

        $response = $this->postJson('/api/v1/customer/store', [
            'first_name'   => 'Dupe Code',
            'phone_number' => '9222333444',
            'user_code'    => 'CODE-EXISTS',
        ]);

        $response->assertOk();
        $body = $response->json();
        $this->assertEquals(422, $body['status']);
        $this->assertStringContainsString('user code', $body['message']);
    }

    /** @test */
    public function create_customer_fails_without_first_name(): void
    {
        $response = $this->postJson('/api/v1/customer/store', [
            'phone_number' => '9333444555',
            'user_code'    => 'CUST-NONAME',
        ]);

        $response->assertOk();
        $body = $response->json();
        $this->assertEquals(422, $body['status']);
        $this->assertStringContainsString('first name', $body['message']);
    }

    /** @test */
    public function create_customer_fails_with_short_phone_number(): void
    {
        $response = $this->postJson('/api/v1/customer/store', [
            'first_name'   => 'Short',
            'phone_number' => '123',
            'user_code'    => 'CUST-SHORT',
        ]);

        $response->assertOk();
        $body = $response->json();
        $this->assertEquals(422, $body['status']);
    }

    // ------------------------------------------------------------------
    //  Customer update
    // ------------------------------------------------------------------

    /** @test */
    public function update_customer_with_valid_data_succeeds(): void
    {
        $customer = Vendor::factory()->create([
            'first_name'   => 'OldName',
            'phone_number' => '9444555666',
            'user_code'    => 'CUST-UPD',
            'user_type'    => 1,
        ]);

        $response = $this->postJson('/api/v1/customer/update', [
            'id'           => $customer->id,
            'first_name'   => 'NewName',
            'phone_number' => '9444555666',
            'user_code'    => 'CUST-UPD',
        ]);

        $response->assertOk();
        $body = $response->json();
        $this->assertEquals(200, $body['status']);
        $this->assertStringContainsString('Updated', $body['message']);

        $customer->refresh();
        $this->assertEquals('NewName', $customer->first_name);
    }

    /** @test */
    public function update_customer_allows_keeping_same_phone(): void
    {
        $customer = Vendor::factory()->create([
            'phone_number' => '9555666777',
            'user_code'    => 'CUST-SAME',
            'user_type'    => 1,
        ]);

        $response = $this->postJson('/api/v1/customer/update', [
            'id'           => $customer->id,
            'first_name'   => 'Updated',
            'phone_number' => '9555666777',
            'user_code'    => 'CUST-SAME',
        ]);

        $response->assertOk();
        $body = $response->json();
        $this->assertEquals(200, $body['status']);
    }

    /** @test */
    public function update_customer_fails_with_duplicate_phone_of_other_customer(): void
    {
        Vendor::factory()->create([
            'phone_number' => '9666777888',
            'user_type'    => 1,
        ]);

        $customer = Vendor::factory()->create([
            'phone_number' => '9777888999',
            'user_code'    => 'CUST-DPH',
            'user_type'    => 1,
        ]);

        $response = $this->postJson('/api/v1/customer/update', [
            'id'           => $customer->id,
            'first_name'   => 'Clash',
            'phone_number' => '9666777888', // taken
            'user_code'    => 'CUST-DPH',
        ]);

        $response->assertOk();
        $body = $response->json();
        // Update returns error via 'errors' key
        $this->assertArrayHasKey('errors', $body);
    }

    /** @test */
    public function update_customer_updates_password_to_hashed_phone(): void
    {
        $customer = Vendor::factory()->create([
            'phone_number' => '9888999000',
            'user_code'    => 'CUST-PWD',
            'user_type'    => 1,
        ]);

        $this->postJson('/api/v1/customer/update', [
            'id'           => $customer->id,
            'first_name'   => 'Updated',
            'phone_number' => '9777888999',
            'user_code'    => 'CUST-PWD',
        ]);

        $customer->refresh();
        $this->assertTrue(Hash::check('9777888999', $customer->password));
    }

    // ------------------------------------------------------------------
    //  Auth guard enforcement
    // ------------------------------------------------------------------

    /** @test */
    public function customer_endpoints_require_authentication(): void
    {
        $this->app['auth']->forgetGuards();

        $response = $this->postJson('/api/v1/customer/store', [
            'first_name'   => 'NoAuth',
            'phone_number' => '9000000001',
            'user_code'    => 'CUST-NA',
        ]);

        $this->assertContains($response->status(), [401, 403]);
    }
}
