@extends('layouts.app')

@section('content')
<div class="card-panel">

    {{-- HEADER --}}
    <div class="section-header">
        <div>
            <h5 class="section-title">Gestión de Recursos</h5>
            <p class="section-subtitle">Inventario de equipamiento móvil</p>
        </div>
        <a href="{{ route('resources.create') }}" class="btn btn-dark btn-sm">
            <i class="bi bi-plus me-1"></i>Nuevo Recurso
        </a>
    </div>

    {{-- ERRORES / SUCCESS --}}
    @if(session('error'))
        <div class="alert alert-danger py-2 mb-3" style="font-size:0.8rem;">
            <i class="bi bi-exclamation-triangle me-1"></i>{{ session('error') }}
        </div>
    @endif
    @if(session('success'))
        <div class="alert alert-success py-2 mb-3" style="font-size:0.8rem;">
            {{ session('success') }}
        </div>
    @endif

    {{-- FILTROS --}}
    <form method="GET" action="{{ route('resources.index') }}"
          class="d-flex flex-wrap gap-2 mb-3 align-items-center">

        <select name="category_id" class="form-select form-select-sm" style="width:auto; min-width:160px;">
            <option value="">Todas las categorías</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->category_id }}"
                    {{ request('category_id') == $cat->category_id ? 'selected' : '' }}>
                    {{ $cat->name }}
                </option>
            @endforeach
        </select>

        <select name="status" class="form-select form-select-sm" style="width:auto; min-width:150px;">
            <option value="">Todos los estados</option>
            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>● Disponible</option>
            <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>● Mantenimiento</option>
            <option value="3" {{ request('status') == '3' ? 'selected' : '' }}>● Fuera de servicio</option>
        </select>

        <button type="submit" class="btn btn-dark btn-sm">Filtrar</button>

        @if(request()->hasAny(['category_id', 'status']))
            <a href="{{ route('resources.index') }}"
               class="btn btn-outline-secondary btn-sm">Limpiar</a>
            <span class="text-muted" style="font-size:0.7rem; text-transform:uppercase; letter-spacing:.04em;">
                {{ $resources->count() }} resultado(s)
            </span>
        @endif
    </form>

    {{-- TABLA --}}
    <div class="table-responsive">
        <table class="table table-sm table-hover table-bordered m-0">
            <thead>
                <tr>
                    <th class="text-center" style="width:50px;">ID</th>
                    <th>Nombre</th>
                    <th style="width:140px;">Categoría</th>
                    <th style="width:130px;" class="text-center">Estado</th>
                    <th style="width:130px;">Creado por</th>
                    <th style="width:150px;" class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($resources as $resource)
                    <tr>
                        <td class="text-center text-muted">{{ $resource->resource_id }}</td>
                        <td>
                            <a href="{{ route('resources.show', $resource->resource_id) }}"
                               class="fw-bold text-uppercase text-decoration-none text-dark">
                                {{ $resource->name }}
                            </a>
                        </td>
                        <td>
                            <span style="border:1px solid #ccc; padding:1px 7px; font-size:0.6rem;
                                         text-transform:uppercase; background:#fafafa;">
                                {{ $resource->category->name ?? '—' }}
                            </span>
                        </td>
                        <td class="text-center">
                            @if($resource->status == 1)
                                <span class="badge-status badge-disponible">● Disponible</span>
                            @elseif($resource->status == 2)
                                <span class="badge-status badge-mantenimiento">● Mantenimiento</span>
                            @else
                                <span class="badge-status badge-averiado">● Fuera de servicio</span>
                            @endif
                        </td>
                        <td class="text-muted text-uppercase" style="font-size:0.65rem;">
                            {{ $resource->creator->name ?? '—' }}
                        </td>
                        <td class="text-center">
                            <div class="d-flex gap-1 justify-content-center">
                                <a href="{{ route('resources.edit', $resource->resource_id) }}"
                                   class="btn btn-outline-dark btn-sm py-0 px-2">Editar</a>
                                <form action="{{ route('resources.destroy', $resource->resource_id) }}"
                                      method="POST"
                                      onsubmit="return confirm('¿Eliminar {{ addslashes($resource->name) }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn btn-sm btn-danger py-0 px-2">Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted text-uppercase"
                            style="font-size:0.75rem;">
                            @if(request()->hasAny(['category_id', 'status']))
                                Sin resultados para los filtros aplicados.
                            @else
                                No hay recursos registrados.
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection