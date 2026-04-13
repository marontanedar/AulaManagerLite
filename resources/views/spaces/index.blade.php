@extends('layouts.app')

@section('content')
<div class="container py-4">

    <div class="section-header d-flex align-items-center justify-content-between mb-4">
        <h1 class="section-title mb-0">Espacios</h1>
        <a href="{{ route('spaces.create') }}" class="btn btn-sm btn-dark">+ Nuevo espacio</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success py-2">{{ session('success') }}</div>
    @endif

    @forelse($spaces as $space)
    <div class="card-panel mb-3">
        <div class="d-flex align-items-start justify-content-between gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span style="font-size:0.9rem;font-weight:500;color:#111">{{ $space->name }}</span>
                    @if($space->status === 1)
                        <span class="badge-status badge-disponible">Disponible</span>
                    @elseif($space->status === 2)
                        <span class="badge-status badge-mantenimiento">Mantenimiento</span>
                    @else
                        <span class="badge-status badge-averiado">Fuera de servicio</span>
                    @endif
                </div>
                <div style="font-size:0.78rem;color:#999">
                    {{ $space->category?->name ?? '—' }}
                    &middot; {{ $space->capacity }} plazas
                </div>
                @if($space->description)
                <div style="font-size:0.78rem;color:#666;margin-top:4px">
                    {{ $space->description }}
                </div>
                @endif
            </div>
            <div class="d-flex gap-2 align-items-center">
                <a href="{{ route('spaces.show', $space->space_id) }}"
                   class="btn btn-sm btn-outline-secondary" style="font-size:0.75rem">
                    Ver
                </a>
                <a href="{{ route('spaces.edit', $space->space_id) }}"
                   class="btn btn-sm btn-outline-secondary" style="font-size:0.75rem">
                    Editar
                </a>
                <form method="POST"
                      action="{{ route('spaces.destroy', $space->space_id) }}"
                      onsubmit="return confirm('¿Eliminar este espacio?')"
                      class="mb-0">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger" style="font-size:0.75rem">
                        Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
    @empty
        <div class="card-panel text-center py-5">
            <p style="color:#aaa;font-size:0.85rem">No hay espacios registrados.</p>
            <a href="{{ route('spaces.create') }}" class="btn btn-sm btn-dark mt-2">
                Crear primer espacio
            </a>
        </div>
    @endforelse

</div>
@endsection