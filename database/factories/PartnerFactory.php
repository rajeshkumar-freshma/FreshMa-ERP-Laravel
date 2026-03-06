<?php

namespace Database\Factories;

use App\Models\Partner;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PartnerFactory extends Factory
{
    protected $model = Partner::class;

    public function definition()
    {
        return [
            'first_name'        => $this->faker->firstName,
            'last_name'         => $this->faker->lastName,
            'email'             => $this->faker->unique()->safeEmail,
            'phone_number'      => $this->faker->numerify('9#########'),
            'user_type'         => 1,
            'password'          => Hash::make('password'),
            'email_verified_at' => now(),
            'phone_verified_at' => now(),
            'api_token'         => Hash::make('api-token'),
            'remember_token'    => Str::random(10),
            'status'            => 1,
        ];
    }

    /**
     * Manager partner (user_type = 2).
     */
    public function manager()
    {
        return $this->state(fn () => ['user_type' => 2]);
    }

    /**
     * Partner partner (user_type = 3).
     */
    public function partner()
    {
        return $this->state(fn () => ['user_type' => 3]);
    }
}
