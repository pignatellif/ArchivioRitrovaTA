@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/chi-siamo.css') }}">
@endpush

@section('content')

<section class="hero-section">
    <div class="hero-container">
        <h2>Archivio Regionale dei Filmini di Famiglia</h2>
        <p>Archivio RitrovaTA raccoglie e valorizza i filmini di famiglia della Puglia, custodendo la memoria privata come patrimonio collettivo.</p>
    </div>
</section>

<div class="section-divider"></div>

<section class="archive-section">
    <div class="archive-half text">
        <h2>Un archivio che nasce dal cuore</h2>
        <p>
            Archivio RitrovaTA è un progetto nato nel 2023 dal desiderio di salvare, custodire e condividere i filmini di famiglia e le memorie audiovisive del territorio pugliese.
            Nel 2024, prende forma come associazione culturale ETS, con sede a Taranto, ma con lo sguardo aperto su tutta la regione e oltre.
        </p>
    </div>
    <div class="archive-half image">
        <img src="{{ asset('img/chi-siamo/chi-siamo.png') }}" alt="Archivio RitrovaTA" class="archive-image">
    </div>
</section>

<div class="section-divider"></div>

<section class="cosa-facciamo-full">
    <h2 class="cf-title">Cosa Facciamo</h2>
    <p class="cf-desc">
        Ci occupiamo del recupero, digitalizzazione e valorizzazione dei filmati amatoriali, con un’attenzione particolare ai formati Super 8 e 8mm.
    </p>

    <div class="cf-wrapper">
        <div class="cf-row" style="background-image: url('{{ asset('img/chi-siamo/recupero.png') }}');">
            <div class="cf-overlay">
                <div class="cf-content">
                    <h3><i class="fas fa-film"></i> Incontri di raccolta filmati</h3>
                    <p>Raccogliamo pellicole dimenticate per restituirle alla memoria collettiva.</p>
                </div>
            </div>
        </div>

        <div class="cf-row" style="background-image: url('{{ asset('img/chi-siamo/digitalizzazione.png') }}');">
            <div class="cf-overlay">
                <div class="cf-content">
                    <h3><i class="fas fa-video"></i> Eventi culturali, talk e proiezioni</h3>
                    <p>Organizziamo eventi pubblici per condividere le storie ritrovate.</p>
                </div>
            </div>
        </div>

        <div class="cf-row" style="background-image: url('{{ asset('img/chi-siamo/valorizzazione.png') }}');">
            <div class="cf-overlay">
                <div class="cf-content">
                    <h3><i class="fas fa-chalkboard-teacher"></i> Percorsi educativi e laboratori</h3>
                    <p>Promuoviamo attività didattiche per riscoprire il cinema di famiglia.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="section-divider"></div>

<section class="why-section">
    <div class="why-container">
        <div class="why-text-wrapper">
            <div class="why-text-block">
                <h2 class="why-title">Perché lo facciamo?</h2>
                <p class="why-text">
                    Crediamo che <span class="highlight">la memoria privata sia un bene comune</span>.
                    I filmini di famiglia raccontano ciò che spesso sfugge agli archivi ufficiali: l’intimità dei gesti quotidiani, i legami, le trasformazioni sociali e culturali viste dal basso.
                </p>
                <p class="why-text">
                    Ritrovarli significa <span class="highlight">riattivare connessioni</span>, <span class="highlight">costruire narrazioni nuove</span>, <span class="highlight">creare ponti tra generazioni</span>.
                </p>
            </div>
        </div>
    </div>
</section>

<div class="section-divider"></div>

<section class="archive-section">
    <div class="archive-half image">
        <img src="{{ asset('img/chi-siamo/approccio.png') }}" alt="Approccio artigianale" class="archive-image">
    </div>
    <div class="archive-half text">
        <h2>Il nostro approccio</h2>
        <p>
            Lavoriamo con cura artigianale, rispetto e ascolto.
            Ogni pellicola è analizzata, catalogata e restituita in forma digitale a chi ce la affida. Alcuni filmati vengono poi selezionati per attività pubbliche, sempre nel rispetto della volontà dei donatori.
        </p>
    </div>
</section>

<div class="section-divider"></div>

<section class="spacer-section">
    <div class="container">
        <!-- Spazio per separare il contenuto dal footer -->
    </div>
</section>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const cfRows = document.querySelectorAll('.cf-row');

        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    // Ritarda di 1 secondo (1000 ms) l'aggiunta della classe
                    setTimeout(() => {
                        entry.target.classList.add('in-view');
                    }, 1000);

                    observer.unobserve(entry.target); // rimuovi se vuoi l'effetto solo la prima volta
                }
            });
        }, {
            threshold: 0.3
        });

        cfRows.forEach(row => observer.observe(row));
    });
</script>
@endpush
