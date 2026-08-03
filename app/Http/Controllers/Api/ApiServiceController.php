<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreServiceRequest;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use App\Services\ServiceService;
use Illuminate\Support\Facades\Gate;

class ApiServiceController extends Controller
{
    public function __construct(
        protected ServiceService $serviceService
    ) {}

    public function index()
    {
        $services = Service::with(['user', 'category'])
            ->where('status', 'active')
            ->latest()
            ->get();

        return ServiceResource::collection($services);
    }

    public function show(Service $service)
    {
        return new ServiceResource($service->load(['user', 'category']));
    }

    public function store(StoreServiceRequest $request)
    {
        $service = $this->serviceService->publish($request->validated());

        return (new ServiceResource($service))->response()->setStatusCode(201);
    }

    public function update(StoreServiceRequest $request, Service $service)
    {
        Gate::authorize('update', $service);

        $updated = $this->serviceService->updateService($service, $request->validated());

        return new ServiceResource($updated);
    }

    public function destroy(Service $service)
    {
        Gate::authorize('delete', $service);

        $service->delete();

        return response()->json(null, 204);
    }
}
