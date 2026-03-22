<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use App\Models\Category;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Carbon\Carbon;

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
            $q->where('date_reservation', $date);
        }])
        ->when($request->category, function ($query, $categoryId) {
            return $query->where('category_id', $categoryId);
        })
        ->get();

        return view('reservations.index', compact('categories', 'hours', 'resources', 'date'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'resource_id' => 'required|exists:resources,resource_id',
            'date_reservation' => 'required|date',
            'start_time' => 'required', // Formato H:i desde el modal
            'end_time' => 'required|after:start_time',
        ]);

        // Validación de solapamiento (Overlap)
        $overlap = Reservation::where('resource_id', $request->resource_id)
            ->where('date_reservation', $request->date_reservation)
            ->where(function ($q) use ($request) {
                $q->whereBetween('start_time', [$request->start_time, $request->end_time])
                  ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                  ->orWhere(function ($q2) use ($request) {
                      $q2->where('start_time', '<=', $request->start_time)
                         ->where('end_time', '>=', $request->end_time);
                  });
            })->exists();

        if ($overlap) {
            return back()->withErrors(['msg' => 'Ya existe una reserva que choca con este horario.']);
        }

        Reservation::create([
            'user_id' => auth()->id(),
            'resource_id' => $request->resource_id,
            'date_reservation' => $request->date_reservation,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'remarks' => $request->remarks
        ]);

        return back()->with('success', '¡Reserva guardada con éxito!');
    }
}
