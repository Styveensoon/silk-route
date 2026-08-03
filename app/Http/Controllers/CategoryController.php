<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class CategoryController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Categories/Index', [
            'categories' => Category::withCount('services')->orderBy('name')->get(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Categories/Create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:80'],
        ]);

        Category::create([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
        ]);

        return redirect()->route('categories.index')->with('success', 'Categoria creada.');
    }

    public function edit(Category $category): Response
    {
        return Inertia::render('Categories/Edit', ['category' => $category]);
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:80'],
        ]);

        $category->update([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
        ]);

        return redirect()->route('categories.index')->with('success', 'Categoria actualizada.');
    }

    public function destroy(Category $category)
    {
        if ($category->services()->exists()) {
            return back()->withErrors(['category' => 'No se puede eliminar una categoria con servicios asociados.']);
        }

        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Categoria eliminada.');
    }
}
