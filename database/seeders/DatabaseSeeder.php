<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin UTP',
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'Freelancer Demo',
            'email' => 'freelancer@example.com',
            'role' => 'freelancer',
        ]);

        User::factory()->create([
            'name' => 'Cliente Demo',
            'email' => 'cliente@example.com',
            'role' => 'cliente',
        ]);

        $this->call([
            CategorySeeder::class,
            ServiceSeeder::class,
            DragQueenServiceSeeder::class,
        ]);
    }
}
