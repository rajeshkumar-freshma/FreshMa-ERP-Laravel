<?php

namespace Database\Factories;

use App\Models\Admin;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

class UnitFactory extends Factory
{
    protected $model = Unit::class;

    public function definition()
    {
        $admin = Admin::first() ?? Admin::factory()->create();

        return [
            'unit_name' => 'Kilogram',
            'unit_short_code' => 'Kg',
            'base_unit' => 'Kilo',
            'allow_decimal' => 2,
            'operator' => '*',
            'operation_value' => 1,
            'status' => 1,
            'created_by' => $admin->id,
            'updated_by' => $admin->id,
        ];
    }
}
