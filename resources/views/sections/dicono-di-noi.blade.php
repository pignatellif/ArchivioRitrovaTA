@extends('layouts.app')

@section('content')
<section class="hero-section">
    <div class="hero-overlay">
        <div class="hero-text">
            <h2>Archivio Ritrovata</h2>
            <hr>
            <h3>Le nostre storie</h3>
            <p>Esplora i racconti e le testimonianze che ci rendono unici.</p>
        </div>
    </div>
</section>

<!-- Divisore sezione -->
<div class="section-divider"></div>

<!-- Sezione riconoscimenti -->
<div class="recognition-section">
    <div class="container">
        @if($riconoscimenti->count() > 0)
            <!-- Grid dei Riconoscimenti -->
            <div class="row g-4">
                @foreach($riconoscimenti as $riconoscimento)
                    <div class="col-lg-4 col-md-6">
                        <div class="card recognition-card">
                            <div class="card-body">
                                <!-- Header del Card -->
                                <div class="card-header-custom">
                                    <span class="source-badge">{{ $riconoscimento->fonte }}</span>
                                    <small class="publication-date">
                                        @php
                                            $dataPublicazione = $riconoscimento->data_pubblicazione;
                                            if ($dataPublicazione && is_string($dataPublicazione)) {
                                                $dataPublicazione = \Carbon\Carbon::parse($dataPublicazione);
                                            }
                                        @endphp
                                        {{ $dataPublicazione ? $dataPublicazione->format('d/m/Y') : 'Data non disponibile' }}
                                    </small>
                                </div>

                                <!-- Titolo -->
                                <h5 class="card-title">
                                    {{ $riconoscimento->titolo }}
                                </h5>

                                <!-- Estratto -->
                                @if($riconoscimento->estratto)
                                    <div class="card-text">
                                        <p>{{ Str::limit($riconoscimento->estratto, 150) }}</p>
                                    </div>
                                @endif

                                <!-- Call to Action -->
                                <div class="card-action">
                                    @if($riconoscimento->url)
                                        <a href="{{ $riconoscimento->url }}" 
                                           target="_blank" 
                                           rel="noopener noreferrer"
                                           class="btn btn-primary">
                                            <i class="fas fa-external-link-alt"></i>
                                            Leggi Articolo Completo
                                        </a>
                                    @endif
                                </div>
                            </div>

                            <!-- Footer del Card -->
                            <div class="card-footer-custom">
                                <small class="time-ago">
                                    <i class="fas fa-calendar-alt"></i>
                                    @php
                                        $dataPublicazione = $riconoscimento->data_pubblicazione;
                                        if ($dataPublicazione && is_string($dataPublicazione)) {
                                            $dataPublicazione = \Carbon\Carbon::parse($dataPublicazione);
                                        }
                                    @endphp
                                    Pubblicato {{ $dataPublicazione ? $dataPublicazione->diffForHumans() : 'di recente' }}
                                </small>
                                @if($riconoscimento->url)
                                    <small class="link-indicator">
                                        <i class="fas fa-link"></i>
                                    </small>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Paginazione se necessaria -->
            @if($riconoscimenti instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="view-list">
                    <div class="row mt-5">
                        <div class="col-12 d-flex justify-content-center">
                            {{ $riconoscimenti->links() }}
                        </div>
                    </div>
                </div>
            @endif

        @else
            <!-- Stato Vuoto -->
            <div class="empty-state">
                <div class="empty-content">
                    <div class="empty-icon">
                        <i class="fas fa-newspaper"></i>
                    </div>
                    <h3>Nessun riconoscimento ancora disponibile</h3>
                    <p>
                        Torna presto per scoprire cosa dicono di noi i media e gli esperti del settore.
                    </p>
                </div>
            </div>
        @endif

    </div>
</div>

<div class="section-divider"></div>

<section class="spacer-section">
    <div class="container">
        <!-- Spazio per separare il contenuto dal footer -->
    </div>
</section>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/riconoscimenti.css') }}">
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestione clic sui link esterni
    const externalLinks = document.querySelectorAll('a[target="_blank"]');
    
    externalLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            // Aggiungi analytics o tracking se necessario
            console.log('Link esterno cliccato:', this.href);
        });
    });
    
    // Animazione cards al caricamento
    const cards = document.querySelectorAll('.recognition-card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            card.style.transition = 'all 0.6s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
});
</script>
@endpush