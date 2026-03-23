<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Gestión Reservas') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body { background-color: #f8f9fa; }
        .navbar { background-color: #fff; border-bottom: 1px solid #dee2e6; }
        .main-card { border: 1px solid #000; padding: 20px; background: #fff; }
        .main-card h2 { text-transform: uppercase; font-size: 1.2rem; margin-bottom: 20px;}
        .table thead th { border-bottom: 1px solid #000; text-transform: uppercase; font-size: 0.8rem; }
        .table tbody td { font-size: 0.9rem; }
    </style>
  </head>
  <body>

    <nav class="navbar navbar-expand-lg">
      <div class="container-fluid">
        <a class="navbar-brand text-uppercase" href="#">Logo Institución</a>
        <div class="collapse navbar-collapse">
          <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link" href="{{ route('reservations.index') }}">Reservas</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('resources.index') }}">Recursos</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('incidences.index') }}">Incidencias</a>
            </li>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <div class="container mt-4">
        
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content')
        
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>