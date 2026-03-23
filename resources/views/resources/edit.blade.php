@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="main-card p-4 border border-dark bg-white shadow-sm">
            <div class="d-flex justify-content-between align-items-center mb-4 border-bottom border-dark pb-2">
                <div>
                    <h4 class="text-uppercase fw-bold m-0">Editar Recurso</h4>
                    <small class="text-muted text-uppercase">ID: {{ $resource->resource_id }}</small>
                </div>
                <a href="{{ route('resources.index') }}" class="btn btn-sm btn-outline-dark rounded-0 text-uppercase fw-bold">
                    Cancelar
                </a>
            </div>

            <form action="{{ route('resources.update', $resource->resource_id) }}" method="POST">
                @csrf
                @method('PUT') <div class="mb-3">
                    <label class="small text-uppercase fw-bold">Nombre del Recurso:</label>
                    <input type="text" name="name" 
                           class="form-control border-dark rounded-0 @error('name') is-invalid @enderror" 
                           value="{{ old('name', $resource->name) }}" required maxlength="50">
                    @error('name')
                        <div class="invalid-feedback fw-bold">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="small text-uppercase fw-bold">Categoría:</label>
                    <select name="category_id" class="form-select border-dark rounded-0 @error('category_id') is-invalid @enderror" required>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->category_id }}" 
                                {{ (old('category_id', $resource->category_id) == $cat->category_id) ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="small text-uppercase fw-bold">Estado del Recurso:</label>
                    <div class="d-flex gap-3 mt-1">
                        <div class="form-check">
                            <input class="form-check-input border-dark" type="radio" name="status" id="status1" value="1" 
                                {{ $resource->status == 1 ? 'checked' : '' }}>
                            <label class="form-check-label small text-uppercase" for="status1">Disponible</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input border-dark" type="radio" name="status" id="status3" value="3" 
                                {{ $resource->status == 3 ? 'checked' : '' }}>
                            <label class="form-check-label small text-uppercase" for="status3">⚠️ Averiado / Fuera de servicio</label>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="small text-uppercase fw-bold">Descripción / Notas técnicas:</label>
                    <textarea name="description" class="form-control border-dark rounded-0" rows="3">{{ old('description', $resource->description) }}</textarea>
                </div>

                <hr class="border-dark">

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary rounded-0 text-uppercase fw-bold py-2 border-dark">
                        Actualizar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection