@extends('layouts.app')

@if($errors->any())
    <div class="alert alert-danger rounded-0 border-dark">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@section('content')
<div class="row">
    <div class="col-md-2">
        <div class="main-card p-3 shadow-sm border border-dark bg-white">
            <h6 class="text-uppercase fw-bold small">Filtros:</h6>
            <hr class="border-dark">
            
            <p class="small mb-1 text-uppercase fw-bold">Categoría:</p>
            <ul class="list-unstyled mb-4">
                <li class="mb-1">
                    <a href="{{ route('reservations.index', ['date' => $date]) }}" 
                       class="text-decoration-none {{ !request('category') ? 'fw-bold text-primary' : 'text-dark' }} small">
                       ● Todas
                    </a>
                </li>
                @foreach($categories as $cat)
                    <li class="mb-1">
                        <a href="?category={{ $cat->category_id }}&date={{ $date }}" 
                           class="text-decoration-none {{ request('category') == $cat->category_id ? 'fw-bold text-primary' : 'text-dark' }} small">
                           ○ {{ $cat->name }}
                        </a>
                    </li>
                @endforeach
            </ul>

            <p class="small mb-1 text-uppercase fw-bold">Fecha:</p>
            <form action="{{ route('reservations.index') }}" method="GET" id="dateForm">
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                <input type="date" name="date" class="form-control form-control-sm border-dark rounded-0" 
                       value="{{ $date }}" onchange="this.form.submit()">
            </form>
        </div>
    </div>

    <div class="col-md-10">
        <div class="main-card p-4 border border-dark bg-white shadow-sm">
            <div class="d-flex justify-content-between align-items-center mb-3 border-bottom border-dark pb-2">
                <div>
                    <h5 class="text-uppercase fw-bold m-0">{{ \Carbon\Carbon::parse($date)->translatedFormat('l, d F Y') }}</h5>
                    <span class="badge bg-dark rounded-0 text-uppercase" style="font-size: 0.6rem;">Vista Diaria</span>
                </div>
                <div class="text-end">
                    <span class="small d-block text-uppercase fw-bold">Profesor:</span>
                    <span class="small text-muted">{{ auth()->user()->name }}</span>
                </div>
            </div>

            <div class="table-responsive" style="max-height: 700px; overflow-y: auto;">
                <table class="table table-bordered text-center align-middle m-0">
                    @if($resources->count() > 0)
                        <thead class="table-light sticky-top">
                            <tr>
                                <th style="width: 100px; border: 2px solid #000; background: #eee;">HORA</th>
                                @foreach($resources as $res)
                                    <th style="border: 2px solid #000; background: #eee;">{{ $res->name }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($hours as $hour)
                            <tr>
                                <td class="fw-bold bg-light" style="border: 1px solid #000;">{{ $hour }}</td>
                                @foreach($resources as $res)
                                    @php
                                        $isAveriado = $res->status == 3;
                                        // Buscamos si existe una reserva que cubra este minuto/hora
                                        $reserva = $res->reservations->first(function($r) use ($hour) {
                                            return $hour >= substr($r->start, 0, 5) && $hour < substr($r->end, 0, 5);
                                        });
                                    @endphp
                                    
                                    <td style="border: 1px solid #000; min-width: 120px;" class="{{ $isAveriado ? 'bg-secondary-subtle' : '' }}">
                                        @if($isAveriado)
                                            <span class="text-muted small text-uppercase fw-bold" style="font-size: 0.6rem;">⚠️ Averiado</span>
                                        @elseif($reserva)
                                            <div class="bg-danger-subtle p-1 border border-danger">
                                                <span class="text-danger fw-bold text-uppercase d-block" style="font-size: 0.65rem;">Reservado</span>
                                                <span class="d-block text-dark fw-bold" style="font-size: 0.6rem;">
                                                    {{ substr($reserva->start, 0, 5) }} - {{ substr($reserva->end, 0, 5) }}
                                                </span>
                                            </div>
                                        @else
                                            <div class="d-grid px-2">
                                                <button class="btn btn-sm btn-outline-success text-uppercase fw-bold p-0" 
                                                        style="font-size: 0.65rem; border-radius: 0; border-style: dashed;"
                                                        onclick="openModal('{{ $res->resource_id }}', '{{ $res->name }}', '{{ $hour }}')">
                                                    + Libre
                                                </button>
                                            </div>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    @else
                        <tbody>
                            <tr>
                                <td colspan="100%" class="py-5 bg-white border border-dark">
                                    <div class="text-center py-5">
                                        <h1 class="display-1 text-muted opacity-25">📂</h1>
                                        <h5 class="fw-bold text-uppercase mt-3">Sin recursos disponibles</h5>
                                        <p class="text-muted small">No hay elementos registrados en la categoría seleccionada.</p>
                                        <a href="{{ route('reservations.index', ['date' => $date]) }}" class="btn btn-dark rounded-0 text-uppercase fw-bold px-4 mt-2">
                                            Ver todo el inventario
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal reserva -->
<div class="modal fade" id="reservaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border border-dark border-3 rounded-0 shadow-lg">
            <div class="modal-header bg-dark text-white rounded-0">
                <h5 class="modal-title text-uppercase fw-bold small">Solicitud de Reserva</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form id="reservaForm" action="{{ route('reservations.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="resource_id" id="modalResId">
                    <input type="hidden" name="date" value="{{ $date }}">

                    <div class="mb-3 p-2 bg-light border border-dark text-center">
                        <span class="small text-uppercase d-block fw-bold">Recurso:</span>
                        <span id="modalResName" class="h5 fw-bold text-primary"></span>
                        <hr class="my-1 border-dark">
                        <span class="small fw-bold">Fecha: {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</span>
                    </div>

                    <div class="row g-3">
                        <div class="col-6">
                            <label class="small text-uppercase fw-bold">Hora Entrada:</label>
                            <input type="time" name="start" id="modalStartTime" 
                                   class="form-control border-dark rounded-0 shadow-sm" step="60" required>
                        </div>
                        <div class="col-6">
                            <label class="small text-uppercase fw-bold">Hora Salida:</label>
                            <input type="time" name="end" id="modalEndTime" 
                                   class="form-control border-dark rounded-0 shadow-sm" step="60" required>
                        </div>
                    </div>

                    <div class="mt-3">
                        <label class="small text-uppercase fw-bold">Observaciones:</label>
                        <textarea name="remarks" class="form-control border-dark rounded-0" rows="2" 
                                  placeholder="Motivo de la reserva..."></textarea>
                    </div>
                </div>

                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-dark text-uppercase fw-bold rounded-0" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-dark text-uppercase fw-bold rounded-0 px-4">Confirmar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openModal(id, name, hour) {
        document.getElementById('modalResId').value = id;
        document.getElementById('modalResName').innerText = name;
        document.getElementById('modalStartTime').value = hour;
        
        // Sugerir hora de fin (1 hora después)
        let parts = hour.split(':');
        let endHour = parseInt(parts[0]) + 1;
        document.getElementById('modalEndTime').value = (endHour < 10 ? '0' + endHour : endHour) + ':' + parts[1];

        var myModal = new bootstrap.Modal(document.getElementById('reservaModal'));
        myModal.show();
    }
</script>
@endsection