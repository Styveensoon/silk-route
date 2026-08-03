<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        collect([
            'Tutorias',
            'Programacion',
            'Diseno grafico',
            'Redaccion y traduccion',
            'Fotografia y video',
            'Musica',
        ])->each(fn (string $name) => Category::create([
            'name' => $name,
            'slug' => Str::slug($name),
        ]));
    }
}
