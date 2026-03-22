<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Aula Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; height: 100vh; display: flex; align-items: center; justify-content: center; }
        .login-card { border: 1px solid #000; background: #fff; width: 100%; max-width: 400px; padding: 40px; text-align: center; }
        .login-card h1 { font-size: 1rem; text-transform: uppercase; border-bottom: 1px solid #000; padding-bottom: 10px; margin-bottom: 30px; }
        .form-control { border-radius: 0; border: 1px solid #000; margin-bottom: 15px; }
        .btn-login { border-radius: 0; border: 1px solid #000; background: #fff; width: 100%; text-transform: uppercase; font-weight: bold; }
        .btn-login:hover { background: #000; color: #fff; }
        .forgot-link { font-size: 0.7rem; text-transform: uppercase; text-decoration: none; color: #666; display: block; margin-top: 20px; }
    </style>
</head>
<body>

<div class="login-card">
    <h1>Iniciar Sesión</h1>

    <form action="{{ route('login.post') }}" method="POST">
        @csrf
        <div class="mb-3 text-start">
            <label class="small text-uppercase">Email:</label>
            <input type="email" name="email" class="form-control" placeholder="usuario@ejemplo.com" required value="{{ old('email') }}">
        </div>

        <div class="mb-3 text-start">
            <label class="small text-uppercase">Contraseña:</label>
            <input type="password" name="password" class="form-control" placeholder="************" required>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger py-1 small">{{ $errors->first() }}</div>
        @endif

        <button type="submit" class="btn btn-login">Entrar</button>
    </form>

    <a href="#" class="forgot-link">¿Olvidaste tu contraseña?</a>
</div>

</body>
</html>