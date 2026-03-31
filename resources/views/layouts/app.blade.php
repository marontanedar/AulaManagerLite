<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>AulaManager Lite</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        /* ── Variables globales ── */
        :root {
            --color-black:   #111111;
            --color-gray:    #6c757d;
            --color-light:   #f5f5f5;
            --color-white:   #ffffff;
            --color-success: #198754;
            --color-warning: #e6a817;
            --color-danger:  #dc3545;
            --color-border:  #111111;
            --font-main:     'Montserrat', sans-serif;
            --fs-xs:   0.65rem;
            --fs-sm:   0.75rem;
            --fs-base: 0.85rem;
            --fs-md:   0.95rem;
            --radius:  0;
        }

        /* ── Base ── */
        *, *::before, *::after { box-sizing: border-box; }

        body {
            font-family: var(--font-main);
            background-color: var(--color-light);
            font-size: var(--fs-base);
            margin: 0;
            color: var(--color-black);
        }

        /* ── Navbar ── */
        .navbar {
            background-color: var(--color-white) !important;
            border-bottom: 2px solid var(--color-black);
            padding: 0.4rem 1.5rem;
            min-height: 70px;
            position: sticky;
            top: 0;
            z-index: 1050;
        }

        .navbar-brand img {
            height: 50px;
            width: auto;
        }

        .nav-link {
            font-weight: 700;
            text-transform: uppercase;
            font-size: var(--fs-xs);
            letter-spacing: 1px;
            color: var(--color-black) !important;
            padding: 6px 12px !important;
            border-radius: var(--radius);
            transition: background .15s, color .15s;
        }

        .nav-link:hover,
        .nav-link.active {
            background-color: var(--color-black) !important;
            color: var(--color-white) !important;
        }

        /* ── Botones base ── */
        .btn {
            border-radius: var(--radius);
            font-weight: 700;
            text-transform: uppercase;
            font-size: var(--fs-xs);
            letter-spacing: 0.5px;
        }

        .btn-dark    { background: var(--color-black); border-color: var(--color-black); }
        .btn-outline-dark { border: 1.5px solid var(--color-black) !important; color: var(--color-black); }
        .btn-outline-dark:hover { background: var(--color-black); color: var(--color-white); }

        /* ── Tarjeta base ── */
        .card-panel {
            background: var(--color-white);
            border: 1.5px solid var(--color-black);
            padding: 1.25rem;
        }

        /* ── Cabecera de sección ── */
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1.5px solid var(--color-black);
            padding-bottom: 0.6rem;
            margin-bottom: 1rem;
        }

        .section-title {
            font-size: var(--fs-md);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0;
        }

        .section-subtitle {
            font-size: var(--fs-xs);
            color: var(--color-gray);
            text-transform: uppercase;
            margin: 0;
        }

        /* ── Tablas ── */
        .table thead th {
            font-size: var(--fs-xs);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background: var(--color-light);
            border-color: #ccc;
            padding: 0.5rem 0.6rem;
        }

        .table tbody td {
            font-size: var(--fs-sm);
            padding: 0.45rem 0.6rem;
            vertical-align: middle;
        }

        /* ── Badges de estado ── */
        .badge-status {
            font-size: var(--fs-xs);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 2px 8px;
            display: inline-block;
        }

        .badge-disponible  { color: var(--color-success); }
        .badge-mantenimiento { color: var(--color-warning); }
        .badge-averiado    { color: var(--color-danger); }

        /* ── Formularios ── */
        .form-control,
        .form-select {
            border-radius: var(--radius);
            border: 1.5px solid var(--color-black);
            font-size: var(--fs-sm);
            font-family: var(--font-main);
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--color-black);
            box-shadow: 0 0 0 2px rgba(17,17,17,.15);
        }

        .form-label-upper {
            font-size: var(--fs-xs);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
            display: block;
        }

        /* ── Alertas ── */
        .alert {
            border-radius: var(--radius);
            font-size: var(--fs-sm);
            font-weight: 600;
            border-width: 1.5px;
        }

        /* ── Modal ── */
        .modal-content  { border-radius: var(--radius); border: 2px solid var(--color-black); }
        .modal-header   { background: var(--color-black); color: var(--color-white); border-radius: var(--radius); }
        .modal-title    { font-size: var(--fs-sm); font-weight: 700; text-transform: uppercase; letter-spacing: 1px; }
        .modal-backdrop { z-index: 1040 !important; }
        .modal          { z-index: 1060 !important; }

        /* ── Footer ── */
        footer {
            border-top: 2px solid var(--color-black);
            padding: 14px 0;
            background: var(--color-white);
            margin-top: 2rem;
            font-size: var(--fs-xs);
            font-weight: 700;
            text-transform: uppercase;
            text-align: center;
        }

        /* ── Responsive ajustes ── */
        @media (max-width: 768px) {
            .navbar { min-height: 56px; padding: 0.3rem 1rem; }
            .navbar-brand img { height: 36px; }
            .nav-link { font-size: 0.6rem; padding: 4px 8px !important; }
            .card-panel { padding: 0.85rem; }
            .section-title { font-size: var(--fs-base); }
        }
    </style>
    @stack('styles')
