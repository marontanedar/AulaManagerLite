@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
        <div class="card-panel">
            <div class="section-header">
                <div>
                    <h5 class="section-title">Reportar Incidencia</h5>
                    <p class="section-subtitle">Notificar un problema con un recurso</p>
                </div>
                <a href="{{ route('incidences.index') }}" class="btn btn-outline-dark btn-sm">← Volver</a>
            </div>

            <form action="{{ route('incidences.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label-upper">Recurso afectado</label>
                    <select name="resource_id" class="form-select" required>
                        <option value="">Selecciona un recurso...</option>
                        @foreach($resources as $resource)
                            <option value="{{ $resource->resource_id }}"
                                {{ old('resource_id') == $resource->resource_id ? 'selected' : '' }}>
                                {{ $resource->name }}
                                @if($resource->status == 2) (en mantenimiento) @endif
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label-upper">Descripción del problema</label>
                    <textarea name="description" class="form-control" rows="5"
                              placeholder="Describe el problema con detalle: qué ocurre, cuándo empezó, si afecta al uso del recurso..."
                              required>{{ old('description') }}</textarea>
                    <small class="text-muted" style="font-size:0.65rem;">
                        El recurso pasará automáticamente a estado de mantenimiento al reportar la incidencia.
                    </small>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-dark py-2">
                        <i class="bi bi-exclamation-triangle me-1"></i>Reportar incidencia
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection