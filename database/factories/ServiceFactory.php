<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceFactory extends Factory
{
    /**
     * Puntos de encuentro de ejemplo en Puebla (coordenadas aproximadas).
     * Se guardan directas aqui para que el seeding no dependa de
     * internet ni de la API de Nominatim (eso solo se usa cuando un
     * usuario real publica un servicio desde la app).
     */
    protected array $meetingPoints = [
        ['address' => 'Zocalo de Puebla, Puebla', 'lat' => 19.0433, 'lng' => -98.1982],
        ['address' => 'Angelopolis, Puebla', 'lat' => 19.0086, 'lng' => -98.2519],
        ['address' => 'CU BUAP, Puebla', 'lat' => 19.0000, 'lng' => -98.2063],
        ['address' => 'Universidad Tecnologica de Puebla, Puebla', 'lat' => 19.1002, 'lng' => -98.2809],
    ];

    public function definition(): array
    {
        $point = $this->faker->randomElement($this->meetingPoints);

        return [
            'user_id' => User::factory(),
            'category_id' => Category::query()->inRandomOrder()->value('id'),
            'title' => ucfirst($this->faker->words(4, true)),
            'description' => $this->faker->paragraph(3),
            'price' => $this->faker->randomFloat(2, 100, 2500),
            'status' => 'active',
            'meeting_address' => $point['address'],
            'meeting_lat' => $point['lat'],
            'meeting_lng' => $point['lng'],
        ];
    }
}