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

        $hours = [];
        $inicio = 8;
        $fin = 20;

        for ($i = $inicio; $i <= $fin; $i++) {
            $hours[] = sprintf('%02d:00', $i);
        }

        $query = Resource::query();
        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }
        $resources = $query->get();

        return view('reservations.index', compact('categories', 'hours', 'resources'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'resource_id' => 'required|exists:resources,resource_id',
            'date_reservation' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        $exists = Reservation::where('resource_id', $request->resource_id)
            ->where('date_reservation', $request->date_reservation)
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                      ->orWhereBetween('end_time', [$request->start_time, $request->end_time]);
            })->exists();

        if ($exists) {
            return back()->withErrors(['overloop' => 'El recurso ya está ocupado en ese intervalo exacto.']);
        }

        Reservation::create([
            'user_id' => auth()->id(),
            'resource_id' => $request->resource_id,
            'date_reservation' => $request->date_reservation,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'remarks' => $request->remarks,
        ]);

        return back()->with('success', 'Reserva realizada con éxito.');
    }
}
