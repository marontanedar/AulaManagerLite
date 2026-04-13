@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-md-9">
        <div class="card-panel">
            <div class="section-header">
                <div>
                    <h5 class="section-title">Detalle de Incidencia #{{ $incidence->incidence_id }}</h5>
                    <p class="section-subtitle">
                        {{ \Carbon\Carbon::parse($incidence->date_incidence)->format('d/m/Y H:i') }}
                    </p>
                </div>
                <div class="d-flex gap-2">
                    @if(auth()->user()->isAdmin() && $incidence->status != 3)
                        <a href="{{ route('incidences.edit', $incidence->incidence_id) }}"
                           class="btn btn-dark btn-sm">Resolver</a>
                    @endif
                    <a href="{{ route('incidences.index') }}" class="btn btn-outline-dark btn-sm">← Volver</a>
                </div>
            </div>

            {{-- Estado destacado --}}
            @php
                $resuelta = $incidence->status == 3;
                $bgColor     = $resuelta ? '#f0fff4' : '#fff5f5';
                $borderColor = $resuelta ? '#198754' : '#dc3545';
                $textColor   = $resuelta ? '#198754' : '#dc3545';
                $label       = $resuelta ? '● INCIDENCIA RESUELTA' : '● INCIDENCIA PENDIENTE DE RESOLUCIÓN';
            @endphp

            <div class="mb-4 p-3 text-center"
                style="background:{{ $bgColor }}; border:1.5px solid {{ $borderColor }};">
                <span class="fw-bold" style="color:{{ $textColor }}; font-size:0.85rem;">
                    {{ $label }}
                </span>
            </div>

            {{-- Info en grid --}}
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <p class="form-label-upper mb-1">Recurso</p>
                    <span class="fw-bold" style="font-size:0.8rem;">
                        {{ $incidence->resource->name ?? '—' }}
                    </span>
                </div>

                <div class="col-6 col-md-3">
                    <p class="form-label-upper mb-1">Estado del recurso</p>
                    @php $rStatus = $incidence->resource->status ?? null; @endphp
                    @if($rStatus == 1)
                        <span class="badge-status badge-disponible">● Disponible</span>
                    @elseif($rStatus == 2)
                        <span class="badge-status badge-mantenimiento">● Mantenimiento</span>
                    @elseif($rStatus == 3)
                        <span class="badge-status badge-averiado">● Fuera de servicio</span>
                    @else
                        <span class="text-muted" style="font-size:0.75rem;">—</span>
                    @endif
                </div>
                
                <div class="col-6 col-md-3">
                    <p class="form-label-upper mb-1">Reportado por</p>
                    <span style="font-size:0.8rem;">{{ $incidence->user->name ?? '—' }}</span>
                </div>
                <div class="col-6 col-md-3">
                    <p class="form-label-upper mb-1">Fecha reporte</p>
                    <span style="font-size:0.8rem;">
                        {{ \Carbon\Carbon::parse($incidence->date_incidence)->format('d/m/Y H:i') }}
                    </span>
                </div>
            </div>

            {{-- Descripción --}}
            <div class="mb-4">
                <p class="form-label-upper mb-1">Descripción del problema</p>
                <div style="background:#f5f5f5; border:1.5px solid #ddd; padding:1rem; font-size:0.85rem; line-height:1.6;">
                    {{ $incidence->description }}
                </div>
            </div>

            {{-- Resolución si existe --}}
            @if($incidence->status ==3 && $incidence->updater)
                <div class="mb-4">
                    <p class="form-label-upper mb-1">Resuelta por</p>
                    <div style="background:#f0fff4; border:1.5px solid #198754; padding:0.75rem; font-size:0.8rem;">
                        <span class="fw-bold">{{ $incidence->updater->name }}</span>
                        @if($incidence->updated_at)
                            <span class="text-muted ms-2">
                                — {{ \Carbon\Carbon::parse($incidence->updated_at)->format('d/m/Y H:i') }}
                            </span>
                        @endif
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>
@endsection