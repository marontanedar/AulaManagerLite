<?php

namespace App\Http\Controllers;

use App\Models\Space;
use App\Models\Resource;
use App\Models\Category;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AuditLog;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        // $categories = Category::all();
        $date = $request->get('date', date('Y-m-d'));
        // $categoryId = $request->get('category');

        $hours = [];
        for ($i = 8; $i <= 21; $i++) {
            $hours[] = sprintf('%02d:00', $i);
        }

        // Se muestran todos los espacios disponibles
        $spaces = Space::with(['category', 'reservations' => function ($q) use ($date) {
                $q->where('date', $date)->with('user');
        }])
            ->where('status', 1)
            ->orderBy('name')
            ->get();

        $selectedSpace = null;
        if ($request->has('space')) {
            $selectedSpace = Space::with([
                'category',
                'reservations' => fn($q) => $q->where('date', $date)->with(['user', 'resources']),
            ])->find($request->get('space'));
        }

        $resources = Resource::where('status', 1)
            ->with('category')
            ->orderBy('name')
            ->get();

        return view('reservations.index', compact(
            'hours',
            'spaces',
            'resources',
            'date',
            'selectedSpace'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'space_id'       => 'required|exists:spaces,space_id',
            'date'           => 'required|date',
            'start'          => 'required|date_format:H:i',
            'end'            => 'required|date_format:H:i|after:start',
            'notes'          => 'nullable|string|max:500',
            'resource_ids'   => 'nullable|array',
            'resource_ids.*' => 'exists:resources,resource_id',
        ]);

        // Validación de solapamiento
        $exists = Reservation::where('space_id', $request->space_id)
            ->where('date', $request->date)
            ->where(function ($q) use ($request) {
                $q->where('start', '<', $request->end)
                  ->where('end', '>', $request->start);
            })->exists();

        if ($exists) {
            return back()->withErrors(['msg' => 'Ya existe una reserva que choca con este horario.']);
        }

        $reservation = Reservation::create([
            'user_id'  => Auth::id(),
            'space_id' => $request->space_id,
            'date'     => $request->date,
            'start'    => $request->start,
            'end'      => $request->end,
            'notes'    => $request->notes,
        ]);

        if ($request->filled('resource_ids')) {
            $reservation->resources()->attach($request->resource_ids);
        }

        AuditLog::record(
            'reserved',
            'Reservation',
            $reservation->reservation_id,
            'Reserva de ' . $reservation->space->name . ' el ' . $reservation->date
        );

        return back()->with('success', '¡Reserva guardada con éxito!');
    }

    public function myReservations()
    {
        $reservations = Auth::user()->reservations()
            ->with(['space', 'space.category', 'resources'])
            ->where('date', '>=', today()) // ← solo presentes y futuras
            ->orderBy('date', 'asc')
            ->orderBy('start', 'asc')
            ->get();

        return view('reservations.my_reservations', compact('reservations'));
    }

    public function destroy(Reservation $reservation)
    {
        if ($reservation->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            return back()->withErrors(['msg' => 'Sin permisos para cancelar esta reserva.']);
        }

        $reservation->resources()->detach();
        $reservation->delete();

        return back()->with('success', 'Reserva cancelada.');
    }
}