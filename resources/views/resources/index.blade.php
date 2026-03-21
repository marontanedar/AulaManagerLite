@extends('layouts.app')

@section('content')
    <div class="main-card">
        
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Gestión de Recursos</h2>
            <a href="{{ route('resources.create') }}" class="btn btn-dark btn-sm text-uppercase">+ Nuevo Recurso</a>
        </div>

        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Recurso</th>
                    <th>Categoría</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($resources as $resource)
                <tr>
                    <td>{{ $resource->name }}</td>
                    
                    <td>{{ $resource->category->name }}</td>
                    
                    <td>
                        @if($resource->status == 1)
                            <span class="badge bg-success">Disponible</span>
                        @elseif($resource->status == 2)
                            <span class="badge bg-warning text-dark">Mantenimiento</span>
                        @else
                            <span class="badge bg-danger">Averiado</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('resources.edit', $resource->resource_id) }}" class="btn btn-outline-dark btn-sm">Editar</a>
                        
                        <form action="{{ route('incidences.store') }}" method="POST" class="d-inline">
                            @csrf
                            <input type="hidden" name="resource_id" value="{{ $resource->resource_id }}">
                            <input type="hidden" name="description" value="Reporte rápido: Revisar equipo.">
                            <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('¿Seguro que quieres reportar una avería en este equipo?')">Averiado</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-muted">No hay recursos registrados.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection