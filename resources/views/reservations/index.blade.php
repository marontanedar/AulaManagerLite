{{-- resources/views/reservations/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="d-flex" style="min-height: calc(100vh - 60px)">

    {{-- SIDEBAR — lista de espacios --}}
    <aside style="width:240px;min-width:240px;border-right:1px solid #e0e0e0;background:#fafafa;overflow-y:auto">

        {{-- Date picker --}}
        <div style="padding:1rem;border-bottom:1px solid #e0e0e0">
            <p class="form-label-upper mb-2">Fecha</p>
            <input type="date" id="date-picker" class="form-control form-control-sm"
                   value="{{ $date }}"
                   onchange="window.location.href='{{ route('reservations.index') }}?date='+this.value+'&space={{ request('space') }}'">
        </div>

        {{-- Lista de espacios --}}
        <div>
            @forelse($spaces as $space)
                @php
                    $isActive   = $selectedSpace && $selectedSpace->space_id === $space->space_id;
                    $freeCount  = collect($hours)->filter(function($hour) use ($space) {
                        $h = (int) $hour;
                        return !$space->reservations->first(fn($r) =>
                            (int) substr($r->start, 0, 2) <= $h && (int) substr($r->end, 0, 2) > $h
                        );
                    })->count();
                    $totalSlots = count($hours);
                @endphp
                <a href="{{ route('reservations.index', ['date' => $date, 'space' => $space->space_id]) }}"
                   class="d-block text-decoration-none"
                   style="padding:10px 12px;border-bottom:1px solid #efefef;
                          border-left:{{ $isActive ? '3px solid #111' : '3px solid transparent' }};
                          background:{{ $isActive ? '#fff' : 'transparent' }}">
                    <div class="d-flex align-items-start justify-content-between gap-1">
                        <span style="font-size:0.82rem;font-weight:{{ $isActive ? '500' : '400' }};color:#111">
                            {{ $space->name }}
                        </span>
                    </div>
                    <div style="font-size:0.72rem;color:#999;margin-top:2px">
                        {{ $space->category?->name }} · {{ $space->capacity }} pl.
                    </div>
                    <div class="mt-1">
                        @if($freeCount === $totalSlots)
                            <span style="font-size:0.7rem;padding:2px 7px;border-radius:20px;background:#e1f5ee;color:#085041">
                                Libre todo el día
                            </span>
                        @elseif($freeCount === 0)
                            <span style="font-size:0.7rem;padding:2px 7px;border-radius:20px;background:#fcebeb;color:#791f1f">
                                Sin disponibilidad
                            </span>
                        @else
                            <span style="font-size:0.7rem;padding:2px 7px;border-radius:20px;background:#e1f5ee;color:#085041">
                                {{ $freeCount }} libres
                            </span>
                        @endif
                    </div>
                </a>
            @empty
                <div style="padding:1rem;font-size:0.8rem;color:#aaa">No hay espacios disponibles.</div>
            @endforelse
        </div>
    </aside>

    {{-- DETALLE — timeline del espacio seleccionado --}}
    <div class="flex-grow-1 d-flex flex-column overflow-hidden">

        @if($selectedSpace)

            {{-- Cabecera del espacio --}}
            <div style="padding:.875rem 1.25rem;border-bottom:1px solid #e0e0e0;background:#fff">
                <div class="d-flex align-items-start justify-content-between gap-3">
                    <div>
                        <div class="d-flex align-items-center gap-2">
                            <h1 class="section-title mb-0">{{ $selectedSpace->name }}</h1>
                            @if($selectedSpace->status === 2)
                                <span class="badge-status badge-mantenimiento">Mantenimiento</span>
                            @elseif($selectedSpace->status === 3)
                                <span class="badge-status badge-averiado">Fuera de servicio</span>
                            @else
                                <span class="badge-status badge-disponible">Disponible</span>
                            @endif
                        </div>
                        <div style="font-size:0.78rem;color:#999;margin-top:3px">
                            {{ $selectedSpace->category?->name }}
                            &middot; {{ $selectedSpace->capacity }} plazas
                            @if($selectedSpace->description)
                                &middot; {{ $selectedSpace->description }}
                            @endif
                        </div>
                        {{-- Recursos globales disponibles --}}
                        @if($resources->count())
                            <div class="d-flex flex-wrap gap-1 mt-2">
                                @foreach($resources as $resource)
                                    <span style="font-size:0.7rem;padding:2px 8px;border-radius:20px;border:1px solid #ddd;color:#666">
                                        {{ $resource->name }}
                                    </span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <button class="btn btn-sm btn-dark"
                            style="font-size:0.78rem;white-space:nowrap"
                            onclick="openModal({{ $selectedSpace->space_id }}, '{{ addslashes($selectedSpace->name) }}', '08:00')">
                        + Reservar espacio
                    </button>
                </div>
            </div>

            {{-- Alertas --}}
            <div style="padding:.75rem 1.25rem 0">
                @if(session('success'))
                    <div class="alert alert-success py-2 mb-2">{{ session('success') }}</div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger py-2 mb-2">{{ $errors->first() }}</div>
                @endif
            </div>

            {{-- Timeline --}}
            <div class="flex-grow-1 overflow-auto" style="padding:1rem 1.25rem">
                <p class="form-label-upper mb-2">
                    {{ \Carbon\Carbon::parse($date)->isoFormat('dddd, D [de] MMMM YYYY') }}
                </p>

                <div class="d-flex flex-column gap-1">
                    @foreach($hours as $hour)
                        @php
                            $h = (int) $hour;
                            $reservation = $selectedSpace->reservations->first(function($r) use ($h) {
                                return (int) substr($r->start, 0, 2) <= $h
                                    && (int) substr($r->end, 0, 2) > $h;
                            });
                        @endphp

                        @if($reservation)
                            {{-- Franja ocupada --}}
                            <div class="d-flex align-items-center gap-3 px-3 py-2"
                                 style="background:#e6f1fb;border-left:3px solid #185fa5;min-height:52px">
                                <span style="min-width:44px;font-size:0.78rem;color:#185fa5;font-weight:500">
                                    {{ $hour }}
                                </span>
                                <div class="flex-grow-1">
                                    <div style="font-size:0.82rem;font-weight:500;color:#0c447c">
                                        {{ $reservation->notes ?? 'Reservado' }}
                                    </div>
                                    <div style="font-size:0.72rem;color:#185fa5;margin-top:2px">
                                        {{ $reservation->user->name ?? '' }}
                                        &middot;
                                        {{ substr($reservation->start, 0, 5) }} – {{ substr($reservation->end, 0, 5) }}
                                    </div>
                                    @if($reservation->resources->count())
                                        <div class="d-flex flex-wrap gap-1 mt-1">
                                            @foreach($reservation->resources as $res)
                                                <span style="font-size:0.68rem;padding:1px 6px;border-radius:20px;background:#b5d4f4;color:#0c447c">
                                                    {{ $res->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                                @if($reservation->user_id === auth()->id() || auth()->user()->isAdmin())
                                    <form method="POST"
                                          action="{{ route('reservations.destroy', $reservation->reservation_id) }}"
                                          onsubmit="return confirm('¿Cancelar esta reserva?')"
                                          class="mb-0">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm"
                                                style="font-size:0.7rem;padding:3px 10px;color:#a32d2d;border:1px solid #f09595">
                                            Cancelar
                                        </button>
                                    </form>
                                @endif
                            </div>

                        @else
                            {{-- Franja libre --}}
                            <div class="d-flex align-items-center gap-3 px-3 py-2 slot-free"
                                 style="border:1px dashed #ddd;min-height:52px;cursor:pointer"
                                 onclick="openModal({{ $selectedSpace->space_id }}, '{{ addslashes($selectedSpace->name) }}', '{{ $hour }}')">
                                <span style="min-width:44px;font-size:0.78rem;color:#aaa">{{ $hour }}</span>
                                <span style="font-size:0.82rem;font-weight:500;color:#1d9e75">+ Disponible</span>
                            </div>
                        @endif

                    @endforeach
                </div>
            </div>

        @else
            {{-- Estado vacío --}}
            <div class="d-flex flex-grow-1 align-items-center justify-content-center flex-column gap-2"
                 style="color:#ccc">
                <svg width="40" height="40" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="1">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p style="font-size:0.85rem">Selecciona un espacio del panel izquierdo</p>
            </div>
        @endif

    </div>
</div>

{{-- MODAL DE RESERVA --}}
<div class="modal fade" id="reservaModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h5 class="modal-title" style="font-size:0.9rem;font-weight:500" id="modal-title">
                    Nueva reserva
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('reservations.store') }}">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="space_id" id="modal-space-id">
                    <input type="hidden" name="date" value="{{ $date }}">

                    <div class="mb-3">
                        <label class="form-label-upper">Espacio</label>
                        <input type="text" class="form-control form-control-sm"
                               id="modal-space-name" readonly style="background:#f5f5f5">
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col">
                            <label class="form-label-upper">Hora inicio</label>
                            <input type="time" name="start" id="modal-start"
                                   class="form-control form-control-sm"
                                   min="08:00" max="21:00" required>
                        </div>
                        <div class="col">
                            <label class="form-label-upper">Hora fin</label>
                            <input type="time" name="end" id="modal-end"
                                   class="form-control form-control-sm"
                                   min="08:00" max="21:00" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label-upper">
                            Motivo
                            <span style="font-weight:400;text-transform:none">(opcional)</span>
                        </label>
                        <input type="text" name="notes" class="form-control form-control-sm"
                               placeholder="ej: clase de biología">
                    </div>

                    @if($resources->count())
                        <div class="mb-1">
                            <label class="form-label-upper">
                                Recursos adicionales
                                <span style="font-weight:400;text-transform:none">(opcional)</span>
                            </label>
                            <div class="d-flex flex-column gap-2 mt-2"
                                 style="max-height:150px;overflow-y:auto;padding-right:4px">
                                @foreach($resources as $resource)
                                    <label class="d-flex align-items-center gap-2"
                                           style="font-size:0.8rem;cursor:pointer">
                                        <input type="checkbox" name="resource_ids[]"
                                               value="{{ $resource->resource_id }}">
                                        <span style="color:#111">{{ $resource->name }}</span>
                                        <span style="font-size:0.7rem;color:#aaa;margin-left:auto">
                                            {{ $resource->category?->name }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-sm btn-outline-secondary"
                            data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-sm btn-dark">Confirmar reserva</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.slot-free:hover { background:#f0faf5 !important; border-color:#5dcaa5 !important; }
</style>

<script>
function openModal(spaceId, spaceName, hour) {
    document.getElementById('modal-space-id').value   = spaceId;
    document.getElementById('modal-space-name').value = spaceName;
    document.getElementById('modal-start').value      = hour;

    const [h, m] = hour.split(':');
    const endH   = String(Math.min(parseInt(h) + 1, 21)).padStart(2, '0');
    document.getElementById('modal-end').value = endH + ':' + m;

    document.querySelectorAll('#reservaModal input[type=checkbox]')
            .forEach(cb => cb.checked = false);

    new bootstrap.Modal(document.getElementById('reservaModal')).show();
}
</script>
@endsection