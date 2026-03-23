@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="main-card p-4 border border-dark bg-white shadow-sm">
            <div class="d-flex justify-content-between align-items-center mb-4 border-bottom border-dark pb-2">
                <h4 class="text-uppercase fw-bold m-0">Nuevo Recurso</h4>
                <a href="{{ route('resources.index') }}" class="btn btn-sm btn-outline-dark rounded-0 text-uppercase fw-bold">
                    Volver
                </a>
            </div>

            <form action="{{ route('resources.store') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label class="small text-uppercase fw-bold">Nombre del Recurso / Aula:</label>
                    <input type="text" name="name" 
                           class="form-control border-dark rounded-0 @error('name') is-invalid @enderror" 
                           placeholder="Ej: Aula 101, Proyector A..." 
                           value="{{ old('name') }}" required maxlength="50">
                    @error('name')
                        <div class="invalid-feedback fw-bold">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="small text-uppercase fw-bold">Categoría:</label>
                    <select name="category_id" class="form-select border-dark rounded-0 @error('category_id') is-invalid @enderror" required>
                        <option value="" selected disabled>Selecciona una categoría...</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->category_id }}" {{ old('category_id') == $cat->category_id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="invalid-feedback fw-bold text-uppercase" style="font-size: 0.7rem;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="small text-uppercase fw-bold">Estado Inicial:</label>
                    <div class="d-flex gap-3 mt-1">
                        <div class="form-check">
                            <input class="form-check-input border-dark" type="radio" name="status" id="status1" value="1" checked>
                            <label class="form-check-label small text-uppercase" for="status1">Disponible</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input border-dark" type="radio" name="status" id="status3" value="3">
                            <label class="form-check-label small text-uppercase" for="status3">Averiado</label>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="small text-uppercase fw-bold">Descripción (Opcional):</label>
                    <textarea name="description" class="form-control border-dark rounded-0" rows="3" 
                              placeholder="Detalles adicionales, ubicación, etc...">{{ old('description') }}</textarea>
                </div>

                <hr class="border-dark">

                <div class="d-grid">
                    <button type="submit" class="btn btn-dark rounded-0 text-uppercase fw-bold py-2">
                        Guardar Recurso
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection