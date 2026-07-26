<?php

namespace Database\Seeders;

use App\Models\tags;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create default user if not exists
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
            ]
        );

        // Seed default tags
        $defaultTags = ['Work', 'Personal', 'Urgent', 'Feature', 'Bug', 'Shopping'];
        foreach ($defaultTags as $tagName) {
            tags::firstOrCreate(['name' => $tagName]);
        }

        // Seed 100 dummy to-dos
        $this->call(TodoSeeder::class);
    }
}
