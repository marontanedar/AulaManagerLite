{{-- resources/views/reservations/my_reservations.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container py-4">

    <div class="section-header mb-4">
        <h1 class="section-title">Mis reservas</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success py-2">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger py-2">{{ $errors->first() }}</div>
    @endif

    @forelse($reservations as $reservation)
    <div class="card-panel mb-3">
        <div class="d-flex align-items-start justify-content-between gap-3">

            {{-- Info principal --}}
            <div class="d-flex flex-column gap-1">
                <div class="d-flex align-items-center gap-2">
                    <span class="fw-500" style="font-size:0.9rem">
                        {{ $reservation->space->name ?? '—' }}
                    </span>
                    @if($reservation->space?->category)
                        <span class="badge-status" style="background:#f1efe8;color:#5f5e5a;font-size:0.68rem;padding:2px 8px;border-radius:20px">
                            {{ $reservation->space->category->name }}
                        </span>
                    @endif
                </div>

                <div style="font-size:0.78rem;color:#888">
                    {{ \Carbon\Carbon::parse($reservation->date)->isoFormat('dddd, D MMMM YYYY') }}
                    &middot;
                    {{ substr($reservation->start, 0, 5) }} – {{ substr($reservation->end, 0, 5) }}
                </div>

                @if($reservation->notes)
                <div style="font-size:0.78rem;color:#555;margin-top:2px">
                    {{ $reservation->notes }}
                </div>
                @endif

                {{-- Recursos asociados --}}
                @if($reservation->resources->count())
                <div class="d-flex flex-wrap gap-1 mt-1">
                    @foreach($reservation->resources as $resource)
                    <span style="font-size:0.7rem;padding:2px 8px;border-radius:20px;border:0.5px solid #ddd;color:#666">
                        {{ $resource->name }}
                    </span>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- Acciones --}}
            <div class="d-flex flex-column align-items-end gap-2">
                {{-- Badge estado del espacio --}}
                @php $status = $reservation->space?->status ?? 1; @endphp
                @if($status === 2)
                    <span class="badge-status badge-mantenimiento">Mantenimiento</span>
                @elseif($status === 3)
                    <span class="badge-status badge-averiado">Fuera de servicio</span>
                @else
                    <span class="badge-status badge-disponible">Confirmada</span>
                @endif

                {{-- Cancelar --}}
                @if(\Carbon\Carbon::parse($reservation->date)->isFuture() || \Carbon\Carbon::parse($reservation->date)->isToday())
                <form method="POST"
                      action="{{ route('reservations.destroy', $reservation->reservation_id) }}"
                      onsubmit="return confirm('¿Seguro que quieres cancelar esta reserva?')"
                      class="mb-0">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="btn btn-sm"
                            style="font-size:0.72rem;padding:3px 10px;color:#a32d2d;border:0.5px solid #f09595">
                        Cancelar reserva
                    </button>
                </form>
                @else
                    <span style="font-size:0.72rem;color:#bbb">Pasada</span>
                @endif
            </div>

        </div>
    </div>
    @empty
        <div class="card-panel text-center py-5">
            <p style="color:#aaa;font-size:0.85rem">No tienes reservas activas.</p>
            <a href="{{ route('reservations.index') }}" class="btn btn-sm btn-dark mt-2">
                Ver espacios disponibles
            </a>
        </div>
    @endforelse

</div>
@endsection