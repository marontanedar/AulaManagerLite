@extends('layouts.app')

@section('content')
<div class="row">
    <!-- FILTROS (Lateral Izquierdo) -->
    <div class="col-md-2">
        <div class="main-card p-3">
            <h6 class="text-uppercase fw-bold small">Filtros:</h6>
            <hr>
            <p class="small mb-1 text-uppercase">Categoría:</p>
            <ul class="list-unstyled">
                <li><a href="{{ route('reservations.index') }}" class="text-decoration-none text-dark small">Todas</a></li>
                @foreach($categories as $cat)
                    <li><a href="?category={{ $cat->category_id }}" class="text-decoration-none text-dark small">{{ $cat->name }}</a></li>
                @endforeach
            </ul>
            <p class="small mb-1 text-uppercase mt-3">Fecha:</p>
            <input type="date" class="form-control form-control-sm" value="{{ date('Y-m-d') }}">
        </div>
    </div>

    <!-- CALENDARIO (Centro) -->
    <div class="col-md-10">
        <div class="main-card">
            <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                <span class="text-uppercase fw-bold">{{ now()->format('l, d F Y') }}</span>
                <span class="small">Prof. {{ auth()->user()->name }}</span>
            </div>

            <table class="table table-bordered text-center align-middle">
                <thead>
                    <tr class="table-light">
                        <th style="width: 100px;">HORA</th>
                        @foreach($resources as $res)
                            <th>{{ $res->name }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($hours as $hour)
                    <tr>
                        <td class="fw-bold">{{ $hour }}</td>
                        @foreach($resources as $res)
                            @php
                                // Lógica temporal: Si el estado del recurso es 3 (Averiado)
                                $isAveriado = $res->status == 3;
                            @endphp
                            
                            <td class="{{ $isAveriado ? 'bg-light text-muted' : '' }}">
                                @if($isAveriado)
                                    <span class="badge bg-secondary text-uppercase" style="font-size: 0.6rem;">Averiado</span>
                                @else
                                    <div class="d-grid">
                                        <!-- Simulamos algunos 'Libres' y 'Reservados' para la prueba -->
                                        @if(rand(0,1))
                                            <button class="btn btn-sm btn-outline-success text-uppercase" style="font-size: 0.7rem;">Libre</button>
                                        @else
                                            <span class="text-danger fw-bold text-uppercase" style="font-size: 0.7rem;">Reservado</span>
                                        @endif
                                    </div>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection