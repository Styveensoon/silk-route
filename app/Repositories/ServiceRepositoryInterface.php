<?php

namespace App\Repositories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Collection;

interface ServiceRepositoryInterface
{
    public function all(): Collection;

    public function create(array $data): Service;

    public function update(Service $service, array $data): Service;
}
