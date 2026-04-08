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
                        ?->name ?? '—'
                        ?```
                        @endif
                        <td style="font-size:0.7rem;">
                            {{ \Carbon\Carbon::parse($incidence->date_incidence)->format('d/m/Y H:i') }}
                        </td>
                        <td style="font-size:0.7rem; max-width:280px;">
                            {{ \Illuminate\Support\Str::limit($incidence->description, 60) }}
                        </td>
                        <td class="text-center">
                            @if($incidence->status)
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
                                @if(auth()->user()->isAdmin() && !$incidence->status)
                                    <a href="{{ route('incidences.edit', $incidence->incidence_id) }}"
                                       class="btn btn-dark btn-sm py-0 px-2">
                                        Resolver
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ auth()->user()->isAdmin() ? 7 : 6 }}"
                            class="text-center py-4 text-muted text-uppercase" style="font-size:0.75rem;">
                            No hay incidencias registradas.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection