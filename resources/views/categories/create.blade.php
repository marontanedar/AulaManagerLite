@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-md-6">
        <div class="card-panel">
            <div class="section-header">
                <div>
                    <h5 class="section-title">Nueva Categoría</h5>
                    <p class="section-subtitle">Añadir categoría al sistema</p>
                </div>
                <a href="{{ route('categories.index') }}" class="btn btn-outline-dark btn-sm">← Volver</a>
            </div>

            @if($errors->any())
                <div class="alert alert-danger py-2" style="font-size:0.8rem;">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('categories.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label-upper">Nombre</label>
                    <input type="text"
                           name="name"
                           class="form-control form-control-sm @error('name') is-invalid @enderror"
                           value="{{ old('name') }}"
                           placeholder="Ej: Proyectores, Portátiles..."
                           autofocus>
                </div>
                <div class="d-flex gap-2 justify-content-end">
                    <a href="{{ route('categories.index') }}" class="btn btn-outline-dark btn-sm">Cancelar</a>
                    <button type="submit" class="btn btn-dark btn-sm">Crear categoría</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection