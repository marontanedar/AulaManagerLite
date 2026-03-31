@extends('layouts.app')

@section('content')
<div class="card-panel">
    <div class="section-header">
        <div>
            <h5 class="section-title">Gestión de Recursos</h5>
            <p class="section-subtitle">Inventario de aulas y equipamiento</p>
        </div>
        <a href="{{ route('resources.create') }}" class="btn btn-dark btn-sm">
            <i class="bi bi-plus me-1"></i>Nuevo Recurso
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-sm table-hover table-bordered m-0">
            <thead>
                <tr>
                    <th class="text-center" style="width:50px;">ID</th>
                    <th>Nombre</th>
                    <th style="width:140px;">Categoría</th>
                    <th style="width:130px;" class="text-center">Estado</th>
                    <th style="width:130px;">Creador</th>
                    <th style="width:150px;" class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($resources as $resource)
                    <tr>
                        <td class="text-center text-muted">{{ $resource->resource_id }}</td>
                        <td class="fw-bold text-uppercase">{{ $resource->name }}</td>
                        <td>
                            <span style="border:1px solid #ccc; padding:1px 7px; font-size:0.6rem; text-transform:uppercase; background:#fafafa;">
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
                                      onsubmit="return confirm('¿Eliminar este recurso?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger py-0 px-2">Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted text-uppercase" style="font-size:0.75rem;">
                            No hay recursos registrados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection