<?php

namespace App\Repositories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Collection;

class EloquentServiceRepository implements ServiceRepositoryInterface
{
    public function all(?int $categoryId = null): Collection
    {
        return Service::with(['user', 'category'])
            ->where('status', 'active')
            ->when($categoryId, fn ($query) => $query->where('category_id', $categoryId))
            ->latest()
            ->get();
    }

    public function create(array $data): Service
    {
        return Service::create($data);
    }

    public function update(Service $service, array $data): Service
    {
        $service->update($data);

        return $service->fresh();
    }
}
