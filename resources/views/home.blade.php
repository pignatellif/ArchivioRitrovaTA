@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
@endpush

@section('content')

<div class="hero-section">
    <div class="hero-overlay">
        <h1 class="hero-title">Archivio Ritrovata</h1>
        <hr class="hero-divider">
        <p class="hero-subtitle">Nel silenzio dei filmini, la voce di chi eravamo.</p>
        <a href="#welcome-section" class="hero-button">Scopri l'archivio</a>
    </div>
</div>

<section class="welcome-section" id="welcome-section">
    <div class="welcome-container">
        <img src="{{ asset('img/logo.png') }}" alt="Archivio Ritrovata Logo" class="welcome-logo fade-in delay-1">
        <h2 class="welcome-heading fade-in delay-2">
            Archivio Regionale dei Filmini di Famiglia
        </h2>
        <p class="welcome-text fade-in delay-3">
            Archivio RitrovaTA raccoglie e valorizza i filmini di famiglia della Puglia, custodendo la memoria privata come patrimonio collettivo.
        </p>
    </div>
</section>

<div class="section-divider"></div>

<section class="progetti">
    <h2 class="titolo-sezione">I nostri progetti</h2>
    <div class="griglia-progetti">
        <div class="progetto">
            <img src="{{ asset('img/progetti/cineteca.png') }}" alt="Cineteca dello Stretto – Ortigia" class="img-progetto">
            <h3 class="titolo-progetto">Cineteca dello Stretto – Ortigia</h3>
            <p class="descrizione-progetto">
                Dal 2023 archiviamo ricordi, dal 2024 siamo un ETS.<br>
                Archivio RitrovaTA partecipa alla non-conferenza con i suoi filmini di famiglia: piccoli frammenti che raccontano la Puglia e Taranto attraverso lo sguardo intimo delle persone.
            </p>
        </div>
        <div class="progetto">
            <img src="{{ asset('img/progetti/recordis.png') }}" alt="RECordis – Palazzo Ulmo" class="img-progetto">
            <h3 class="titolo-progetto">RECordis – Palazzo Ulmo</h3>
            <p class="descrizione-progetto">
                Archivio RitrovaTA partecipa alla residenza #RECordis con un incontro dedicato alla memoria filmica privata.<br>
                Giovanni Talamo presenta il talk <em>“Filmini di famiglia: significato, storia e archivi”</em>, seguito dalla proiezione di filmati storici dell’Archivio.
            </p>
        </div>
        <div class="progetto">
            <img src="{{ asset('img/progetti/vicoli.png') }}" alt="Vicoli Corti – Massafra" class="img-progetto">
            <h3 class="titolo-progetto">Vicoli Corti – Massafra</h3>
            <p class="descrizione-progetto">
                Parte la raccolta dei filmati privati girati a Massafra in Super 8 o 8mm: viaggi, feste, momenti di vita.<br>
                Archivio RitrovaTA li digitalizza gratuitamente e li valorizza all’interno di eventi pubblici.<br><br>
                Contribuisci anche tu alla costruzione di una memoria collettiva.<br>
                Ci vediamo a <strong>Vicoli Corti</strong>!
            </p>
        </div>
    </div>
    <div class="contenitore-bottone">
        <a href="#" class="bottone-trasparente">Sfoglia le nostre iniziative</a>
    </div>
</section>

<div class="section-divider"></div>

<section class="info-section">
    <div class="info-container">
        <div class="info-text-wrapper">
            <div>
                <h2 class="info-title">Un viaggio nella memoria</h2>
                <p class="info-text">
                    Archivio RitrovaTA raccoglie e valorizza i filmini di famiglia della Puglia, custodendo la memoria privata come patrimonio collettivo. Un viaggio nella memoria, per riscoprire le storie che ci uniscono.
                </p>
            </div>
            <a href="#" class="info-button">Scopri Chi Siamo</a>
        </div>
    </div>
</section>

<div class="section-divider"></div>

<section class="archive-section">
    <div class="archive-half text">
        <h4>Archivio</h4>
        <h2>Ogni filmato è una storia che torna a vivere</h2>
        <p>
            Nel nostro Archivio trovi ricordi in movimento: frammenti di vita pugliese, custoditi e restituiti alla comunità. Una finestra sul passato, da esplorare e riconoscere.
        </p>
        <a href="#" class="archive-button">Vai all'Archivio</a>
    </div>
    <div class="archive-half image">
        <img src="{{ asset('img/home/image1.png') }}" alt="Archivio" class="archive-image">
    </div>
</section>

<div class="section-divider"></div>

<section class="archive-section">
    <div class="archive-half image">
        <img src="{{ asset('img/home/image2.png') }}" alt="Archivio" class="archive-image">
    </div>
    <div class="archive-half text">
        <h4>Serie</h4>
        <h2>Ogni filmato è una storia che torna a vivere</h2>
        <p>
            Nel nostro Archivio trovi ricordi in movimento: frammenti di vita pugliese, custoditi e restituiti alla comunità. Una finestra sul passato, da esplorare e riconoscere.
        </p>
        <a href="#" class="archive-button">Esplora le Serie</a>
    </div>
</section>

<div class="section-divider"></div>

<section class="archive-section">
    <div class="archive-half text">
        <h4>Fuori dal Tacco</h4>
        <h2>Non solo Puglia.</h2>
        <p>
            In questa sezione trovano spazio i filmati girati nel resto d’Italia e del mondo. Perché la memoria segue le persone, ovunque vadano.
        </p>
        <a href="#" class="archive-button">Guarda i filmati</a>
    </div>
    <div class="archive-half image">
        <img src="{{ asset('img/home/image3.png') }}" alt="Archivio" class="archive-image">
    </div>
</section>

<div class="section-divider"></div>

<section class="archive-section">
    <div class="archive-half image">
        <img src="{{ asset('img/home/image4.png') }}" alt="Archivio" class="archive-image">
    </div>
    <div class="archive-half text">
        <h4>Fuori dal Frame</h4>
        <h2>Dietro ogni immagine, una voce.</h2>
        <p>
            “Fuori dal Frame” dà spazio ai racconti di chi ha vissuto quei momenti: mini-interviste che svelano le storie celate dietro i filmini di famiglia.
        </p>
        <a href="#" class="archive-button">Scopri le Storie</a>
    </div>
</section>

<div class="section-divider"></div>

<section class="contact-section">
    <div class="container">
        <div class="contact-header">
            <h2>Contattaci</h2>
            <h5>Hai un filmato da condividere? Un ricordo da valorizzare? Scrivici!</h5>
        </div>
        <div class="contact-info">
            <p class="contact-detail">📍 Via Cataldo Nitti, 155, Taranto – 40123, Italia</p>
            <p class="contact-detail">📞 +39 3928783002</p>
            <p class="contact-detail">✉️ <a href="mailto:inforitrovata@gmail.com">inforitrovata@gmail.com</a></p>
            <div class="contact-social">
                <a href="#"><i class="fa-brands fa-facebook"></i></a>
                <a href="#"><i class="fa-brands fa-instagram"></i></a>
                <a href="#"><i class="fa-brands fa-linkedin"></i></a>
            </div>
            <div class="contact-button">
                <a href="mailto:inforitrovata@gmail.com">Scrivici</a>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
    <script src="{{ asset('js/home.js') }}"></script>
@endpush