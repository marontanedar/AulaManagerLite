@extends('layouts.app')

@section('content')
<div class="card-panel">
    <div class="section-header">
        <div>
            <h5 class="section-title">Historial de Auditoría</h5>
            <p class="section-subtitle">Registro completo de acciones del sistema</p>
        </div>
        <span class="badge bg-dark" style="font-size:0.6rem; border-radius:0;">
            {{ $logs->total() }} registros
        </span>
    </div>

    {{-- Filtros --}}
    <form method="GET" action="{{ route('audit.index') }}" class="row g-2 mb-3">
        <div class="col-6 col-md-3">
            <label class="form-label-upper">Acción</label>
            <select name="action" class="form-select form-select-sm">
                <option value="">Todas</option>
                @foreach($actions as $action)
                    <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                        {{ ucfirst($action) }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-6 col-md-3">
            <label class="form-label-upper">Entidad</label>
            <select name="model" class="form-select form-select-sm">
                <option value="">Todas</option>
                @foreach($models as $model)
                    <option value="{{ $model }}" {{ request('model') == $model ? 'selected' : '' }}>
                        {{ $model }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-6 col-md-3">
            <label class="form-label-upper">Fecha</label>
            <input type="date" name="date" class="form-control form-control-sm"
                   value="{{ request('date') }}">
        </div>
        <div class="col-6 col-md-3 d-flex align-items-end gap-2">
            <button type="submit" class="btn btn-dark btn-sm w-100">Filtrar</button>
            <a href="{{ route('audit.index') }}" class="btn btn-outline-dark btn-sm w-100">Limpiar</a>
        </div>
    </form>

    {{-- Tabla --}}
    <div class="table-responsive">
        <table class="table table-sm table-hover table-bordered m-0">
            <thead>
                <tr>
                    <th style="width:50px;" class="text-center">ID</th>
                    <th style="width:140px;">Fecha</th>
                    <th style="width:130px;">Usuario</th>
                    <th style="width:100px;" class="text-center">Acción</th>
                    <th style="width:120px;">Entidad</th>
                    <th>Descripción</th>
                    <th style="width:60px;" class="text-center">Ver</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td class="text-center text-muted">{{ $log->log_id }}</td>
                        <td style="font-size:0.65rem;">
                            {{ $log->created_at->format('d/m/Y') }}<br>
                            <span class="text-muted">{{ $log->created_at->format('H:i:s') }}</span>
                        </td>
                        <td class="fw-bold text-uppercase" style="font-size:0.65rem;">
                            {{ $log->user->name ?? '—' }}
                        </td>
                        <td class="text-center">
                            <span class="badge bg-{{ $log->actionColor() }}" style="font-size:0.58rem; border-radius:0;">
                                {{ $log->actionLabel() }}
                            </span>
                        </td>
                        <td style="font-size:0.65rem;">
                            <span style="border:1px solid #ccc; padding:1px 6px; background:#fafafa; text-transform:uppercase; font-size:0.6rem;">
                                {{ $log->model }} #{{ $log->model_id }}
                            </span>
                        </td>
                        <td style="font-size:0.7rem;">{{ $log->description ?? '—' }}</td>
                        <td class="text-center">
                            @if($log->changes)
                                <button class="btn btn-outline-dark btn-sm py-0 px-1"
                                        style="font-size:0.6rem;"
                                        data-bs-toggle="modal"
                                        data-bs-target="#changesModal{{ $log->log_id }}">
                                    <i class="bi bi-eye"></i>
                                </button>

                                {{-- Modal cambios --}}
                                <div class="modal fade" id="changesModal{{ $log->log_id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Cambios — {{ $log->model }} #{{ $log->model_id }}</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <table class="table table-sm table-bordered m-0">
                                                    <thead>
                                                        <tr>
                                                            <th style="font-size:0.65rem;">Campo</th>
                                                            <th style="font-size:0.65rem;">Antes</th>
                                                            <th style="font-size:0.65rem;">Después</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $after  = $log->changes['after']  ?? [];
                                                            $before = $log->changes['before'] ?? [];
                                                        @endphp

                                                        @if(empty($after))
                                                            <tr>
                                                                <td colspan="3" class="text-center text-muted py-3" style="font-size:0.75rem;">
                                                                    Sin detalles de cambios.
                                                                </td>
                                                            </tr>
                                                        @else
                                                            @foreach($after as $field => $newVal)
                                                                <tr>
                                                                    <td class="fw-bold text-uppercase" style="font-size:0.65rem;">{{ $field }}</td>
                                                                    <td class="text-danger" style="font-size:0.65rem;">{{ $before[$field] ?? '—' }}</td>
                                                                    <td class="text-success" style="font-size:0.65rem;">
                                                                        {{ is_array($newVal) ? json_encode($newVal) : $newVal }}
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted text-uppercase" style="font-size:0.75rem;">
                            No hay registros de auditoría.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Paginación --}}
    @if($logs->hasPages())
        <div class="mt-3">
            {{ $logs->links() }}
        </div>
    @endif
</div>
@endsection