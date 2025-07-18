@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
@endpush

@section('content')

<div class="hero-section">
    <video autoplay loop muted playsinline class="hero-bg-video">
        <source src="/img/home/home-gif.mp4" type="video/mp4" />
        Il tuo browser non supporta il video HTML5.
    </video>
    <div class="hero-overlay">
        <h1 class="hero-title">Archivio Ritrovata</h1>
        <hr class="hero-divider">
        <p class="hero-subtitle">
            <span id="typewriter" class="typewriter"></span>
        </p>
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

<section class="event-section recognition-section">
    <h2 class="titolo-sezione">I nostri eventi</h2>
    <div class="griglia-progetti" data-count="{{ $recentEvents->count() }}">
        @forelse($recentEvents as $event)
            <div class="progetto">
                @if($event->cover_image)
                    <img src="{{ asset($event->cover_image) }}" alt="Copertina di {{ $event->titolo }}" class="img-progetto">
                @else
                    <img src="{{ asset('images/default-cover.jpg') }}" alt="Copertina di default" class="img-progetto">
                @endif
                <h3 class="titolo-progetto">
                    <a href="{{ route('eventi.show', $event->id) }}" class="titolo-progetto-link">
                        {{ $event->titolo }}
                    </a>
                </h3>
                <p class="descrizione-progetto">{{ Str::limit($event->descrizione, 150, '...') }}</p>
                    <p class="descrizione-progetto">
                        <small class="text-muted">
                            Dal {{ \Carbon\Carbon::parse($event->start_date)->format('d/m/Y') }}
                            @if($event->end_date)
                                al {{ \Carbon\Carbon::parse($event->end_date)->format('d/m/Y') }}
                            @endif
                        </small>
                    </p>
                <p class="descrizione-progetto"><strong>Luogo:</strong> {{ $event->luogo }}</p>
            </div>
        @empty
            <div class="empty-state">
                <div class="empty-content">
                    <div class="empty-icon">
                        <i class="fa-solid fa-calendar-days"></i>
                    </div>
                    <h3>Nessun evento disponibile</h3>
                    <p>Stiamo lavorando per pubblicare i primi eventi.<br>Resta sintonizzato!</p>
                </div>
            </div>
        @endforelse
        
        @if($recentEvents->count() > 0)
            <div class="contenitore-bottone">
                <a href="{{ route('eventi') }}" class="bottone-trasparente">Sfoglia tutti gli eventi</a>
            </div>
        @endif
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
            <a href="{{ route('chi_siamo') }}" class="info-button">Scopri Chi Siamo</a>
        </div>
    </div>
</section>

<div class="section-divider"></div>

<section class="archive-section reverse-order fixed-order">
    <div class="archive-half text">
        <h4>Archivio</h4>
        <h2>Ogni filmato è una storia che torna a vivere</h2>
        <p>
            Nel nostro Archivio trovi ricordi in movimento: frammenti di vita pugliese, custoditi e restituiti alla comunità. Una finestra sul passato, da esplorare e riconoscere.
        </p>
        <a href="{{ route('archivio') }}" class="archive-button">Vai all'Archivio</a>
    </div>
    <div class="archive-half image">
        <img src="{{ asset('img/home/image1.png') }}" alt="Archivio" class="archive-image">
    </div>
</section>

<div class="section-divider"></div>

<section class="archive-section fixed-order">
    <div class="archive-half image">
        <img src="{{ asset('img/home/image2.png') }}" alt="Serie" class="archive-image">
    </div>
    <div class="archive-half text">
        <h4>Serie</h4>
        <h2>Storie che si intrecciano in episodi</h2>
        <p>
            Esplora le nostre serie: racconti tematici che uniscono i filmini di famiglia in un viaggio unico. Ogni episodio è un frammento di memoria che prende vita.
        </p>
        <a href="{{ route('serie') }}" class="archive-button">Scopri le Serie</a>
    </div>
</section>

<div class="section-divider"></div>

<section class="archive-section reverse-order fixed-order">
    <div class="archive-half text">
        <h4>Fuori dal Tacco</h4>
        <h2>Non solo Puglia.</h2>
        <p>
            In questa sezione trovano spazio i filmati girati nel resto d’Italia e del mondo. Perché la memoria segue le persone, ovunque vadano.
        </p>
        <a href="{{ route('fuori_dal_tacco') }}" class="archive-button">Guarda i filmati</a>
    </div>
    <div class="archive-half image">
        <img src="{{ asset('img/home/fuori-dal-tacco.png') }}" alt="Archivio" class="archive-image">
    </div>
</section>

<div class="section-divider"></div>

<section class="archive-section fixed-order">
    <div class="archive-half image">
        <img src="{{ asset('img/home/fuori-dal-frame.png') }}" alt="Archivio" class="archive-image">
    </div>
    <div class="archive-half text">
        <h4>Fuori dal Frame</h4>
        <h2>Dietro ogni immagine, una voce.</h2>
        <p>
            “Fuori dal Frame” dà spazio ai racconti di chi ha vissuto quei momenti: mini-interviste che svelano le storie celate dietro i filmini di famiglia.
        </p>
        <a href="{{ route('fuori_dal_frame') }}" class="archive-button">Scopri le Storie</a>
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
            <p class="contact-detail"><i class="fa-solid fa-map-pin"></i> Via Cataldo Nitti, 155, Taranto – 40123, Italia</p>
            <p class="contact-detail"><i class="fa-solid fa-phone"></i> +39 3928783002</p>
            <p class="contact-detail"><i class="fa-solid fa-paper-plane"></i> <a href="mailto:inforitrovata@gmail.com">inforitrovata@gmail.com</a></p>
            <div class="contact-social">
                <a href="https://www.facebook.com/people/Archivio-Ritrovata/100091771725483/" aria-label="Facebook"><i class="fa-brands fa-facebook"></i></a>
                <a href="https://www.instagram.com/ArchivioRitrovaTA" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                <a href="https://www.linkedin.com/in/archivio-ritrovata-385bb12b8/" aria-label="LinkedIn"><i class="fa-brands fa-linkedin"></i></a>
            </div>
            <a href="mailto:inforitrovata@gmail.com" class="contact-button" aria-label="Scrivici via email">
                <i class="fa-solid fa-envelope"></i> Scrivici
            </a>
        </div>
    </div>
</section>

@endsection

@push('scripts')
    <script src="{{ asset('js/home.js') }}"></script>
@endpush