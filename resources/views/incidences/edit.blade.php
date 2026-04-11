@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-md-7">
        <div class="card-panel">
            <div class="section-header">
                <div>
                    <h5 class="section-title">Resolver Incidencia #{{ $incidence->incidence_id }}</h5>
                    <p class="section-subtitle">{{ $incidence->resource->name ?? '—' }}</p>
                </div>
                <a href="{{ route('incidences.show', $incidence->incidence_id) }}"
                   class="btn btn-outline-dark btn-sm">← Volver</a>
            </div>

            {{-- Descripción de la incidencia --}}
            <div class="mb-4">
                <p class="form-label-upper mb-1">Descripción del problema</p>
                <div style="background:#f5f5f5; border:1.5px solid #ddd; padding:1rem; font-size:0.85rem; line-height:1.6;">
                    {{ $incidence->description }}
                </div>
            </div>

            {{-- Formulario de resolución --}}
            <form method="POST" action="{{ route('incidences.update', $incidence->incidence_id) }}">
                @csrf
                @method('PUT')

                <input type="hidden" name="status" value="3">

                <div class="mb-4">
                    <p class="form-label-upper mb-1">Acción</p>
                    <div style="background:#f0fff4; border:1.5px solid #198754; padding:0.75rem; font-size:0.85rem;">
                        Al confirmar, la incidencia se marcará como <strong>resuelta</strong>
                        y el recurso volverá a estar <strong>disponible</strong>.
                    </div>
                </div>

                <div class="d-flex gap-2 justify-content-end">
                    <a href="{{ route('incidences.show', $incidence->incidence_id) }}"
                       class="btn btn-outline-dark btn-sm">Cancelar</a>
                    <button type="submit" class="btn btn-dark btn-sm">
                        ✓ Confirmar resolución
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection