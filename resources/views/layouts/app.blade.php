<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Archivio RitrovaTa')</title>

    <!-- Style -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&family=Roboto:wght@400;700&family=Special+Elite&display=swap" rel="stylesheet">
    <!-- Slick Carousel CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css"/>

    @stack('styles')
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-transparent">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ asset('img/logo.png') }}" alt="RitrovaTa">
            </a>

            <button class="navbar-toggler" id="custom-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars text-accent"></i>
            </button>

            <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('archivio') }}">Archivio</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('serie') }}">Serie</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('fuori_dal_tacco') }}">Fuori dal tacco</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('fuori_dal_frame') }}">Fuori dal frame</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('eventi') }}">Eventi</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('sostienici') }}">Sostienici</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('dicono_di_noi') }}">Dicono di noi</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container-fluid p-0">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer>
        <div class="footer-container">
            <p class="copyright">&copy; 2025 RitrovaTa. Tutti i diritti riservati.</p>
            <ul class="footer-links">
                <li><a href="{{ route('home') }}">Home</a></li>
                <li><a href="{{ route('archivio') }}">Archivio</a></li>
                <li><a href="{{ route('serie') }}">Serie</a></li>
                <li><a href="{{ route('fuori_dal_tacco') }}">Fuori dal tacco</a></li>
                <li><a href="{{ route('fuori_dal_frame') }}">Fuori dal frame</a></li>
                <li><a href="{{ route('sostienici') }}">Sostienici</a></li>
            </ul>
            <ul class="social-links">
                <li><a href="https://www.youtube.com" target="_blank"><i class="fab fa-youtube"></i></a></li>
                <li><a href="https://www.facebook.com" target="_blank"><i class="fab fa-facebook"></i></a></li>
                <li><a href="https://www.instagram.com" target="_blank"><i class="fab fa-instagram"></i></a></li>
            </ul>
        </div>
    </footer>

    <script src="{{ asset('js/app.js') }}"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery (necessario per Slick) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Slick Carousel JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>

    @stack('scripts')

</body>
</html>