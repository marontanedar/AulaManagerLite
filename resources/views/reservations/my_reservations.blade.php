@extends('layouts.app')

@section('content')
<div class="card-panel">
    <div class="section-header">
        <div>
            <h5 class="section-title">Mis Reservas</h5>
            <p class="section-subtitle">Historial de reservas realizadas</p>
        </div>
        <a href="{{ route('reservations.index') }}" class="btn btn-outline-dark btn-sm">← Calendario</a>
    </div>

    <div class="table-responsive">
        <table class="table table-sm table-hover table-bordered m-0">
            <thead>
                <tr>
                    <th class="text-center" style="width:50px;">ID</th>
                    <th>Recurso</th>
                    <th style="width:110px;">Fecha</th>
                    <th style="width:130px;" class="text-center">Horario</th>
                    <th style="width:110px;" class="text-center">Acción</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reservations as $reservation)
                    <tr>
                        <td class="text-center text-muted">{{ $reservation->reservation_id }}</td>
                        <td class="fw-bold text-uppercase">{{ $reservation->resource->name ?? '—' }}</td>
                        <td>{{ \Carbon\Carbon::parse($reservation->date)->format('d/m/Y') }}</td>
                        <td class="text-center fw-bold">
                            {{ substr($reservation->start,0,5) }} — {{ substr($reservation->end,0,5) }}
                        </td>
                        <td class="text-center">
                            <form action="{{ route('reservations.destroy', $reservation->reservation_id) }}"
                                  method="POST"
                                  onsubmit="return confirm('¿Cancelar esta reserva?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger py-0 px-2">
                                    <i class="bi bi-trash"></i> Cancelar
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted text-uppercase" style="font-size:0.75rem;">
                            No tienes reservas activas.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection