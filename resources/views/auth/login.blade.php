<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login — AulaManager Lite</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --color-black: #111111;
            --color-light: #f5f5f5;
            --font-main:   'Montserrat', sans-serif;
            --fs-xs:  0.65rem;
            --fs-sm:  0.75rem;
        }

        body {
            font-family: var(--font-main);
            background-color: var(--color-light);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .login-wrap {
            width: 100%;
            max-width: 380px;
        }

        .login-card {
            background: #fff;
            border: 2px solid var(--color-black);
            padding: 2rem 2rem 1.5rem;
        }

        .login-logo {
            display: block;
            max-height: 48px;
            margin: 0 auto 1.25rem;
        }

        .login-title {
            font-size: var(--fs-sm);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            text-align: center;
            border-bottom: 1.5px solid var(--color-black);
            padding-bottom: 0.75rem;
            margin-bottom: 1.25rem;
        }

        .form-label {
            font-size: var(--fs-xs);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-control {
            border-radius: 0;
            border: 1.5px solid var(--color-black);
            font-family: var(--font-main);
            font-size: var(--fs-sm);
        }

        .form-control:focus {
            border-color: var(--color-black);
            box-shadow: 0 0 0 2px rgba(17,17,17,.12);
        }

        .btn-login {
            width: 100%;
            border-radius: 0;
            border: 1.5px solid var(--color-black);
            background: var(--color-black);
            color: #fff;
            font-family: var(--font-main);
            font-size: var(--fs-xs);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 0.6rem;
            transition: background .15s, color .15s;
        }

        .btn-login:hover {
            background: #fff;
            color: var(--color-black);
        }

        .alert {
            border-radius: 0;
            font-size: var(--fs-xs);
            font-weight: 600;
        }

        .login-footer {
            font-size: var(--fs-xs);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            text-align: center;
            color: #888;
            margin-top: 1rem;
        }
    </style>
</head>
<body>

<div class="login-wrap">
    <div class="login-card">
        <img src="{{ asset('images/logo.png') }}" alt="AulaManager Lite" class="login-logo">
        <p class="login-title">Iniciar Sesión</p>

        <form action="{{ route('login.post') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control"
                       placeholder="usuario@centro.com"
                       value="{{ old('email') }}" required autofocus>
            </div>

            <div class="mb-3">
                <label class="form-label">Contraseña</label>
                <input type="password" name="password" class="form-control"
                       placeholder="••••••••" required>
            </div>

            @if($errors->any())
                <div class="alert alert-danger border-danger py-2 mb-3">
                    {{ $errors->first() }}
                </div>
            @endif

            <button type="submit" class="btn-login mt-1">Entrar</button>
        </form>
    </div>

    <p class="login-footer">© 2026 AulaManager Lite</p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>