<?php

namespace Database\Factories;

use App\Models\PaymentType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PaymentTypeFactory extends Factory
{
    protected $model = PaymentType::class;

    public function definition()
    {
        $name = $this->faker->unique()->word() . ' Payment';

        return [
            'payment_type'     => $name,
            'slug'             => Str::slug($name),
            'payment_category' => $this->faker->randomElement([1, 2]),
            'status'           => 1,
        ];
    }
}
