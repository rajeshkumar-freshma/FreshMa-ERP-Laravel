<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        $name = $this->faker->unique()->words(3, true);
        $admin = \App\Models\Admin::first() ?? \App\Models\Admin::factory()->create();
        $itemType = \App\Models\ItemType::factory()->create([
            'created_by' => $admin->id,
            'updated_by' => $admin->id,
        ]);
        $taxRate = \App\Models\TaxRate::factory()->create([
            'created_by' => $admin->id,
            'updated_by' => $admin->id,
        ]);
        return [
            'name' => $name,
            'slug' => Str::slug($name . '-' . $this->faker->unique()->numerify('###')),
            'sku_code' => $this->faker->unique()->bothify('SKU###??'),
            'hsn_code' => $this->faker->bothify('HSN####'),
            'product_description' => $this->faker->sentence,
            'item_type_id' => $itemType->id,
            'unit_id' => Unit::factory(),
            'tax_type' => 1,
            'tax_id' => $taxRate->id,
            'status' => 1,
            'created_by' => $admin->id,
            'updated_by' => $admin->id,
        ];
    }
}
