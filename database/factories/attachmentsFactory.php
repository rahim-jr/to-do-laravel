<?php

namespace Database\Factories;

use App\Models\attachments;
use App\Models\todo;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class attachmentsFactory extends Factory
{
    protected $model = attachments::class;

    public function definition(): array
    {
        return [
            'file_path' => $this->faker->word(),
            'original_name' => $this->faker->name(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'todo_id' => todo::factory(),
        ];
    }
}
