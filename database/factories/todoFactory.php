<?php

namespace Database\Factories;

use App\Models\todo;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<todo>
 */
class todoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'is_completed' => fake()->boolean(30),
            'created_at' => fake()->dateTimeBetween('-60 days', 'now'),
        ];
    }
}
