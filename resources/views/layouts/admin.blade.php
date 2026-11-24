<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Admin') — Kazzpars CMS</title>

    <meta name="robots" content="noindex, nofollow">

    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <link rel="stylesheet" href="{{ asset('admin.css') }}">
    <script src="{{ asset('editor.js') }}" defer></script>
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
    >
</head>

<body class="admin-body">

    <div class="admin-shell">

        <!-- ADMIN HEADER -->
        <header class="admin-header">
            <a href="{{ route('home') }}" class="admin-logo">
                KAZZPARS <span>CMS</span>
            </a>

            <nav class="admin-nav">
                <a href="{{ route('admin.dashboard') }}">
                    <i class="fa-solid fa-gauge-high" aria-hidden="true"></i>
                    Dashboard
                </a>
                <a href="{{ route('admin.posts.index') }}">
                    <i class="fa-solid fa-newspaper" aria-hidden="true"></i>
                    Posts
                </a>
                <a href="{{ route('admin.categories.index') }}">
                    <i class="fa-solid fa-tags" aria-hidden="true"></i>
                    Kategori
                </a>
                <a href="{{ route('home') }}" target="_blank" rel="noopener">
                    <i class="fa-solid fa-up-right-from-square" aria-hidden="true"></i>
                    Lihat Situs
                </a>
            </nav>

            <form
                method="POST"
                action="{{ route('logout') }}"
                class="admin-logout"
            >
                @csrf
                <button type="submit">
                    <i class="fa-solid fa-right-from-bracket" aria-hidden="true"></i>
                    Logout ({{ Auth::user()->name }})
                </button>
            </form>
        </header>

        <!-- FLASH MESSAGES -->
        @if (session('status'))
            <div class="admin-flash admin-flash-ok" role="status">
                <i class="fa-solid fa-circle-check" aria-hidden="true"></i>
                {{ session('status') }}
            </div>
        @endif

        @if (session('error'))
            <div class="admin-flash admin-flash-error" role="alert">
                <i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i>
                {{ session('error') }}
            </div>
        @endif

        <!-- CONTENT -->
        <main class="admin-main">
            @yield('content')
        </main>

        <footer class="admin-footer">
            KAZZPARS CMS v1.0 — dibuat dengan Laravel {{ app()->version() }}
        </footer>

    </div>

</body>
</html>
