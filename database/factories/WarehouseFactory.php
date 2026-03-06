<?php

namespace Database\Factories;

use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class WarehouseFactory extends Factory
{
    protected $model = Warehouse::class;

    public function definition()
    {
        $admin = \App\Models\Admin::first() ?? \App\Models\Admin::factory()->create();

        return [
            'name' => $this->faker->company . ' Warehouse',
            'slug' => Str::slug($this->faker->unique()->company . '-wh'),
            'code' => 'WH-' . $this->faker->unique()->numerify('######'),
            'phone_number' => $this->faker->numerify('9#########'),
            'email' => $this->faker->unique()->safeEmail,
            'start_date' => now()->subYear(),
            'address' => $this->faker->streetAddress,
            'city_id' => 1,
            'state_id' => 1,
            'country_id' => 1,
            'pincode' => $this->faker->postcode,
            'longitude' => $this->faker->longitude,
            'latitude' => $this->faker->latitude,
            'status' => 1,
            'created_by' => $admin->id,
            'updated_by' => $admin->id,
        ];
    }
}
