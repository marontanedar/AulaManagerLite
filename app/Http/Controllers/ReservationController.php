<?php

namespace App\Http\Controllers;

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
        $categories = Category::all();

        $date = $request->get('date', date('Y-m-d'));

        $hours = [];
        $apertura = 8;
        $cierre = 21;

        for ($i = $apertura; $i <= $cierre; $i++) {
            $hours[] = sprintf('%02d:00', $i);
        }

        $resources = Resource::with(['reservations' => function ($q) use ($date) {
            $q->where('date', $date);
        }])
        ->when($request->category, function ($q, $categoryId) {
            return $q->where('category_id', $categoryId);
        })
        ->get();

        return view('reservations.index', compact('categories', 'hours', 'resources', 'date'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'resource_id'  => 'required|exists:resources,resource_id',
            'date'         => 'required|date',
            'start'        => 'required|date_format:H:i', // Formato H:i desde el modal
            'end'          => 'required|date_format:H:i|after:start',
        ]);

        // Validación de solapamiento (Overlap)
         $exists = Reservation::where('resource_id', $request->resource_id)
            ->where('date', $request->date)
            ->where(function ($q) use ($request) {
                $q->where('start', '<', $request->end)
                  ->where('end', '>', $request->start);
            })->exists();

        if ($exists) {
            return back()->withErrors(['msg' => 'Ya existe una reserva que choca con este horario.']);
        }

        $reservation = Reservation::create([
            'user_id'     => auth()->id(),
            'resource_id' => $request->resource_id,
            'date'        => $request->date,
            'start'       => $request->start,
            'end'         => $request->end,
        ]);

        AuditLog::record(
            'reserved',
            'Reservation',
            $reservation->reservation_id,
            'Reserva de ' . $reservation->resource->name . ' el ' . $reservation->date
        );

        return back()->with('success', '¡Reserva guardada con éxito!');
    }

    public function myReservations()
    {
        // if (!Auth::check()) {
        //     return redirect()->route('login');
        // }
        $reservations = Auth::user()->reservations()->with('resource')->orderBy('date', 'desc')->get();
        return view('reservations.my_reservations', compact('reservations'));
    }

    public function destroy(Reservation $reservation)
    {
        if ($reservation->user_id !== Auth::id()) {
            return back()->withErrors(['msg' => 'Sin permisos para cancelar esta reserva']);
        }

        $reservation->delete();

        return back()->with('success', 'Reserva cancelada');
    }
}
