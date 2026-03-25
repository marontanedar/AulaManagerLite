@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-12">
        <div class="main-card border border-dark bg-white shadow-sm p-3">
            
            <div class="d-flex justify-content-between align-items-center mb-3 border-bottom border-dark pb-2">
                <div>
                    <h5 class="fw-bold text-uppercase m-0 italic" style="letter-spacing: 1px;">Gestión de Recursos</h5>
                    <small class="text-muted text-uppercase" style="font-size: 0.65rem;">Inventario de Aulas y Equipamiento Tecnológico</small>
                </div>
                
                <a href="{{ route('resources.create') }}" 
                   class="btn btn-sm btn-outline-dark rounded-0 text-uppercase fw-bold px-3 shadow-sm"
                   style="font-size: 0.7rem; border-width: 1.5px;">
                   + Nuevo Recurso
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-sm table-hover table-bordered m-0">
                    <thead class="table-light text-uppercase">
                        <tr>
                            <th style="width: 50px;" class="text-center">ID</th>
                            <th>Nombre del Recurso</th>
                            <th style="width: 150px;">Categoría</th>
                            <th style="width: 120px;" class="text-center">Estado</th>
                            <th style="width: 150px;">Creador</th>
                            <th style="width: 180px;" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="fw-bold" style="font-size: 0.75rem;">
                        @forelse($resources as $resource)
                            <tr>
                                <td class="text-center text-muted small">{{ $resource->resource_id }}</td> 
                                <td class="text-uppercase">{{ $resource->name }}</td>
                                <td>
                                    <span class="border border-dark px-2 py-0 small text-uppercase" style="font-size: 0.6rem; background: #fdfdfd;">
                                        {{ $resource->category->name ?? 'Sin Categoría' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if($resource->status == 1)
                                        <span class="text-success" style="font-size: 0.65rem;">● DISPONIBLE</span>
                                    @elseif($resource->status == 3)
                                        <span class="text-warning" style="font-size: 0.65rem;">● AVERIADO</span>
                                    @else
                                        <span class="text-danger" style="font-size: 0.65rem;">● RESERVADO</span>
                                    @endif
                                </td>
                                <td class="small text-muted text-uppercase" style="font-size: 0.65rem;">
                                    {{ $resource->user->name ?? 'Admin' }}
                                </td>
                                <td class="text-center">
                                    <div class="d-flex gap-1 justify-content-center">
                                        <a href="{{ route('resources.edit', $resource->resource_id) }}" 
                                        class="btn btn-xs btn-outline-dark py-0 px-2 fw-bold" 
                                        style="font-size: 0.6rem;">EDITAR</a>
                                        
                                        <form action="{{ route('resources.destroy', $resource->resource_id) }}" method="POST" onsubmit="return confirm('¿Eliminar este recurso?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-xs btn-danger py-0 px-2 fw-bold" 
                                                    style="font-size: 0.6rem;">ELIMINAR</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted text-uppercase">
                                    No hay recursos registrados actualmente.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection