@extends('layouts.app')

@section('content')
<div class="container py-4" style="max-width:640px">

    <div class="section-header mb-4">
        <a href="{{ route('spaces.index') }}"
           style="font-size:0.78rem;color:#888;text-decoration:none">
            ← Volver a espacios
        </a>
        <h1 class="section-title mt-1 mb-0">Nuevo espacio</h1>
    </div>

    @if($errors->any())
        <div class="alert alert-danger py-2 mb-3">{{ $errors->first() }}</div>
    @endif

    <div class="card-panel">
        <form method="POST" action="{{ route('spaces.store') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label-upper">Nombre</label>
                <input type="text" name="name" class="form-control form-control-sm"
                       value="{{ old('name') }}" required placeholder="ej: Aula 101">
            </div>

            <div class="row g-3 mb-3">
                <div class="col">
                    <label class="form-label-upper">Categoría</label>
                    <select name="category_id" class="form-select form-select-sm">
                        <option value="">— Sin categoría —</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->category_id }}"
                                {{ old('category_id') == $cat->category_id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col">
                    <label class="form-label-upper">Capacidad (personas)</label>
                    <input type="number" name="capacity" class="form-control form-control-sm"
                           value="{{ old('capacity', 1) }}" min="1" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label-upper">Estado</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="1" {{ old('status') == 1 ? 'selected' : '' }}>Disponible</option>
                    <option value="2" {{ old('status') == 2 ? 'selected' : '' }}>Mantenimiento</option>
                    <option value="3" {{ old('status') == 3 ? 'selected' : '' }}>Fuera de servicio</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="form-label-upper">
                    Descripción
                    <span style="font-weight:400;text-transform:none">(opcional)</span>
                </label>
                <textarea name="description" class="form-control form-control-sm"
                          rows="3" placeholder="ej: Equipada con pizarra digital y proyector fijo">{{ old('description') }}</textarea>
            </div>

            <div class="d-flex gap-2 justify-content-end">
                <a href="{{ route('spaces.index') }}"
                   class="btn btn-sm btn-outline-secondary">Cancelar</a>
                <button type="submit" class="btn btn-sm btn-dark">Guardar espacio</button>
            </div>
        </form>
    </div>

</div>
@endsection