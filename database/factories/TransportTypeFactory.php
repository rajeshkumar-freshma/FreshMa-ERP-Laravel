<?php

namespace Database\Factories;

use App\Models\TransportType;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransportTypeFactory extends Factory
{
    protected $model = TransportType::class;

    public function definition()
    {
        return [
            'transport_type' => $this->faker->unique()->word() . ' Transport',
            'status'         => 1,
        ];
    }
}
