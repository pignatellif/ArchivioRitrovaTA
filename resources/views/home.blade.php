@extends('layouts.app')  {{-- Estende il layout principale --}}

@push('styles')  
    {{-- Importa Bootstrap, il CSS personalizzato e lo stile per la mappa --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">

    <!-- Stili per Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
@endpush

@section('content')

    <!-- HERO SECTION -->
    <div class="hero-section position-relative">
        {{-- Sfondo slider dinamico --}}
        <div class="background-slider position-absolute w-100 h-100"></div>
        <div class="background-slider-next position-absolute w-100 h-100"></div>
        {{-- Overlay scuro per migliorare la leggibilità del testo --}}
        <div class="overlay position-absolute w-100 h-100 bg-dark opacity-50"></div>
        
        {{-- Contenuto principale della Hero Section --}}
        <div class="hero-content text-center text-white position-absolute top-50 start-50 translate-middle">
            <h1 class="fade-in">Archivio RitrovaTA</h1>
            <hr class="slide-in">
            <p class="hero-text fade-in">"Custodiamo la storia della Puglia, un fotogramma alla volta"</p>
            <a href="#benvenuto" class="cta-button">Scopri di più</a>
        </div>
    </div>

    <!-- SEZIONE DI BENVENUTO -->
    <section class="py-5">
        <div class="container" id="benvenuto">
            <div class="row align-items-center">
                {{-- Colonna con l'immagine --}}
                <div class="col-md-6">
                    <img src="{{ asset('img/chi-siamo.png') }}" alt="Benvenuto" class="img-fluid welcome-image">
                </div>
                {{-- Colonna con il testo di benvenuto --}}
                <div class="col-md-6">
                    <h2 class="welcome-title">Archivio regionale dei filmini di famiglia.</h2>
                    <p class="welcome-text">"Custodiamo la storia della Puglia, un fotogramma alla volta"</p>
                </div>
            </div>
        </div>
    </section>

    <!-- SEZIONE INFORMATIVA CON LA MAPPA -->
    <section class="info-map-section">
        <div class="text-wrapper">
            {{-- Sezione "Chi Siamo" --}}
            <div class="text-content">
                <h2>Chi Siamo</h2>
                <p>Archivio RitrovaTA nasce come progetto di recupero e valorizzazione della memoria audiovisiva del territorio pugliese.</p>
            </div>

            {{-- Sezione "I Principali Obiettivi" --}}
            <div class="text-content">
                <h2>I Principali Obiettivi</h2>
                <ul>
                    <li><strong>Recuperare</strong> e <strong>condividere</strong> il patrimonio audiovisivo della Puglia.</li>
                    <li><strong>Valorizzare</strong> la memoria delle persone attraverso la digitalizzazione.</li>
                    <li>Rendere l'archivio <strong>accessibile</strong> a tutti con progetti culturali.</li>
                </ul>
            </div>
        </div>

        {{-- Sezione con la mappa interattiva --}}
        <div class="map-wrapper">
            <h2>I Comuni che hanno Contribuito</h2>
            <div id="map"></div>
        </div>
    </section>

    <!-- SEZIONI DEL SITO -->
    <section class="category-section py-5">
        <div class="container text-center">
            <h2 class="section-title mb-5">Le Nostre Sezioni</h2>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
                <div class="col">
                    <div class="category">
                        <div class="category-content">
                            <h3>Archivio</h3>
                            <p>Un viaggio nella nostra collezione di filmati, documenti e materiali restaurati, per riscoprire la memoria audiovisiva del passato.</p>
                            <a href="{{ route('archivio') }}" class="btn section-btn">Esplora Archivio</a>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="category">
                        <div class="category-content">
                            <h3>Serie</h3>
                            <p>Una selezione di produzioni originali che raccontano storie inedite, esplorando tematiche culturali e sociali con uno sguardo unico.</p>
                            <a href="{{ route('serie') }}" class="btn section-btn">Scopri le Serie</a>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="category">
                        <div class="category-content">
                            <h3>Fuori dal Frame</h3>
                            <p>Approfondimenti, interviste e racconti che vanno oltre l’immagine per rivelare ciò che accade dietro le quinte e nel processo creativo.</p>
                            <a href="{{ route('fuori_dal_frame') }}" class="btn section-btn">Approfondisci</a>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="category">
                        <div class="category-content">
                            <h3>Fuori dal Tacco</h3>
                            <p>Un’esplorazione visiva e narrativa delle storie e delle identità dei territori, attraverso testimonianze e immagini inedite.</p>
                            <a href="{{ route('fuori_dal_tacco') }}" class="btn section-btn">Vai oltre il Tacco</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SEZIONE CONTATTACI -->
    <section id="contattaci" class="call-to-action py-5 bg-light">
        <div class="container">
            <div class="row align-items-center">
                {{-- Testo e bottone di contatto --}}
                <div class="col-md-6">
                    <h2>Hai vecchi filmati?</h2>
                    <p>VHS, Super 8, 8mm? Li digitalizziamo e li conserviamo per il futuro!</p>
                    <a href="mailto:inforitrovata@gmail.com" class="btn section-btn">Contattaci</a>
                </div>
                {{-- Immagine illustrativa --}}
                <div class="col-md-6" id="contact-image">
                    <img src="{{ asset('img/home/cosa-ci-serve.jpg') }}" alt="VHS e pellicole" class="contact-image img-fluid">
                </div>
            </div>
        </div>
    </section>

@endsection

@push('scripts')
    {{-- Inclusione script per funzionalità interattive --}}
    <script src="{{ asset('js/home.js') }}"></script>

    <!-- Script per Leaflet -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

    <script type="application/json" id="locations-data">
        {!! json_encode($locations) !!}
    </script>

    <script src="{{ asset('js/map.js') }}"></script>
@endpush
