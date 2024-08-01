<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Account>
 */
class AccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'login' => fake()->name(),
            'password' => fake()->password(),
            'platform_id' => 1,
            'busy' => now(),
            'email' => fake()->email(),
            'passwordEmail' => fake()->password(),
            'comment' => fake()->text(),
            'price' => fake()->numberBetween(20, 200),
            'user_id' => 1,
            //'status' => null
        ];
    }
}
