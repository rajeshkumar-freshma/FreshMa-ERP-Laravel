<?php

namespace Database\Factories;

use App\Models\IncomeExpenseType;
use Illuminate\Database\Eloquent\Factories\Factory;

class IncomeExpenseTypeFactory extends Factory
{
    protected $model = IncomeExpenseType::class;

    public function definition()
    {
        return [
            'name'   => $this->faker->unique()->word() . ' Expense',
            'type'   => $this->faker->randomElement([1, 2]),
            'status' => 1,
        ];
    }
}
