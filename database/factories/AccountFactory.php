<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'balance' => 0,
            'account_number'  => $this->faker->unique()->numberBetween(1000009, 9999999),
            'user_id' => $this->faker->numberBetween(1, User::count()),
        ];
    }
}
