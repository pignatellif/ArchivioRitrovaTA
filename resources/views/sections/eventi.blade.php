@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/events.css') }}">
@endpush

@section('content')
<section class="hero-section">
    <div class="hero-overlay">
        <div class="hero-text">
            <h2>Progetti</h2>
            <hr>
            <h3>Ogni pellicola è un ricordo che torna a parlare.</h3>
            <p>Scopri i filmati che compongono la nostra memoria condivisa.</p>
        </div>
    </div>
</section>

<section class="search-section">
    <div class="search-bar">
        <h2 class="search-title">Cerca Eventi</h2>
        <form action="{{ route('eventi') }}" method="GET" id="search-form">
            <div class="input-group position-relative">
                <!-- Campo di input -->
                <input type="text" class="form-control" name="query" id="search-input" placeholder="Cerca per nome, data o luogo" value="{{ request('query') }}">
                <!-- Icona di reset -->
                <i class="fa-solid fa-xmark search-reset-icon" id="reset-icon" style="display: none;"></i>
                <!-- Bottone di ricerca -->
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                </div>
            </div>
        </form>
    </div>
</section>

<div class="section-divider"></div>

<section class="event-section">
    <div class="row mt-4 view-list event-row">
        @forelse($events as $event)
            <div class="col-md-4">
                <div class="card mb-4">
                    @if($event->cover_image)
                        <img src="{{ asset($event->cover_image) }}" class="card-img-top" alt="Copertina di {{ $event->titolo }}">
                    @else
                        <img src="{{ asset('images/default-cover.jpg') }}" class="card-img-top" alt="Copertina di default">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $event->titolo }}</h5>
                        <p class="card-text">{{ $event->descrizione }}</p>
                        <p class="card-text">
                            <small class="text-muted">
                                Dal {{ \Carbon\Carbon::parse($event->start_date)->format('d/m/Y') }}
                                @if($event->end_date)
                                    al {{ \Carbon\Carbon::parse($event->end_date)->format('d/m/Y') }}
                                @endif
                            </small>
                        </p>
                        <p class="card-text"><strong>Luogo:</strong> {{ $event->luogo }}</p>
                    </div>
                </div>
            </div>
        @empty
            <!-- Stato Vuoto -->
            <div class="empty-state">
                <div class="empty-content">
                    <div class="empty-icon">
                        <i class="fa-solid fa-calendar-days"></i>
                    </div>
                    <h3>Nessun evento ancora disponibile</h3>
                    <p>
                        Torna presto per scoprire cosa stiamo organizzando.
                    </p>
                </div>
            </div>
        @endforelse
    </div>
</section>

<div class="row justify-content-center view-list">
    {{ $events->appends(request()->input())->links() }}
</div>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const searchInput = document.getElementById("search-input");
            const resetIcon = document.getElementById("reset-icon");
            const searchForm = document.getElementById("search-form");

            // Mostra l'icona di reset quando l'input contiene testo
            searchInput.addEventListener("input", function () {
                if (searchInput.value.trim() !== "") {
                    resetIcon.style.display = "block";
                } else {
                    resetIcon.style.display = "none";
                }
            });

            // Resetta il campo di ricerca e invia il modulo
            resetIcon.addEventListener("click", function () {
                searchInput.value = "";
                resetIcon.style.display = "none";
                searchForm.submit(); // Reimposta i risultati della ricerca
            });
        });
    </script>
@endpush