<?php

namespace Tests\Feature\API;

use App\Models\Admin;
use App\Models\User;
use App\Models\UserAppMenuMapping;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Laravel\Passport\Client;
use Laravel\Passport\Passport;
use Laravel\Passport\PersonalAccessClient;
use Tests\TestCase;

/**
 * Business-logic tests for the Auth API endpoints.
 *
 * Covers: login (email / phone), OTP verification, token save, token verify.
 * Complements the existing contract-level and validation tests.
 */
class AuthApiBusinessLogicTest extends TestCase
{
    use RefreshDatabase;

    private Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a Passport personal-access client so createToken() works.
        $client = Client::forceCreate([
            'name'                   => 'Test Personal Access Client',
            'secret'                 => 'secret',
            'provider'               => 'admins',
            'redirect'               => 'http://localhost',
            'personal_access_client' => true,
            'password_client'        => false,
            'revoked'                => false,
        ]);
        PersonalAccessClient::forceCreate(['client_id' => $client->id]);

        $this->admin = Admin::factory()->create([
            'email'        => 'admin@test.com',
            'phone_number' => '9876543210',
            'password'     => Hash::make('password'),
            'status'       => 1,
            'user_type'    => 1,
        ]);
    }

    // ------------------------------------------------------------------
    //  Login – email / password
    // ------------------------------------------------------------------

    /** @test */
    public function admin_login_succeeds_with_valid_credentials_and_menu_mappings(): void
    {
        // Create required menu mappings (bottom + sidebar)
        UserAppMenuMapping::create([
            'admin_id'      => $this->admin->id,
            'admin_type'    => 1,
            'menu_type'     => 1,
            'app_menu_json' => json_encode(['dashboard']),
            'status'        => 1,
        ]);
        UserAppMenuMapping::create([
            'admin_id'      => $this->admin->id,
            'admin_type'    => 1,
            'menu_type'     => 2,
            'app_menu_json' => json_encode(['settings']),
            'status'        => 1,
        ]);

        $response = $this->postJson('/api/v1/login', [
            'email'    => 'admin@test.com',
            'password' => 'password',
        ]);

        $response->assertOk();
        $body = $response->json();
        $this->assertEquals(200, $body['status']);
        $this->assertArrayHasKey('access_token', $body);
        $this->assertEquals(0, $body['is_supplier']);
        $this->assertStringContainsString('successfully', $body['message']);
    }

    /** @test */
    public function admin_login_returns_400_without_menu_mappings(): void
    {
        // No UserAppMenuMapping records → login should fail with 400
        $response = $this->postJson('/api/v1/login', [
            'email'    => 'admin@test.com',
            'password' => 'password',
        ]);

        $response->assertOk(); // HTTP 200 but status field = 400
        $body = $response->json();
        $this->assertEquals(400, $body['status']);
        $this->assertStringContainsString('Menu mapping', $body['message']);
    }

    /** @test */
    public function admin_login_fails_with_wrong_password(): void
    {
        $response = $this->postJson('/api/v1/login', [
            'email'    => 'admin@test.com',
            'password' => 'wrong-password',
        ]);

        $response->assertOk();
        $body = $response->json();
        $this->assertEquals(400, $body['status']);
    }

    /** @test */
    public function admin_login_with_nonexistent_email_fails(): void
    {
        $response = $this->postJson('/api/v1/login', [
            'email'    => 'nobody@invalid.com',
            'password' => 'password',
        ]);

        $response->assertOk();
        $body = $response->json();
        $this->assertEquals(400, $body['status']);
    }

    // ------------------------------------------------------------------
    //  Login – phone / OTP flow
    // ------------------------------------------------------------------

    /** @test */
    public function login_with_phone_sends_otp_for_active_admin(): void
    {
        // Mock HTTP to prevent real SMS API calls.
        Http::fake(['api.textlocal.in/*' => Http::response(['status' => 'success'], 200)]);

        $response = $this->postJson('/api/v1/login', [
            'phone_number' => '9876543210',
        ]);

        $response->assertOk();
        $body = $response->json();
        $this->assertEquals(200, $body['status']);
        $this->assertArrayHasKey('otp', $body);
        $this->assertEquals('9876543210', $body['phone_number']);

        // OTP should be persisted on the admin record.
        $this->assertNotNull($this->admin->fresh()->otp);
    }

    /** @test */
    public function login_with_phone_blocked_account_returns_400(): void
    {
        $this->admin->update(['status' => 0]);

        $response = $this->postJson('/api/v1/login', [
            'phone_number' => '9876543210',
        ]);

        $response->assertOk();
        $body = $response->json();
        $this->assertEquals(400, $body['status']);
        $this->assertStringContainsString('Blocked', $body['message']);
    }

    /** @test */
    public function login_with_nonexistent_phone_returns_400(): void
    {
        $response = $this->postJson('/api/v1/login', [
            'phone_number' => '0000000000',
        ]);

        $response->assertOk();
        $body = $response->json();
        $this->assertEquals(400, $body['status']);
    }

    // ------------------------------------------------------------------
    //  OTP verification
    // ------------------------------------------------------------------

    /** @test */
    public function verify_otp_with_correct_otp_returns_access_token(): void
    {
        $this->admin->update(['otp' => 123456]);

        $response = $this->postJson('/api/v1/verify-otp', [
            'phone_number' => '9876543210',
            'otp'          => 123456,
        ]);

        $response->assertOk();
        $body = $response->json();
        $this->assertEquals(200, $body['status']);
        $this->assertArrayHasKey('access_token', $body);
        $this->assertArrayHasKey('user', $body);
    }

    /** @test */
    public function verify_otp_with_wrong_otp_returns_403(): void
    {
        $this->admin->update(['otp' => 123456]);

        $response = $this->postJson('/api/v1/verify-otp', [
            'phone_number' => '9876543210',
            'otp'          => 999999,
        ]);

        $response->assertOk();
        $body = $response->json();
        $this->assertEquals(403, $body['status']);
        $this->assertStringContainsString('Invalid', $body['message']);
    }

    /** @test */
    public function verify_otp_with_inactive_account_returns_400(): void
    {
        $this->admin->update(['otp' => 123456, 'status' => 0]);

        $response = $this->postJson('/api/v1/verify-otp', [
            'phone_number' => '9876543210',
            'otp'          => 123456,
        ]);

        $response->assertOk();
        $body = $response->json();
        $this->assertEquals(400, $body['status']);
        $this->assertStringContainsString('Inactive', $body['message']);
    }

    // ------------------------------------------------------------------
    //  Save FCM token (authenticated)
    // ------------------------------------------------------------------

    /** @test */
    public function save_token_updates_admin_fcm_details(): void
    {
        Passport::actingAs($this->admin, [], 'api');

        $response = $this->postJson('/api/v1/save-token', [
            'os'        => 'android',
            'fcmToken'  => 'fcm-token-abc-123',
            'voipToken' => 'voip-token-xyz',
        ]);

        $response->assertOk();
        $body = $response->json();
        $this->assertTrue($body['is_success']);

        $this->admin->refresh();
        $this->assertEquals('android', $this->admin->os);
        $this->assertEquals('fcm-token-abc-123', $this->admin->fcm_token);
        $this->assertEquals('voip-token-xyz', $this->admin->voipToken);
    }

    /** @test */
    public function save_token_requires_authentication(): void
    {
        $response = $this->postJson('/api/v1/save-token', [
            'os'        => '2',
            'fcmToken'  => 'token',
            'voipToken' => 'voip',
        ]);

        $response->assertUnauthorized();
    }

    // ------------------------------------------------------------------
    //  Supplier login flow
    // ------------------------------------------------------------------

    /** @test */
    public function supplier_login_succeeds_with_menu_mappings(): void
    {
        $supplier = User::factory()->create([
            'email'     => 'supplier@test.com',
            'password'  => Hash::make('secret123'),
            'user_type' => 2,
            'status'    => 1,
        ]);

        UserAppMenuMapping::create([
            'admin_id'      => $supplier->id,
            'admin_type'    => 2,
            'menu_type'     => 1,
            'app_menu_json' => json_encode(['orders']),
            'status'        => 1,
        ]);
        UserAppMenuMapping::create([
            'admin_id'      => $supplier->id,
            'admin_type'    => 2,
            'menu_type'     => 2,
            'app_menu_json' => json_encode(['products']),
            'status'        => 1,
        ]);

        $response = $this->postJson('/api/v1/login', [
            'email'    => 'supplier@test.com',
            'password' => 'secret123',
        ]);

        $response->assertOk();
        $body = $response->json();
        $this->assertEquals(200, $body['status']);
        $this->assertEquals(1, $body['is_supplier']);
        $this->assertArrayHasKey('access_token', $body);
    }

    /** @test */
    public function supplier_login_returns_400_without_menu_mappings(): void
    {
        User::factory()->create([
            'email'     => 'supplier2@test.com',
            'password'  => Hash::make('secret123'),
            'user_type' => 2,
            'status'    => 1,
        ]);

        $response = $this->postJson('/api/v1/login', [
            'email'    => 'supplier2@test.com',
            'password' => 'secret123',
        ]);

        $response->assertOk();
        $body = $response->json();
        $this->assertEquals(400, $body['status']);
        $this->assertStringContainsString('Menu mapping', $body['message']);
    }
}
