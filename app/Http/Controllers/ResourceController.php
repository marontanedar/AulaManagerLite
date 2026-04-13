<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Resource;
use Illuminate\Http\Request;

class ResourceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Resource::with(['category', 'creator']);

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $resources = $query->orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        return view('resources.index', compact('resources', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('resources.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:50',
            'category_id' => 'required|exists:categories,category_id',
            'status'      => 'required|in:1,2,3',
            'description' => 'nullable|string|max:500',
        ]);

        Resource::create([
            'name'        => $request->name,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'status'      => $request->status,
            'created_by'  => auth()->id(),
        ]);

        return redirect()->route('resources.index')->with('success', 'Recurso creado.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $resource = Resource::with([
            'category',
            'creator',
            'incidences',
            'reservations' => fn($q) => $q->where('date', '>=', today())
                                         ->orderBy('date')
                                         ->orderBy('start')
                                         ->with('space', 'user'),
        ])->findOrFail($id);

        return view('resources.show', compact('resource'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $resource = Resource::findOrFail($id);
        $categories = Category::orderBy('name')->get();
        return view('resources.edit', compact("resource", 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,category_id',
            'status'      => 'required|in:1,2,3',
            'description' => 'nullable|string|max:500',
        ]);

        $resource = Resource::findOrFail($id);

        $resource->update([
            'name'        => trim($request->name),
            'description' => $request->description,
            'category_id' => $request->category_id,
            'status'      => $request->status,
            'updated_by'  => auth()->id(),
        ]);

        return redirect()->route('resources.index')->with('success', 'Recurso actualizado.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $resource = Resource::findOrFail($id);

        //Bloquear si tiene reservas futuras asignadas
        $futuras = $resource->reservations()->count();

        if ($futuras > 0) {
            return back()->with(
                'error',
                "No se puede elimina \"{$resource->name}\" tiene {$futuras} reservas asignadas"
            );
        }
        $resource->delete();

        return redirect()->route('resources.index')->with('success', 'Recurso eliminado');
    }
}
