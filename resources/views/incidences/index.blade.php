@extends('layouts.app')

@section('content')
<div class="card-panel">

    <div class="section-header">
        <div>
            <h5 class="section-title">Incidencias</h5>
            <p class="section-subtitle">
                {{ auth()->user()->isAdmin() ? 'Todas las incidencias del sistema' : 'Mis incidencias reportadas' }}
            </p>
        </div>
        <a href="{{ route('incidences.create') }}" class="btn btn-dark btn-sm">
            <i class="bi bi-plus me-1"></i>Reportar incidencia
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success py-2 mb-3" style="font-size:0.8rem;">{{ session('success') }}</div>
    @endif
    @if(session('info'))
        <div class="alert alert-info py-2 mb-3" style="font-size:0.8rem;">{{ session('info') }}</div>
    @endif

    {{-- FILTRO ESTADO (solo admin ve ambos estados) --}}
    <form method="GET" action="{{ route('incidences.index') }}"
          class="d-flex flex-wrap gap-2 mb-3 align-items-center">

        <select name="status" class="form-select form-select-sm" style="width:auto; min-width:160px;">
            <option value="">Todos los estados</option>
            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>● Pendiente</option>
            <option value="3" {{ request('status') == '3' ? 'selected' : '' }}>● Resuelta</option>
        </select>

        <button type="submit" class="btn btn-dark btn-sm">Filtrar</button>

        @if(request('status'))
            <a href="{{ route('incidences.index') }}" class="btn btn-outline-secondary btn-sm">Limpiar</a>
            <span class="text-muted" style="font-size:0.7rem; text-transform:uppercase; letter-spacing:.04em;">
                {{ $incidences->count() }} resultado(s)
            </span>
        @endif
    </form>

    <div class="table-responsive">
        <table class="table table-sm table-hover table-bordered m-0">
            <thead>
                <tr>
                    <th class="text-center" style="width:50px;">ID</th>
                    <th>Recurso</th>
                    @if(auth()->user()->isAdmin())
                        <th style="width:130px;">Reportado por</th>
                    @endif
                    <th style="width:140px;">Fecha</th>
                    <th>Descripción</th>
                    <th style="width:110px;" class="text-center">Estado</th>
                    <th style="width:130px;" class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($incidences as $incidence)
                    <tr>
                        <td class="text-center text-muted">{{ $incidence->incidence_id }}</td>
                        <td class="fw-bold text-uppercase">{{ $incidence->resource->name ?? '—' }}</td>
                        @if(auth()->user()->isAdmin())
                            <td style="font-size:0.7rem;">{{ $incidence->user->name ?? '—' }}</td>
                        @endif
                        <td style="font-size:0.7rem;">
                            {{ $incidence->date_incidence
                                ? \Carbon\Carbon::parse($incidence->date_incidence)->format('d/m/Y H:i')
                                : '—' }}
                        </td>
                        <td style="font-size:0.7rem; max-width:280px;">
                            {{ \Illuminate\Support\Str::limit($incidence->description, 60) }}
                        </td>
                        <td class="text-center">
                            @if($incidence->status == 3)
                                <span class="badge-status badge-disponible">● Resuelta</span>
                            @else
                                <span class="badge-status badge-averiado">● Pendiente</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-flex gap-1 justify-content-center">
                                <a href="{{ route('incidences.show', $incidence->incidence_id) }}"
                                   class="btn btn-outline-dark btn-sm py-0 px-2">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if(auth()->user()->isAdmin() && $incidence->status != 3)
                                    <a href="{{ route('incidences.edit', $incidence->incidence_id) }}"
                                       class="btn btn-dark btn-sm py-0 px-2">Resolver</a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ auth()->user()->isAdmin() ? 7 : 6 }}"
                            class="text-center py-4 text-muted text-uppercase" style="font-size:0.75rem;">
                            @if(request('status'))
                                Sin resultados para los filtros aplicados.
                            @else
                                No hay incidencias registradas.
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection