<?php

namespace Database\Factories;

use App\Models\ItemType;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemTypeFactory extends Factory
{
    protected $model = ItemType::class;

    public function definition()
    {
        $admin = \App\Models\Admin::first() ?? \App\Models\Admin::factory()->create();

        return [
            'name' => 'Physical',
            'status' => 1,
            'created_by' => $admin->id,
            'updated_by' => $admin->id,
        ];
    }
}
