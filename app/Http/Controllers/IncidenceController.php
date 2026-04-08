<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Incidence;
use App\Models\Resource;
use Illuminate\Support\Facades\Auth;

class IncidenceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $incidences = Auth::user()->isAdmin()
            ? Incidence::with(['resource', 'user'])->latest()->get()
            : Incidence::with(['resource'])
                ->where('user_id', Auth::id())
                ->latest()
                ->get();

        return view('incidences.index', compact('incidences'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $resources = Resource::where('status', '=', 1)->get(); // Solo envío los que están disponibles
        return view('incidences.create', compact('resources'));
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
            'resource_id' => 'required|exists:resources,resource_id',
            'description' => 'required|string',
        ]);

        // No es necesario validar porque desde function create estoy enviando 1
        // //Validar que no exista una incidencia de ese recurso
        // $existResource = Incidence::where('resource_id', $request->resource_id)
        //     ->where('status', false)
        //     ->exists();

        // if ($existResource) {
        //     return back()->withErrors([
        //         'resource_id' => 'Ya hay una incidencia para este recurso'
        //     ])->withInput();
        // }


        //Create incidence
        Incidence::create([
            'resource_id' => $request->resource_id,
            'user_id' => auth()-> id(),
            'description' => $request->description,
            'date_incidence' => now(),
            'status' => false,
            'created_by' => auth()->id(),
        ]);

        $resource = Resource::find($request->resource_id);
        $resource->update(['status' => 2]);

        return redirect()->route('incidences.index')->with('info', 'Incidencia reportada');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $incidence = Incidence::with(['resource', 'user', 'updater'])->findOrFail($id);

        // Profesor solo puede ver las suyas
        if (!Auth::user()->isAdmin() && $incidence->user_id !== Auth::id()) {
            abort(403);
        }

        return view('incidences.show', compact('incidence'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
