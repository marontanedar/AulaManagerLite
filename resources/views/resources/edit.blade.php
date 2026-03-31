@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
        <div class="card-panel">
            <div class="section-header">
                <div>
                    <h5 class="section-title">Editar Recurso</h5>
                    <p class="section-subtitle">Modificar detalles del inventario</p>
                </div>
                <a href="{{ route('resources.index') }}" class="btn btn-outline-dark btn-sm">← Volver</a>
            </div>

            <form action="{{ route('resources.update', $resource->resource_id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label-upper">Nombre del recurso / aula</label>
                    <input type="text" name="name" class="form-control"
                           value="{{ old('name', $resource->name) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label-upper">Categoría</label>
                    <select name="category_id" class="form-select" required>
                        <option value="">Selecciona...</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->category_id }}"
                                {{ old('category_id', $resource->category_id) == $category->category_id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label-upper">Estado actual</label>
                    <div class="d-flex gap-3 p-2" style="background:#f5f5f5; border:1.5px solid #111;">
                        <div class="form-check mb-0">
                            <input class="form-check-input" type="radio" name="status" id="s1" value="1"
                                   {{ old('status', $resource->status) == 1 ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold text-success" style="font-size:0.7rem;" for="s1">● Disponible</label>
                        </div>
                        <div class="form-check mb-0">
                            <input class="form-check-input" type="radio" name="status" id="s2" value="2"
                                   {{ old('status', $resource->status) == 2 ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" style="font-size:0.7rem; color:#e6a817;" for="s2">● Mantenimiento</label>
                        </div>
                        <div class="form-check mb-0">
                            <input class="form-check-input" type="radio" name="status" id="s3" value="3"
                                   {{ old('status', $resource->status) == 3 ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold text-danger" style="font-size:0.7rem;" for="s3">● Fuera de servicio</label>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label-upper">Descripción (opcional)</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description', $resource->description) }}</textarea>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-dark py-2">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection