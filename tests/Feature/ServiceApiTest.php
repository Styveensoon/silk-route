<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ServiceApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_services_index_is_public_and_returns_json(): void
    {
        $response = $this->getJson('/api/services');

        $response->assertStatus(200);
        $response->assertJsonStructure(['data']);
    }

    public function test_creating_a_service_requires_authentication(): void
    {
        $category = Category::create(['name' => 'Tutorias', 'slug' => 'tutorias']);

        $response = $this->postJson('/api/services', [
            'title' => 'Clases de fisica',
            'description' => 'Asesoria de fisica para primer semestre.',
            'price' => 200,
            'category_id' => $category->id,
            'meeting_address' => 'Zocalo de Puebla, Puebla',
        ]);

        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_create_a_service_via_api(): void
    {
        Http::fake([
            'nominatim.openstreetmap.org/*' => Http::response([
                ['lat' => '19.0433', 'lon' => '-98.1982'],
            ], 200),
        ]);

        $user = User::factory()->create(['role' => 'freelancer']);
        $category = Category::create(['name' => 'Tutorias', 'slug' => 'tutorias']);

        Sanctum::actingAs($user);

        $response = $this->postJson('/api/services', [
            'title' => 'Clases de fisica',
            'description' => 'Asesoria de fisica para primer semestre.',
            'price' => 200,
            'category_id' => $category->id,
            'meeting_address' => 'Zocalo de Puebla, Puebla',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('services', ['title' => 'Clases de fisica']);
    }
}
