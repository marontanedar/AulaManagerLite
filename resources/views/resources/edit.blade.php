@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-12">
        <div class="main-card border border-dark bg-white shadow-sm p-4 mx-auto" style="max-width: 700px;">
            
            <div class="d-flex justify-content-between align-items-center mb-4 border-bottom border-dark pb-2">
                <div>
                    <h5 class="fw-bold text-uppercase m-0" style="letter-spacing: 1px;">Editar Recurso</h5>
                    <small class="text-muted text-uppercase" style="font-size: 0.65rem;">Modificar detalles del inventario</small>
                </div>
                <a href="{{ route('resources.index') }}" class="btn btn-sm btn-outline-dark text-uppercase fw-bold rounded-0">Volver</a>
            </div>

            <form action="{{ route('resources.update', $resource->resource_id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="fw-bold text-uppercase mb-1" style="font-size: 0.75rem;">Nombre del Recurso / Aula:</label>
                    <input type="text" name="name" id="name" 
                           class="form-control border-dark rounded-0 fw-bold" 
                           value="{{ old('name', $resource->name) }}" required>
                </div>

                <div class="mb-3">
                    <label for="category_id" class="fw-bold text-uppercase mb-1" style="font-size: 0.75rem;">Categoría:</label>
                    <select name="category_id" id="category_id" class="form-select border-dark rounded-0 fw-bold" required>
                        <option value="">Selecciona una categoría...</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->category_id }}" 
                                {{ (old('category_id', $resource->category_id) == $category->category_id) ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="fw-bold text-uppercase mb-1" style="font-size: 0.75rem;">Estado Actual:</label>
                    <div class="d-flex gap-4 p-2 border border-dark bg-light">
                        <div class="form-check">
                            <input class="form-check-input border-dark" type="radio" name="status" id="status1" value="1" {{ old('status', $resource->status) == 1 ? 'checked' : '' }}>
                            <label class="form-check-label small fw-bold text-success" for="status1">● DISPONIBLE</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input border-dark" type="radio" name="status" id="status3" value="3" {{ old('status', $resource->status) == 3 ? 'checked' : '' }}>
                            <label class="form-check-label small fw-bold text-warning" for="status3">● AVERIADO</label>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="description" class="fw-bold text-uppercase mb-1" style="font-size: 0.75rem;">Descripción (Opcional):</label>
                    <textarea name="description" id="description" rows="3" 
                              class="form-control border-dark rounded-0">{{ old('description', $resource->description) }}</textarea>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-dark text-uppercase fw-bold py-2 rounded-0 shadow-sm">
                        GUARDAR CAMBIOS
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection