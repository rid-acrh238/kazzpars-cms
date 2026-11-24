<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Kazzpars Portal — Personal Blog')</title>

    <meta name="description" content="Kazzpars Portal — blog personal retro milik Kazzpars, mahasiswa Informatika: tulisan, project, skills, guestbook, dan musik.">
    <meta name="theme-color" content="#000080">

    <!-- Open Graph. Setelah deploy, ganti og:image dengan URL absolut (https://...). -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="Kazzpars Portal — Personal Blog">
    <meta property="og:description" content="Blog personal retro ala GeoCities: tulisan, project, skills, guestbook, dan musik.">
    <meta property="og:image" content="{{ asset('img/photo.jpeg') }}">

    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
    >

    <script src="{{ asset('music.js') }}" defer></script>
    <script src="{{ asset('dateTime.js') }}" defer></script>
    <script src="{{ asset('retro.js') }}" defer></script>
</head>

<body>

    <!-- BOOT SCREEN -->
    <div id="boot-screen" role="status">
        <img src="{{ asset('img/loading.gif') }}" alt="">
        <p>LOADING KAZZPARS PORTAL...</p>
    </div>

    <div class="retro-layout">

        @include('partials.sidebar-left')

        <!-- KOLOM TENGAH: BLOG -->
        <div class="retro-main-column">
            <header class="site-header">
                <a href="{{ route('home') }}" class="logo">Kazzpars <span>Portal</span></a>

                <h1>Welcome to My Blog</h1>

                <nav class="navigation">
                    @php($onHome = request()->routeIs('home'))
                    <ul>
                        <li><a href="{{ $onHome ? '#home' : route('home') . '#home' }}">Home</a></li>
                        <li><a href="{{ $onHome ? '#about' : route('home') . '#about' }}">About</a></li>
                        <li><a href="{{ $onHome ? '#projects' : route('home') . '#projects' }}">Projects</a></li>
                        <li><a href="{{ $onHome ? '#skills' : route('home') . '#skills' }}">Skills</a></li>
                        <li><a href="{{ $onHome ? '#guestbook' : route('home') . '#guestbook' }}">Guestbook</a></li>
                        <li><a href="{{ $onHome ? '#contact' : route('home') . '#contact' }}">Contact</a></li>
                    </ul>
                </nav>
            </header>

            <main class="retro-main">
                @yield('content')
            </main>
        </div>

        @include('partials.widgets')

    </div>

    <footer>
        <p>
            &copy; {{ date('Y') }} KAZZPARS | All Rights Reserved |
            Designed by Kazzpars
        </p>
        <p>Best viewed with curiosity! 💾</p>
        <p class="footer-portal-link">
            <a href="{{ asset('nocturne.html') }}">ENTER NOCTURNE ✦</a>
        </p>
    </footer>

</body>
</html>
