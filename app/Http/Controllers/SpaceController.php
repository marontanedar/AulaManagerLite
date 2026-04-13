<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Space;
use App\Models\Category;

class SpaceController extends Controller
{
    public function index()
    {
        $spaces = Space::with('category')->orderBy('name')->get();
        return view('spaces.index', compact('spaces'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('spaces.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,category_id',
            'capacity'    => 'required|integer|min:1',
            'status'      => 'required|integer|in:1,2,3',
            'description' => 'nullable|string',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['updated_by'] = auth()->id();

        Space::create($validated);

        return redirect()->route('spaces.index')
            ->with('success', 'Espacio creado correctamente.');
    }

    public function show(Space $space)
    {
        $space->load('category', 'reservations.user', 'reservations.resources');
        return view('spaces.show', compact('space'));
    }

    public function edit(Space $space)
    {
        $categories = Category::orderBy('name')->get();
        return view('spaces.edit', compact('space', 'categories'));
    }

    public function update(Request $request, Space $space)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,category_id',
            'capacity'    => 'required|integer|min:1',
            'status'      => 'required|integer|in:1,2,3',
            'description' => 'nullable|string',
        ]);

        $validated['updated_by'] = auth()->id();

        $space->update($validated);

        return redirect()->route('spaces.index')
            ->with('success', 'Espacio actualizado correctamente.');
    }

    public function destroy(Space $space)
    {
        $totalReservations = $space->reservations()->count();

        if ($totalReservations > 0) {
            return redirect()->route('spaces.index')
                ->withErrors([
                    'msg' => "No se puede eliminar \"{$space->name}\": tiene {totalReservations} reservas activas"
                ]);
        }
        $space->delete();

        return redirect()->route('spaces.index')
            ->with('success', 'Espacio eliminado correctamente.');
    }
}