</head>
<body>

<nav class="navbar navbar-expand-lg">
    <div class="container-fluid d-flex justify-content-between align-items-center">

        <a class="navbar-brand" href="{{ url('/') }}">
            <img src="{{ asset('images/logo.png') }}" alt="AulaManager Lite">
        </a>

        <div class="d-flex align-items-center gap-1">
            @auth
            <ul class="navbar-nav flex-row gap-1 align-items-center">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">
                        <i class="bi bi-calendar3 me-1"></i>Inicio
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('reservations.user') ? 'active' : '' }}" href="{{ route('reservations.user') }}">
                        <i class="bi bi-bookmark me-1"></i>Mis Reservas
                    </a>
                </li>

                @if(auth()->user()->isAdmin())
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('resources.*') ? 'active' : '' }}" href="{{ route('resources.index') }}">
                        <i class="bi bi-box me-1"></i>Recursos
                    </a>
                </li>
                @endif
                
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('incidences.*') ? 'active' : '' }}" href="{{ route('incidences.index') }}">
                        <i class="bi bi-exclamation-triangle me-1"></i>Incidencias
                    </a>
                </li>
                
                @if(auth()->user()->isAdmin())
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('audit.*') ? 'active' : '' }}" href="{{ route('audit.index') }}">
                        <i class="bi bi-exclamation-triangle me-1"></i>Auditoria
                    </a>
                </li>
                @endif


                <li class="nav-item dropdown ms-1">
                    <button class="btn btn-outline-dark btn-sm dropdown-toggle px-3" data-bs-toggle="dropdown">
                        <i class="bi bi-person me-1"></i>{{ Auth::user()->name }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end border-dark shadow" style="border-radius:0; min-width: 140px;">
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item fw-bold text-danger" style="font-size: var(--fs-xs); text-transform: uppercase;">
                                    <i class="bi bi-box-arrow-right me-1"></i>Salir
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
            @endauth
        </div>
    </div>
</nav>

<main class="py-3">
    <div class="container-fluid px-3 px-md-4">

        {{-- Alertas globales --}}
        @if(session('success'))
            <div class="alert alert-success border-success mb-3">
                <i class="bi bi-check-circle me-1"></i>{{ session('success') }}
            </div>
        @endif

        @if(session('info'))
            <div class="alert alert-info border-info mb-3">
                <i class="bi bi-info-circle me-1"></i>{{ session('info') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger border-danger mb-3">
                <i class="bi bi-exclamation-circle me-1"></i>
                <ul class="mb-0 mt-1 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </div>
</main>

<footer>© 2026 AulaManager Lite — Gestión de Recursos Educativos</footer>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
@stack('scripts')
</body>
</html>