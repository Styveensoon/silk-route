<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreServiceRequest;
use App\Models\Service;
use App\Services\ServiceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class ServiceController extends Controller
{
    public function __construct(
        protected ServiceService $serviceService
    ) {}

    public function index(Request $request): Response
    {
        $categoryId = $request->integer('category') ?: null;

        return Inertia::render('Services/Index', [
            'services' => $this->serviceService->listActiveServices($categoryId),
            'categories' => $this->serviceService->categories(),
            'activeCategory' => $categoryId,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Services/Create', [
            'categories' => $this->serviceService->categories(),
        ]);
    }

    public function store(StoreServiceRequest $request)
    {
        $this->serviceService->publish($request->validated());

        return redirect()->route('services.index')
            ->with('success', 'Servicio publicado correctamente.');
    }

    public function edit(Service $service): Response
    {
        Gate::authorize('update', $service);

        return Inertia::render('Services/Edit', [
            'service' => $service,
            'categories' => $this->serviceService->categories(),
        ]);
    }

    public function update(StoreServiceRequest $request, Service $service)
    {
        Gate::authorize('update', $service);

        $this->serviceService->updateService($service, $request->validated());

        return redirect()->route('services.index')
            ->with('success', 'Servicio actualizado correctamente.');
    }

    public function destroy(Service $service)
    {
        Gate::authorize('delete', $service);

        $service->delete();

        return redirect()->route('services.index')
            ->with('success', 'Servicio eliminado.');
    }
}
