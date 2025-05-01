@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/archivio.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
@endpush

@section('content')
<div class="container-fluid">
    <!-- HERO SECTION -->
    <section class="hero-section">
        <div class="hero-content">
            <h1>Storie in viaggio, memorie oltre la Puglia.</h1>
            <hr class="hero-divider">
            <p>Una raccolta di filmini girati in altre regioni d’Italia e del mondo. Perché la memoria non ha confini, e ogni sguardo racconta un altrove.</p>
        </div>
    </section>

    <div class="d-flex align-items-center mb-2 top-buttons">
        <!-- Pulsante filtri -->
        <button class="btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#filterSidebar" aria-controls="filterSidebar">
            <i class="fa-sharp fa-solid fa-sliders"></i>
        </button>

        <!-- Pulsante griglia -->
        <button class="btn" id="gridViewBtn" title="Vista griglia">
            <i class="fas fa-th"> Griglia</i>
        </button>

        <!-- Pulsante mappa -->
        <button class="btn" id="mapViewBtn" title="Vista mappa">
            <i class="fas fa-map-marked-alt"> Mappa</i>
        </button>
    </div>


    <div class="row">
        <!-- Sidebar a Comparsa -->
        <div class="offcanvas offcanvas-start" tabindex="-1" id="filterSidebar" aria-labelledby="filterSidebarLabel">
            <div class="offcanvas-header">
                <h3 class="offcanvas-title" id="filterSidebarLabel">Filtri</h3>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <!-- Sezione filtri -->
                <form id="filterForm" class="filter-container">
                    <div class="filter-row">
                        <!-- Filtro Titolo -->
                        <div class="mb-3">
                            <div class="slider-container">
                                <span class="slider-label">Titolo</span>
                                <div class="input-wrapper">
                                    <input type="text" class="form-control filter" id="filterTitle" placeholder="Inserisci un titolo">
                                    <button type="button" class="btn-clear-input d-none" data-target="#filterTitle">
                                        <i class="fa-sharp fa-solid fa-xmark"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="filter-row">
                        <!-- Filtro Anno -->
                        <div class="mb-3">
                            <div class="slider-container">
                                <span class="slider-label">Anno</span>
                                <span id="yearValue1" class="slider-value">{{ $minYear }}</span>
                                <div class="slider-wrapper">
                                    <input type="range" min="{{ $minYear }}" max="{{ $maxYear }}" value="{{ $minYear }}" class="slider" id="yearSlider1">
                                    <input type="range" min="{{ $minYear }}" max="{{ $maxYear }}" value="{{ $maxYear }}" class="slider" id="yearSlider2">
                                </div>
                                <span id="yearValue2" class="slider-value">{{ $maxYear }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="filter-row">
                        <!-- Filtro Regista -->
                        <div class="mb-3">
                            <div class="slider-container">
                                <span class="slider-label">Regista</span>
                                <div class="input-wrapper">
                                    <select class="form-control filter" id="filterAuthor">
                                        <option value="">Tutti</option>
                                        @foreach($authors as $author)
                                            <option value="{{ $author }}">{{ $author }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="filter-row">
                        <!-- Filtro Durata -->
                        <div class="mb-3">
                            <div class="slider-container">
                                <span class="slider-label">Durata</span>
                                <span id="durationValue1" class="slider-value">{{ $minDuration }}</span>
                                <div class="slider-wrapper">
                                    <input type="range" min="{{ $minDuration }}" max="{{ $maxDuration }}" value="{{ $minDuration }}" class="slider" id="durationSlider1">
                                    <input type="range" min="{{ $minDuration }}" max="{{ $maxDuration }}" value="{{ $maxDuration }}" class="slider" id="durationSlider2">
                                </div>
                                <span id="durationValue2" class="slider-value">{{ $maxDuration }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="filter-row">
                        <!-- Filtro Formato -->
                        <div class="mb-3">
                            <div class="slider-container">
                                <span class="slider-label">Formato</span>
                                <div class="input-wrapper">
                                    <select class="form-control filter" id="filterFormat">
                                        <option value="">Tutti</option>
                                        @foreach($formats as $format)
                                            <option value="{{ $format }}">{{ $format }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="filter-row">
                        <!-- Filtro Famiglia -->
                        <div class="mb-3">
                            <div class="slider-container">
                                <span class="slider-label">Famiglia</span>
                                <div class="input-wrapper">
                                    <select class="form-control filter" id="filterFamily">
                                        <option value="">Tutti</option>
                                        @foreach($families as $family)
                                            <option value="{{ $family }}">{{ $family }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="filter-row">
                        <!-- Filtro Luogo -->
                        <div class="mb-3">
                            <div class="slider-container">
                                <span class="slider-label">Luogo</span>
                                <div class="input-wrapper">
                                    <select class="form-control filter" id="filterLocation">
                                        <option value="">Tutti</option>
                                        @foreach($locations as $location)
                                            <option value="{{ $location }}">{{ $location }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="filter-row" style="border-bottom: none;">
                        <button type="button" id="resetFilters" class="reset-button w-100">Resetta Filtri</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sezione Video -->
        <div class="col-12">
            <div class="video-section">
                <div id="loadingIndicator" style="display: none; text-align: center; margin: 20px;">
                    <i class="fas fa-spinner fa-spin"></i> Caricamento in corso...
                </div>
                <!-- Sezione video -->
                <div id="videoContainer" class="video-container">
                    <!-- Il contenuto verrà caricato dinamicamente via JavaScript -->
                </div>

                <!-- Link di paginazione -->
                <div class="pagination-container mt-4">
                    @if ($videos->lastPage() > 1)
                        <ul class="pagination">
                            <li class="{{ $videos->onFirstPage() ? 'disabled' : '' }}">
                                <a href="{{ $videos->previousPageUrl() }}" class="pagination-link">&laquo;</a>
                            </li>

                            @for ($i = 1; $i <= $videos->lastPage(); $i++)
                                <li class="{{ $videos->currentPage() == $i ? 'active' : '' }}">
                                    <a href="{{ $videos->url($i) }}" class="pagination-link">{{ $i }}</a>
                                </li>
                            @endfor

                            <li class="{{ $videos->currentPage() == $videos->lastPage() ? 'disabled' : '' }}">
                                <a href="{{ $videos->nextPageUrl() }}" class="pagination-link">&raquo;</a>
                            </li>
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="{{ asset('js/archivio.js') }}"></script>
@endpush