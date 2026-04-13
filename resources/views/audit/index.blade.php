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
            <label class="form-label-upper">Usuario</label>
            <select name="user_id" class="form-select form-select-sm">
                <option value="">Todos</option>
                @foreach($users as $user)
                    <option value="{{ $user->user_id }}" {{ request('user_id') == $user->user_id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-6 col-md-3">
            <label class="form-label-upper">Fecha</label>
            <input type="date" name="date" class="form-control form-control-sm"
                   value="{{ request('date') }}">
        </div>
        <div class="col-12 col-md-12 d-flex justify-content-end gap-2 mt-1">
            <button type="submit" class="btn btn-dark btn-sm px-4">Filtrar</button>
            <a href="{{ route('audit.index') }}" class="btn btn-outline-dark btn-sm px-4">Limpiar</a>
        </div>
    </form>

    {{-- Tabla --}}
    <div class="table-responsive">
        <table class="table table-sm table-hover table-bordered m-0">
            <thead>
                <tr>
                    <th style="width:50px;" class="text-center">ID</th>
                    <th style="width:140px;">Fecha</th>
                    <th style="width:140px;">Usuario</th>
                    <th style="width:100px;" class="text-center">Acción</th>
                    <th style="width:130px;">Entidad</th>
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
                            <span class="badge bg-{{ $log->actionColor() }}"
                                  style="font-size:0.58rem; border-radius:0;">
                                {{ $log->actionLabel() }}
                            </span>
                        </td>
                        <td style="font-size:0.65rem;">
                            <span style="border:1px solid #ccc; padding:1px 6px; background:#fafafa;
                                         text-transform:uppercase; font-size:0.6rem;">
                                {{ $log->model }} #{{ $log->model_id }}
                            </span>
                        </td>
                        <td style="font-size:0.7rem;">{{ $log->description ?? '—' }}</td>
                        <td class="text-center">
                            @if($log->changes)
                                {{--
                                    Un único modal reutilizable (#changesModal).
                                    Pasamos los datos via data-* para evitar generar
                                    30 modales en el DOM.
                                --}}
                                <button class="btn btn-outline-dark btn-sm py-0 px-1 btn-changes"
                                        style="font-size:0.6rem;"
                                        data-title="{{ $log->model }} #{{ $log->model_id }}"
                                        data-changes="{{ json_encode($log->changes) }}">
                                    <i class="bi bi-eye"></i>
                                </button>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted text-uppercase"
                            style="font-size:0.75rem;">
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

{{-- Modal único reutilizable --}}
<div class="modal fade" id="changesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius:0;">
            <div class="modal-header" style="border-radius:0;">
                <h5 class="modal-title section-title" id="changesModalTitle">Cambios</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0" id="changesModalBody">
                {{-- Se rellena por JS --}}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal      = document.getElementById('changesModal');
    const modalTitle = document.getElementById('changesModalTitle');
    const modalBody  = document.getElementById('changesModalBody');

    document.querySelectorAll('.btn-changes').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const title   = btn.dataset.title;
            const changes = JSON.parse(btn.dataset.changes);

            modalTitle.textContent = 'Cambios — ' + title;
            modalBody.innerHTML    = buildChangesTable(changes);

            new bootstrap.Modal(modal).show();
        });
    });

    function buildChangesTable(changes) {
        const before = changes.before || {};
        const after  = changes.after  || {};

        // Si no hay estructura before/after, mostramos el JSON completo formateado
        if (Object.keys(after).length === 0 && Object.keys(before).length === 0) {
            return '<pre class="p-3 m-0" style="font-size:0.7rem; background:#fafafa;">'
                + JSON.stringify(changes, null, 2)
                + '</pre>';
        }

        // Si solo hay after (ej: created) usamos sus keys
        const fields = Object.keys(Object.keys(after).length ? after : before);

        if (fields.length === 0) {
            return '<p class="text-center text-muted py-3 m-0" style="font-size:0.75rem;">Sin detalles de cambios.</p>';
        }

        let rows = '';
        fields.forEach(function (field) {
            const oldVal = before[field] !== undefined ? before[field] : '—';
            const newVal = after[field]  !== undefined ? after[field]  : '—';

            const oldStr = typeof oldVal === 'object' ? JSON.stringify(oldVal) : oldVal;
            const newStr = typeof newVal === 'object' ? JSON.stringify(newVal) : newVal;

            // Resaltar solo si hay before Y after y son distintos
            const changed   = Object.keys(before).length > 0 && String(oldStr) !== String(newStr);
            const oldClass  = changed ? 'text-danger'  : '';
            const newClass  = changed ? 'text-success' : '';

            rows += `<tr>
                <td class="fw-bold text-uppercase" style="font-size:0.65rem; width:160px;">${escHtml(field)}</td>
                <td class="${oldClass}" style="font-size:0.65rem;">${escHtml(oldStr)}</td>
                <td class="${newClass}" style="font-size:0.65rem;">${escHtml(newStr)}</td>
            </tr>`;
        });

        return `<div class="table-responsive">
            <table class="table table-sm table-bordered m-0">
                <thead>
                    <tr>
                        <th style="font-size:0.65rem; width:160px;">Campo</th>
                        <th style="font-size:0.65rem;">Antes</th>
                        <th style="font-size:0.65rem;">Después</th>
                    </tr>
                </thead>
                <tbody>${rows}</tbody>
            </table>
        </div>`;
    }

    function escHtml(str) {
        if (str === null || str === undefined) return '—';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }
});
</script>
@endpush
@endsection