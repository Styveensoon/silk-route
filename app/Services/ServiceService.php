<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Service;
use App\Models\User;
use App\Repositories\ServiceRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class ServiceService
{
    public function __construct(
        protected ServiceRepositoryInterface $repository
    ) {}

    public function listActiveServices(): Collection
    {
        return $this->repository->all();
    }

    public function categories(): Collection
    {
        return Category::orderBy('name')->get();
    }

    public function publish(array $data): Service
    {
        $data['user_id'] = Auth::id() ?? User::query()->value('id');
        $data['status'] = 'active';

        return $this->repository->create($data);
    }

    public function updateService(Service $service, array $data): Service
    {
        return $this->repository->update($service, $data);
    }
}
