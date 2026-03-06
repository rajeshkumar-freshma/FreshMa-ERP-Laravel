<?php

namespace Database\Factories;

use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class VendorFactory extends Factory
{
    protected $model = Vendor::class;

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
     * Create a customer (user_type = 3).
     */
    public function customer()
    {
        return $this->state(fn () => ['user_type' => 3]);
    }
}
