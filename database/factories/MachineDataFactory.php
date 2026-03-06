<?php

namespace Database\Factories;

use App\Models\MachineData;
use Illuminate\Database\Eloquent\Factories\Factory;

class MachineDataFactory extends Factory
{
    protected $model = MachineData::class;

    public function definition()
    {
        return [
            'MachineName'    => $this->faker->word() . ' Machine',
            'store_id'       => null,
            'Slno'           => 1,
            'Port'           => $this->faker->numberBetween(1000, 9999),
            'IPAddress'      => $this->faker->ipv4(),
            'Capacity'       => $this->faker->numberBetween(100, 1000),
            'Status'         => 1,
            'PLUMasterCode'  => null,
            'Online'         => 1,
        ];
    }

    /**
     * Attach a valid store via factory.
     */
    public function withStore()
    {
        return $this->state(fn () => [
            'store_id' => \App\Models\Store::factory(),
        ]);
    }
}
