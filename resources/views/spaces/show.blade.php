@extends('layouts.app')

@section('content')
<div class="container py-4" style="max-width:760px">

    <div class="section-header mb-4">
        <a href="{{ route('spaces.index') }}"
           style="font-size:0.78rem;color:#888;text-decoration:none">
            ← Volver a espacios
        </a>
        <div class="d-flex align-items-center justify-content-between mt-1">
            <h1 class="section-title mb-0">{{ $space->name }}</h1>
            <a href="{{ route('spaces.edit', $space->space_id) }}"
               class="btn btn-sm btn-outline-secondary" style="font-size:0.75rem">
                Editar
            </a>
        </div>
    </div>

    {{-- Info general --}}
    <div class="card-panel mb-3">
        <div class="row g-3">
            <div class="col-sm-4">
                <p class="form-label-upper mb-1">Categoría</p>
                <p style="font-size:0.85rem;color:#111">{{ $space->category?->name ?? '—' }}</p>
            </div>
            <div class="col-sm-4">
                <p class="form-label-upper mb-1">Capacidad</p>
                <p style="font-size:0.85rem;color:#111">{{ $space->capacity }} personas</p>
            </div>
            <div class="col-sm-4">
                <p class="form-label-upper mb-1">Estado</p>
                @if($space->status === 1)
                    <span class="badge-status badge-disponible">Disponible</span>
                @elseif($space->status === 2)
                    <span class="badge-status badge-mantenimiento">Mantenimiento</span>
                @else
                    <span class="badge-status badge-averiado">Fuera de servicio</span>
                @endif
            </div>
            @if($space->description)
            <div class="col-12">
                <p class="form-label-upper mb-1">Descripción</p>
                <p style="font-size:0.85rem;color:#555">{{ $space->description }}</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Reservas del espacio --}}
    <div class="section-header mb-3">
        <h2 class="section-title" style="font-size:1rem">Reservas recientes</h2>
    </div>

    @forelse($space->reservations->sortByDesc('date')->take(10) as $reservation)
    <div class="card-panel mb-2">
        <div class="d-flex align-items-start justify-content-between gap-3">
            <div>
                <div style="font-size:0.85rem;font-weight:500;color:#111">
                    {{ $reservation->notes ?? 'Sin descripción' }}
                </div>
                <div style="font-size:0.75rem;color:#999;margin-top:3px">
                    {{ \Carbon\Carbon::parse($reservation->date)->isoFormat('ddd D MMM YYYY') }}
                    &middot;
                    {{ substr($reservation->start, 0, 5) }} – {{ substr($reservation->end, 0, 5) }}
                    &middot;
                    {{ $reservation->user->name ?? '—' }}
                </div>
                @if($reservation->resources->count())
                <div class="d-flex flex-wrap gap-1 mt-1">
                    @foreach($reservation->resources as $res)
                    <span style="font-size:0.7rem;padding:2px 7px;border-radius:20px;border:1px solid #ddd;color:#666">
                        {{ $res->name }}
                    </span>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>
    @empty
        <div class="card-panel text-center py-4">
            <p style="color:#aaa;font-size:0.82rem">Este espacio no tiene reservas.</p>
        </div>
    @endforelse

</div>
@endsection