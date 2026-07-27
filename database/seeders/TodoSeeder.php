<?php

namespace Database\Seeders;

use App\Models\tags;
use App\Models\todo;
use App\Models\User;
use Illuminate\Database\Seeder;

class TodoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
            ]
        );

        $tags = tags::all();

        todo::factory()
            ->count(100)
            ->create([
                'user_id' => $user->id,
            ])
            ->each(function (todo $todo) use ($tags) {
                if ($tags->isNotEmpty()) {
                    $todo->tags()->attach(
                        $tags->random(rand(0, min(3, $tags->count())))->pluck('id')
                    );
                }
            });
    }
}
