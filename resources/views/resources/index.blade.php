@extends('layouts.app')

@section('content')
<div class="main-card p-4 border border-dark bg-white shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="text-uppercase fw-bold m-0">Gestión de Recursos</h4>
        <a href="{{ route('resources.create') }}" class="btn btn-dark rounded-0 text-uppercase fw-bold">
            + Nuevo Recurso
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success rounded-0 border-dark small fw-bold text-uppercase">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-bordered align-middle">
        <thead class="table-light text-center">
            <tr class="border-dark">
                <th class="border-dark" style="width: 50px;">ID</th>
                <th class="border-dark">NOMBRE</th>
                <th class="border-dark">CATEGORÍA</th>
                <th class="border-dark">ESTADO</th>
                <th class="border-dark">CREADOR</th>
                <th class="border-dark">ACCIONES</th>
            </tr>
        </thead>
        <tbody>
            @forelse($resources as $res)
            <tr>
                <td class="text-center small">{{ $res->resource_id }}</td>
                <td class="fw-bold">{{ $res->name }}</td>
                <td class="text-center">
                    <span class="badge border border-dark text-dark rounded-0 small">
                        {{ $res->category->name }}
                    </span>
                </td>
                <td class="text-center">
                    @if($res->status == 1)
                        <span class="text-success fw-bold small text-uppercase">● Disponible</span>
                    @elseif($res->status == 3)
                        <span class="text-danger fw-bold small text-uppercase">■ Averiado</span>
                    @else
                        <span class="text-warning fw-bold small text-uppercase">○ Reservado</span>
                    @endif
                </td>
                <td class="small">{{ $res->creator->name ?? 'Sistema' }}</td>
                <td class="text-center">
                    <div class="btn-group">
                        <a href="{{ route('resources.edit', $res->resource_id) }}" class="btn btn-sm btn-outline-dark rounded-0">Editar</a>
                        <form action="{{ route('resources.destroy', $res->resource_id) }}" method="POST" 
                            onsubmit="return confirm('¿Estás seguro de eliminar este recurso? Esta acción no se puede deshacer.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger rounded-0 text-uppercase fw-bold">
                                Eliminar
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center py-4 text-muted">No hay recursos registrados.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection