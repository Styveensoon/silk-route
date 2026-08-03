<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Service;
use App\Models\User;
use App\Repositories\ServiceRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class ServiceService
{
    public function __construct(
        protected ServiceRepositoryInterface $repository
    ) {}

    public function listActiveServices(?int $categoryId = null): Collection
    {
        return $this->repository->all($categoryId);
    }

    public function categories(): Collection
    {
        return Category::orderBy('name')->get();
    }

    public function publish(array $data): Service
    {
        $data['user_id'] = Auth::id() ?? User::query()->value('id');
        $data['status'] = 'active';
        $data = $this->attachCoordinates($data);

        return $this->repository->create($data);
    }

    public function updateService(Service $service, array $data): Service
    {
        $data = $this->attachCoordinates($data);

        return $this->repository->update($service, $data);
    }

    /**
     * Consumo del Web Service de terceros: OpenStreetMap Nominatim.
     * Convierte la direccion en texto (meeting_address) a coordenadas
     * lat/lng. No requiere API key. Si Nominatim no encuentra la
     * direccion o falla, se guarda el servicio igual pero sin
     * coordenadas (el mapa del frontend simplemente no se muestra).
     */
    protected function attachCoordinates(array $data): array
    {
        $address = $data['meeting_address'] ?? null;

        if (! $address) {
            return $data;
        }

        $response = Http::withHeaders([
            'User-Agent' => 'SilkRoad-UTP/1.0 (proyecto academico UTP, Desarrollo Web Integral)',
        ])->get('https://nominatim.openstreetmap.org/search', [
            'q' => $address,
            'format' => 'json',
            'limit' => 1,
        ]);

        $result = $response->successful() ? ($response->json()[0] ?? null) : null;

        $data['meeting_lat'] = $result['lat'] ?? null;
        $data['meeting_lng'] = $result['lon'] ?? null;

        return $data;
    }
}
