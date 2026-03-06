<?php

namespace Database\Factories;

use App\Models\PartnershipType;
use Illuminate\Database\Eloquent\Factories\Factory;

class PartnershipTypeFactory extends Factory
{
    protected $model = PartnershipType::class;

    public function definition()
    {
        return [
            'partnership_name'       => $this->faker->unique()->word() . ' Partnership',
            'partnership_percentage' => $this->faker->randomFloat(2, 1, 50),
            'status'                 => 1,
        ];
    }
}
