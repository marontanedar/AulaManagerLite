<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>AulaManager Lite</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #f8f9fa;
            font-size: 0.85rem;
            margin: 0;
        }

        /* NAVBAR: Fija, sólida y con el logo visible */
        .navbar {
            border-bottom: 2px solid #000 !important;
            background-color: #fff !important; 
            padding: 0.5rem 2rem !important; 
            min-height: 100px;
            position: sticky; 
            top: 0;
            z-index: 1050 !important; 
        }

        .navbar-brand img {
            height: 80px !important; 
            width: auto;
            display: block;
        }

        /* MENÚ NAVEGACIÓN */
        .nav-link {
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 1px;
            color: #000 !important;
            padding: 8px 15px !important;
        }

        .nav-link.active {
            background-color: #000 !important;
            color: #fff !important;
        }

        /* Dropdown Admin */
        .btn-outline-dark {
            border: 2px solid #000 !important;
            border-radius: 0 !important;
            font-weight: 700;
            text-transform: uppercase;
        }

        /* FIX PARA MODALES: Asegura que el fondo oscuro no bloquee la pantalla */
        .modal-backdrop {
            z-index: 1040 !important;
        }
        .modal {
            z-index: 1060 !important;
        }

        footer {
            border-top: 2px solid #000;
            padding: 20px 0;
            background: #fff;
            margin-top: 40px;
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

            <div class="d-flex align-items-center">
                <ul class="navbar-nav flex-row gap-2">
                    @auth
                        <li class="nav-item"><a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">Inicio</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Mis Reservas</a></li>
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('resources.*') ? 'active' : '' }}" href="{{ route('resources.index') }}">Recursos</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Incidencias</a></li>
                        
                        <li class="nav-item dropdown ms-2">
                            <button class="btn btn-outline-dark btn-sm dropdown-toggle px-3" data-bs-toggle="dropdown">
                                {{ Auth::user()->name }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end border-dark rounded-0 shadow">
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item small fw-bold text-danger">SALIR</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        <div class="container-fluid px-4">
            @yield('content')
        </div>
    </main>

    <footer class="text-center">
        <small class="fw-bold text-uppercase">© 2026 AULAMANAGER LITE — GESTIÓN DE RECURSOS</small>
    </footer>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

    @stack('scripts')
</body>
</html>