@extends('layouts.app')

@section('content')
<div class="row g-3">

    {{-- COLUMNA IZQUIERDA: detalle del recurso --}}
    <div class="col-12 col-lg-4">
        <div class="card-panel h-100">
            <div class="section-header">
                <div>
                    <h5 class="section-title">{{ $resource->name }}</h5>
                    <p class="section-subtitle">Detalle del recurso</p>
                </div>
                @if(auth()->user()->role->admin)
                    <a href="{{ route('resources.edit', $resource->resource_id) }}"
                       class="btn btn-outline-dark btn-sm">Editar</a>
                @endif
            </div>

            {{-- Estado --}}
            <div class="mb-3">
                <span class="form-label-upper d-block mb-1">Estado</span>
                @if($resource->status == 1)
                    <span class="badge-status badge-disponible">● Disponible</span>
                @elseif($resource->status == 2)
                    <span class="badge-status badge-mantenimiento">● Mantenimiento</span>
                @else
                    <span class="badge-status badge-averiado">● Fuera de servicio</span>
                @endif
            </div>

            {{-- Categoría --}}
            <div class="mb-3">
                <span class="form-label-upper d-block mb-1">Categoría</span>
                <span style="border:1px solid #ccc; padding:2px 8px; font-size:0.65rem; text-transform:uppercase; background:#fafafa;">
                    {{ $resource->category->name ?? '—' }}
                </span>
            </div>

            {{-- Descripción --}}
            <div class="mb-3">
                <span class="form-label-upper d-block mb-1">Descripción</span>
                <p class="text-muted mb-0" style="font-size:0.8rem;">
                    {{ $resource->description ?: 'Sin descripción.' }}
                </p>
            </div>

            {{-- Meta --}}
            <div style="border-top:1px solid #e0e0e0; padding-top:0.75rem; margin-top:0.75rem;">
                <div class="d-flex justify-content-between mb-1">
                    <span class="form-label-upper">Creado por</span>
                    <span class="text-muted text-uppercase" style="font-size:0.65rem;">
                        {{ $resource->creator->name ?? '—' }}
                    </span>
                </div>
                @if($resource->updated_by)
                <div class="d-flex justify-content-between">
                    <span class="form-label-upper">Modificado por</span>
                    <span class="text-muted text-uppercase" style="font-size:0.65rem;">
                        {{ $resource->updater->name ?? '—' }}
                    </span>
                </div>
                @endif
            </div>

            {{-- Volver --}}
            <div class="mt-4">
                <a href="{{ route('resources.index') }}"
                   class="btn btn-outline-dark btn-sm w-100">← Volver al inventario</a>
            </div>
        </div>
    </div>

    {{-- COLUMNA DERECHA: próximas reservas --}}
    <div class="col-12 col-lg-8">
        <div class="card-panel">
            <div class="section-header mb-3">
                <div>
                    <h5 class="section-title">Próximas reservas</h5>
                    <p class="section-subtitle">Reservas asignadas a partir de hoy</p>
                </div>
                <span class="text-muted" style="font-size:0.7rem; text-transform:uppercase; letter-spacing:.05em;">
                    {{ $resource->reservations->count() }} resultado(s)
                </span>
            </div>

            @if($resource->reservations->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-calendar-x" style="font-size:2rem; opacity:.3;"></i>
                    <p class="mt-2 mb-0 text-uppercase" style="font-size:0.75rem;">
                        Sin reservas próximas para este recurso
                    </p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-sm table-hover table-bordered m-0">
                        <thead>
                            <tr>
                                <th style="width:110px;">Fecha</th>
                                <th style="width:110px;" class="text-center">Horario</th>
                                <th>Espacio</th>
                                <th>Reservado por</th>
                                <th class="text-muted" style="width:80px; font-size:0.65rem;">ID Reserva</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($resource->reservations as $reservation)
                                <tr>
                                    <td class="fw-bold" style="font-size:0.8rem;">
                                        {{ \Carbon\Carbon::parse($reservation->date)->format('d M Y') }}
                                    </td>
                                    <td class="text-center text-muted" style="font-size:0.75rem;">
                                        {{ \Carbon\Carbon::parse($reservation->start)->format('H:i') }}
                                        –
                                        {{ \Carbon\Carbon::parse($reservation->end)->format('H:i') }}
                                    </td>
                                    <td class="text-uppercase fw-bold" style="font-size:0.75rem;">
                                        {{ $reservation->space->name ?? '—' }}
                                    </td>
                                    <td class="text-muted text-uppercase" style="font-size:0.65rem;">
                                        {{ $reservation->user->name ?? '—' }}
                                    </td>
                                    <td class="text-center text-muted" style="font-size:0.65rem;">
                                        #{{ $reservation->reservation_id }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

</div>
@endsection