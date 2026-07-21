<?php

namespace Database\Factories;

use App\Models\tags;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<tags>
 */
class tagsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->word(),
        ];
    }
}
