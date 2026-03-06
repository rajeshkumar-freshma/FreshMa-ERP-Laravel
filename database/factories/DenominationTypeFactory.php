<?php

namespace Database\Factories;

use App\Models\DenominationType;
use Illuminate\Database\Eloquent\Factories\Factory;

class DenominationTypeFactory extends Factory
{
    protected $model = DenominationType::class;

    public function definition()
    {
        return [
            'type'        => $this->faker->randomElement([1, 2]),
            'value'       => $this->faker->randomElement([1, 2, 5, 10, 20, 50, 100, 500, 2000]),
            'description' => $this->faker->sentence(3),
        ];
    }
}
