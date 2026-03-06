<?php

namespace Database\Factories;

use App\Models\Store;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class StoreFactory extends Factory
{
    protected $model = Store::class;

    public function definition()
    {
        $admin = \App\Models\Admin::first() ?? \App\Models\Admin::factory()->create();

        return [
            'store_name' => $this->faker->company . ' Store',
            'slug' => Str::slug($this->faker->unique()->company . '-store'),
            'store_code' => 'ST' . $this->faker->unique()->numerify('######'),
            'warehouse_id' => Warehouse::factory(),
            'phone_number' => $this->faker->numerify('9#########'),
            'email' => $this->faker->unique()->safeEmail,
            'start_date' => now()->subMonths(3),
            'gst_number' => $this->faker->bothify('##????####'),
            'address' => $this->faker->address,
            'city_id' => 1,
            'state_id' => 1,
            'country_id' => 1,
            'pincode' => $this->faker->postcode,
            'status' => 1,
            'created_by' => $admin->id,
            'updated_by' => $admin->id,
        ];
    }
}
