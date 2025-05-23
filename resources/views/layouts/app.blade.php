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
                    <li class="nav-item"><a class="nav-link" href="{{ route('eventi') }}">Progetti</a></li>
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
            <div class="footer-col footer-logo">
                <!-- Inserisci qui il tuo logo -->
                <img src="/img/logo-footer.png" alt="Archivio RitrovaTA Logo" class="logo-img">
            </div>
            <div class="footer-col footer-links">
                <ul>
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ route('archivio') }}">Archivio</a></li>
                    <li><a href="{{ route('serie') }}">Serie</a></li>
                    <li><a href="{{ route('fuori_dal_tacco') }}">Fuori dal tacco</a></li>
                    <li><a href="{{ route('fuori_dal_frame') }}">Fuori dal frame</a></li>
                    <li><a href="{{ route('eventi') }}">Progetti</a></li>
                    <li><a href="{{ route('dicono_di_noi') }}">Dicono di noi</a></li>
                </ul>
            </div>
            <div class="footer-col footer-info">
                <div class="footer-branding">
                    <p class="footer-title">Archivio RitrovaTA – Ente del Terzo Settore (ETS)</p>
                    <p class="footer-details">
                        C.F. 90283780733<br>
                        Iscritta al RUNTS - n. 139731
                    </p>
                </div>
                <div class="footer-contact">
                    <p>
                        <strong>Pec:</strong> <a href="mailto:archivioritrovata@pec.it">archivioritrovata@pec.it</a><br>
                        <strong>Email:</strong> <a href="mailto:inforitrovata@gmail.com">inforitrovata@gmail.com</a>
                    </p>
                </div>
                <ul class="social-links">
                    <li><a href="https://www.youtube.com" target="_blank" aria-label="YouTube"><i class="fab fa-youtube"></i></a></li>
                    <li><a href="https://www.facebook.com" target="_blank" aria-label="Facebook"><i class="fab fa-facebook"></i></a></li>
                    <li><a href="https://www.instagram.com" target="_blank" aria-label="Instagram"><i class="fab fa-instagram"></i></a></li>
                    <li><a href="https://www.linkedin.com" target="_blank" aria-label="Linkedin"><i class="fab fa-linkedin"></i></a></li>
                </ul>
                <p class="copyright">
                    &copy; 2025 Archivio RitrovaTA. Tutti i diritti riservati.
                </p>
            </div>
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