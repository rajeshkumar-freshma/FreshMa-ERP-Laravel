<?php

namespace Database\Factories;

use App\Models\TaxRate;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaxRateFactory extends Factory
{
    protected $model = TaxRate::class;

    public function definition()
    {
        $admin = \App\Models\Admin::first() ?? \App\Models\Admin::factory()->create();

        return [
            'tax_name' => 'GST',
            'tax_rate' => '5',
            'status' => 1,
            'created_by' => $admin->id,
            'updated_by' => $admin->id,
        ];
    }
}
