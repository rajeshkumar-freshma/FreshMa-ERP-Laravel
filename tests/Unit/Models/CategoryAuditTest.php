<?php

namespace Tests\Unit\Models;

use App\Models\Admin;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class CategoryAuditTest extends TestCase
{
    use RefreshDatabase;

    public function test_creating_sets_created_by_and_updated_by_from_admin_guard()
    {
        $admin = Admin::factory()->create();
        $this->actingAs($admin, 'admin');

        $category = Category::create([
            'name' => 'Cat '.Str::random(8),
            'slug' => Str::slug('cat-'.Str::random(8)),
        ]);

        $this->assertSame($admin->id, $category->created_by);
        $this->assertSame($admin->id, $category->updated_by);
    }

    public function test_updating_refreshes_updated_by_from_current_admin()
    {
        $creator = Admin::factory()->create();
        $updater = Admin::factory()->create();

        $this->actingAs($creator, 'admin');
        $category = Category::create([
            'name' => 'Cat '.Str::random(8),
            'slug' => Str::slug('cat-'.Str::random(8)),
        ]);

        $this->actingAs($updater, 'admin');
        $category->update(['name' => 'Cat '.Str::random(8)]);

        $this->assertSame($creator->id, $category->fresh()->created_by);
        $this->assertSame($updater->id, $category->fresh()->updated_by);
    }
}

