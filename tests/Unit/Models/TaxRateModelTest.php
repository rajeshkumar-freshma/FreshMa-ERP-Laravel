<?php

namespace Tests\Unit\Models;

use App\Models\Admin;
use App\Models\TaxRate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaxRateModelTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $admin = Admin::factory()->create();
        $this->actingAs($admin, 'admin');
    }

    public function test_factory_creates_valid_tax_rate()
    {
        $tax = TaxRate::factory()->create();

        $this->assertDatabaseHas('tax_rates', [
            'id' => $tax->id,
            'tax_name' => $tax->tax_name,
        ]);
    }

    public function test_soft_delete()
    {
        $tax = TaxRate::factory()->create();
        $tax->delete();

        $this->assertSoftDeleted('tax_rates', ['id' => $tax->id]);
    }
}
