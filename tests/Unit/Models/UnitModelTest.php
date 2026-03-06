<?php

namespace Tests\Unit\Models;

use App\Models\Admin;
use App\Models\Unit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UnitModelTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $admin = Admin::factory()->create();
        $this->actingAs($admin, 'admin');
    }

    public function test_factory_creates_valid_unit()
    {
        $unit = Unit::factory()->create();

        $this->assertDatabaseHas('units', [
            'id' => $unit->id,
            'unit_name' => $unit->unit_name,
        ]);
    }

    public function test_soft_delete()
    {
        $unit = Unit::factory()->create();
        $unit->delete();

        $this->assertSoftDeleted('units', ['id' => $unit->id]);
    }
}
