<?php

namespace Tests\Unit\Models;

use App\Models\Admin;
use App\Models\Adjustment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class AdjustmentAuditTest extends TestCase
{
    use RefreshDatabase;

    public function test_creating_sets_created_by_and_updated_by_from_admin_guard()
    {
        $admin = Admin::factory()->create();
        $this->actingAs($admin, 'admin');

        $adjustment = Adjustment::create([
            'adjustment_track_number' => 'ADJ-'.Str::upper(Str::random(10)),
        ]);

        $this->assertSame($admin->id, $adjustment->created_by);
        $this->assertSame($admin->id, $adjustment->updated_by);
    }

    public function test_updating_sets_updated_by_from_current_admin()
    {
        $creator = Admin::factory()->create();
        $updater = Admin::factory()->create();

        $this->actingAs($creator, 'admin');
        $adjustment = Adjustment::create([
            'adjustment_track_number' => 'ADJ-'.Str::upper(Str::random(10)),
            'remarks' => 'first',
        ]);

        $this->actingAs($updater, 'admin');
        $adjustment->update(['remarks' => 'second']);

        $this->assertSame($creator->id, $adjustment->fresh()->created_by);
        $this->assertSame($updater->id, $adjustment->fresh()->updated_by);
    }
}

