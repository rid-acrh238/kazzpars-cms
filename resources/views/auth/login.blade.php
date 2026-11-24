<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login Admin — Kazzpars Portal</title>

    <meta name="robots" content="noindex, nofollow">

    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
    >
</head>

<body class="login-page">

    <form
        class="login-dialog"
        method="POST"
        action="{{ route('login') }}"
    >
        @csrf

        <div class="login-titlebar">
            <span>
                <i class="fa-solid fa-lock" aria-hidden="true"></i>
                ADMIN LOGIN.EXE
            </span>
        </div>

        <div class="login-body">
            @if ($errors->any())
                <div class="login-error" role="alert">
                    <i class="fa-solid fa-triangle-exclamation" aria-hidden="true"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <label class="login-field">
                EMAIL
                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="username"
                >
            </label>

            <label class="login-field">
                PASSWORD
                <input
                    type="password"
                    name="password"
                    required
                    autocomplete="current-password"
                >
            </label>

            <label class="login-remember">
                <input type="checkbox" name="remember" value="1">
                Ingat saya di komputer ini
            </label>
        </div>

        <div class="login-actions">
            <a href="{{ route('home') }}" class="login-back">
                &laquo; Kembali ke situs
            </a>
            <button type="submit">
                <i class="fa-solid fa-right-to-bracket" aria-hidden="true"></i>
                LOGIN
            </button>
        </div>
    </form>

</body>
</html>
