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
    public function index(Request $request)
    {
        $query = Auth::user()->isAdmin()
            ? Incidence::with(['resource', 'user'])
            : Incidence::with(['resource'])
                ->where('user_id', Auth::id());

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $incidences = $query->latest()->get();

        return view('incidences.index', compact('incidences'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $resources = Resource::where('status', 1)->orderBy('name')->get(); // Solo envío los que están disponibles
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

        $resource = Resource::findOrFail($request->resource_id);
        if ($resource->status !== 1) {
            return back()->withErrors([
                'resource_id' => 'No disponilbe'
            ])->withInput();
        }

        // Validar que no exista una incidencia de ese recurso
        $existResource = Incidence::where('resource_id', $request->resource_id)
            ->where('status', 1)
            ->exists();

        if ($existResource) {
            return back()->withErrors([
                'resource_id' => 'Ya hay una incidencia para este recurso'
            ])->withInput();
        }


        //Create incidence
        Incidence::create([
            'resource_id' => $request->resource_id,
            'user_id' => auth()-> id(),
            'description' => $request->description,
            'date_incidence' => now(),
            'status' => 1,
            'created_by' => auth()->id(),
        ]);

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
        $incidence = Incidence::with(['resource', 'user'])->findOrFail($id);
        return view('incidences.edit', compact('incidence'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Incidence $incidence)
    {
        $request->validate([
            'status' =>  'required|integer|in:1,3',
            'resolution_notes' => 'nullable|string|max:500',
        ]);

        $incidence->update([
            'status'     => $request->status,
            'resolution_notes' => $request->resolution_notes,
            'updated_by' => auth()->id(),
        ]);

        if ($request->status == 3 && $incidence->resource) {
            $incidence->resource->update(['status' => 1]);
        }

        // dd([
        //     'request_status'    => $request->status,
        //     'request_all'       => $request->all(),
        //     'incidence_id'      => $incidence->incidence_id,
        //     'incidence_status'  => $incidence->fresh()->status,  // recarga de BD
        //     'updated_by'        => $incidence->fresh()->updated_by,
        //     'resource_id'       => $incidence->resource_id,
        // ]);

        return redirect()->route('incidences.show', $incidence)->with(['success', 'Incidencia resuelta']);
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
