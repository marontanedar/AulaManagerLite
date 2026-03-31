@extends('layouts.app')

@section('content')
<div class="row g-3">

    {{-- Sidebar filtros --}}
    <div class="col-12 col-md-2">
        <div class="card-panel h-100">
            <p class="form-label-upper mb-1">Categoría</p>
            <hr class="mt-0 mb-2" style="border-color:#111;">
            <ul class="list-unstyled mb-3">
                <li class="mb-1">
                    <a href="{{ route('reservations.index', ['date' => $date]) }}"
                       class="text-decoration-none small fw-bold {{ !request('category') ? 'text-dark' : 'text-muted' }}">
                       ● Todas
                    </a>
                </li>
                @foreach($categories as $cat)
                    <li class="mb-1">
                        <a href="?category={{ $cat->category_id }}&date={{ $date }}"
                           class="text-decoration-none small {{ request('category') == $cat->category_id ? 'fw-bold text-dark' : 'text-muted' }}">
                           ○ {{ $cat->name }}
                        </a>
                    </li>
                @endforeach
            </ul>

            <p class="form-label-upper mb-1">Fecha</p>
            <form action="{{ route('reservations.index') }}" method="GET">
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                <input type="date" name="date" class="form-control form-control-sm"
                       value="{{ $date }}" onchange="this.form.submit()">
            </form>
        </div>
    </div>

    {{-- Tabla calendario --}}
    <div class="col-12 col-md-10">
        <div class="card-panel">
            <div class="section-header">
                <div>
                    <h5 class="section-title">
                        {{ \Carbon\Carbon::parse($date)->translatedFormat('l, d F Y') }}
                    </h5>
                    <span class="badge bg-dark text-uppercase" style="font-size:0.55rem; border-radius:0;">Vista Diaria</span>
                </div>
                <div class="text-end">
                    <span class="form-label-upper d-block">Usuario</span>
                    <span class="text-muted" style="font-size:0.7rem;">{{ auth()->user()->name }}</span>
                </div>
            </div>

            <div class="table-responsive" style="max-height:65vh; overflow-y:auto;">
                <table class="table table-bordered table-sm text-center align-middle m-0">
                    @if($resources->count() > 0)
                        <thead class="sticky-top">
                            <tr>
                                <th style="width:80px; background:#eee;">HORA</th>
                                @foreach($resources as $res)
                                    <th style="background:#eee; min-width:130px;">
                                        {{ $res->name }}
                                        @if($res->status == 2)
                                            <span class="d-block" style="font-size:0.55rem; color:#e6a817;">⚙ MANT.</span>
                                        @elseif($res->status == 3)
                                            <span class="d-block" style="font-size:0.55rem; color:#dc3545;">✕ F.SERVICIO</span>
                                        @endif
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($hours as $hour)
                            <tr>
                                <td class="fw-bold bg-light" style="font-size:0.7rem;">{{ $hour }}</td>
                                @foreach($resources as $res)
                                    @php
                                        $noDisp = $res->status != 1;
                                        $reserva = $res->reservations->first(fn($r) =>
                                            $hour >= substr($r->start,0,5) && $hour < substr($r->end,0,5)
                                        );
                                    @endphp
                                    <td class="{{ $noDisp ? 'bg-light' : '' }}" style="padding:3px;">
                                        @if($noDisp)
                                            <span class="text-muted" style="font-size:0.6rem;">— N/D —</span>
                                        @elseif($reserva)
                                            <div style="background:#fff0f0; border:1px solid #dc3545; padding:3px 5px;">
                                                <span class="text-danger fw-bold d-block" style="font-size:0.6rem;">RESERVADO</span>
                                                <span style="font-size:0.58rem;">{{ substr($reserva->start,0,5) }}–{{ substr($reserva->end,0,5) }}</span>
                                            </div>
                                        @else
                                            <button class="btn btn-outline-success w-100 py-0 fw-bold"
                                                    style="font-size:0.6rem; border-style:dashed;"
                                                    onclick="openModal('{{ $res->resource_id }}','{{ addslashes($res->name) }}','{{ $hour }}')">
                                                + Libre
                                            </button>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    @else
                        <tbody>
                            <tr>
                                <td colspan="100%" class="py-5 text-center">
                                    <p class="text-muted fw-bold text-uppercase mb-2" style="font-size:0.75rem;">Sin recursos en esta categoría</p>
                                    <a href="{{ route('reservations.index', ['date' => $date]) }}" class="btn btn-dark btn-sm">Ver todos</a>
                                </td>
                            </tr>
                        </tbody>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Modal reserva --}}
<div class="modal fade" id="reservaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Solicitud de Reserva</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('reservations.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="resource_id" id="modalResId">
                    <input type="hidden" name="date" value="{{ $date }}">

                    <div class="p-2 mb-3 text-center" style="background:#f5f5f5; border:1.5px solid #111;">
                        <span class="form-label-upper d-block">Recurso</span>
                        <span id="modalResName" class="fw-bold" style="font-size:0.9rem;"></span>
                        <hr class="my-1" style="border-color:#111;">
                        <span style="font-size:0.7rem; font-weight:600;">{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</span>
                    </div>

                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label-upper">Hora entrada</label>
                            <input type="time" name="start" id="modalStartTime" class="form-control" step="3600" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label-upper">Hora salida</label>
                            <input type="time" name="end" id="modalEndTime" class="form-control" step="3600" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-dark btn-sm" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-dark btn-sm px-4">Confirmar</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openModal(id, name, hour) {
    document.getElementById('modalResId').value = id;
    document.getElementById('modalResName').innerText = name;
    document.getElementById('modalStartTime').value = hour;
    let h = parseInt(hour.split(':')[0]) + 1;
    if (h <= 21) document.getElementById('modalEndTime').value = (h < 10 ? '0'+h : h) + ':00';
    new bootstrap.Modal(document.getElementById('reservaModal')).show();
}
</script>
@endpush
@endsection