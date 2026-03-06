<?php

namespace Tests\Feature\Master;

use App\Models\Admin;
use App\Models\PaymentType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * CRUD tests for PaymentType master module.
 */
class PaymentTypeCrudTest extends TestCase
{
    use RefreshDatabase;

    protected Admin $admin;
    protected string $prefix;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin  = Admin::factory()->create();
        $this->prefix = config('app.admin_prefix', 'rrkadminmanager');
        $this->actingAs($this->admin, 'admin');
    }

    // =====================================================================
    //  AUTH
    // =====================================================================

    /** @test */
    public function guest_cannot_access_payment_type_index()
    {
        $this->app['auth']->forgetGuards();

        $response = $this->get("/{$this->prefix}/master/payment-type");
        $response->assertRedirect();
    }

    // =====================================================================
    //  INDEX / CREATE / EDIT views
    // =====================================================================

    /** @test */
    public function admin_can_view_payment_type_index()
    {
        $response = $this->get("/{$this->prefix}/master/payment-type");
        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_view_payment_type_create_form()
    {
        $response = $this->get("/{$this->prefix}/master/payment-type/create");
        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_view_payment_type_edit_form()
    {
        $pt = PaymentType::factory()->create();

        $response = $this->get("/{$this->prefix}/master/payment-type/{$pt->id}/edit");
        $response->assertStatus(200);
    }

    // =====================================================================
    //  STORE
    // =====================================================================

    /** @test */
    public function admin_can_store_payment_type()
    {
        $response = $this->post("/{$this->prefix}/master/payment-type", [
            'payment_type'     => 'Cash Payment',
            'slug'             => 'cash-payment',
            'payment_category' => 1,
            'status'           => 1,
            'submission_type'  => 1,
        ]);

        $response->assertRedirect(route('admin.payment-type.index'));
        $this->assertDatabaseHas('payment_types', [
            'payment_type' => 'Cash Payment',
            'slug'         => 'cash-payment',
        ]);
    }

    /** @test */
    public function admin_can_store_payment_type_without_slug()
    {
        $response = $this->post("/{$this->prefix}/master/payment-type", [
            'payment_type'     => 'Online Transfer',
            'payment_category' => 2,
            'status'           => 1,
            'submission_type'  => 1,
        ]);

        $response->assertRedirect(route('admin.payment-type.index'));
        $this->assertDatabaseHas('payment_types', ['payment_type' => 'Online Transfer']);
    }

    // =====================================================================
    //  UPDATE
    // =====================================================================

    /** @test */
    public function admin_can_update_payment_type()
    {
        $pt = PaymentType::factory()->create();

        $response = $this->put("/{$this->prefix}/master/payment-type/{$pt->id}", [
            'payment_type'     => 'Updated Payment',
            'slug'             => 'updated-payment',
            'payment_category' => 2,
            'status'           => 1,
            'submission_type'  => 1,
        ]);

        $response->assertRedirect(route('admin.payment-type.index'));
        $this->assertDatabaseHas('payment_types', [
            'id'           => $pt->id,
            'payment_type' => 'Updated Payment',
        ]);
    }

    // =====================================================================
    //  DELETE
    // =====================================================================

    /** @test */
    public function admin_can_delete_payment_type()
    {
        $pt = PaymentType::factory()->create();

        $response = $this->deleteJson("/{$this->prefix}/master/payment-type/{$pt->id}");

        $response->assertOk();
        $response->assertJson(['status' => 200]);
        $this->assertSoftDeleted('payment_types', ['id' => $pt->id]);
    }

    // =====================================================================
    //  VALIDATION
    // =====================================================================

    /** @test */
    public function store_payment_type_requires_name()
    {
        $response = $this->post("/{$this->prefix}/master/payment-type", [
            'payment_type'    => '',
            'status'          => 1,
            'submission_type' => 1,
        ]);

        $response->assertSessionHasErrors('payment_type');
    }

    /** @test */
    public function store_payment_type_requires_status()
    {
        $response = $this->post("/{$this->prefix}/master/payment-type", [
            'payment_type'    => 'Test Payment',
            'submission_type' => 1,
        ]);

        $response->assertSessionHasErrors('status');
    }

    /** @test */
    public function store_payment_type_rejects_duplicate_slug()
    {
        PaymentType::factory()->create(['slug' => 'cash-payment']);

        $response = $this->post("/{$this->prefix}/master/payment-type", [
            'payment_type'    => 'Cash Payment',
            'slug'            => 'cash-payment',
            'status'          => 1,
            'submission_type' => 1,
        ]);

        $response->assertSessionHasErrors('slug');
    }

    /** @test */
    public function update_payment_type_allows_own_slug()
    {
        $pt = PaymentType::factory()->create(['slug' => 'cash-payment']);

        $response = $this->put("/{$this->prefix}/master/payment-type/{$pt->id}", [
            'payment_type'     => 'Cash Payment Edited',
            'slug'             => 'cash-payment',
            'payment_category' => 1,
            'status'           => 1,
            'submission_type'  => 1,
        ]);

        $response->assertRedirect(route('admin.payment-type.index'));
        $this->assertDatabaseHas('payment_types', [
            'id'           => $pt->id,
            'payment_type' => 'Cash Payment Edited',
        ]);
    }
}
