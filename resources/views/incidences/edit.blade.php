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
                    <p class="form-label-upper mb-2">Cambiar estado</p>
                    <div class="d-flex gap-3 p-2" style="background:#f5f5f5; border:1.5px solid #111;">
                        <div class="form-check mb-0">
                            <input class="form-check-input" type="radio" name="status" id="s1" value="1"
                                {{ $incidence->status == 1 ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold text-danger" style="font-size:0.7rem;" for="s1">
                                ● Pendiente
                            </label>
                        </div>
                        <div class="form-check mb-0">
                            <input class="form-check-input" type="radio" name="status" id="s3" value="3"
                                {{ $incidence->status == 3 ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold text-success" style="font-size:0.7rem;" for="s3">
                                ● Resuelta
                            </label>
                        </div>
                    </div>
                    <small class="text-muted" style="font-size:0.65rem;">
                        Al marcar como resuelta, el recurso volverá automáticamente a disponible.
                    </small>
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