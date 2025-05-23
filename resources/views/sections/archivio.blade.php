@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/archivio.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
@endpush

@section('content')

<section class="hero-section">
    <div class="hero-container">
        <h2>Archivio Regionale dei Filmini di Famiglia</h2>
        <p>Archivio RitrovaTA raccoglie e valorizza i filmini di famiglia della Puglia, custodendo la memoria privata come patrimonio collettivo.</p>
    </div>
</section>

<div class="section-divider"></div>

<section class="control-section">
    <div class="control-container">
        <div class="view-switch">
            <div class="btn-group" role="group" aria-label="Visualizzazione">
                <input type="radio" class="btn-check" name="viewOption" id="viewGrid" autocomplete="off" checked>
                <label class="btn btn-outline-secondary" for="viewGrid"><i class="fa-solid fa-film"></i> Video</label>
                <input type="radio" class="btn-check" name="viewOption" id="viewMap" autocomplete="off">
                <label class="btn btn-outline-secondary" for="viewMap"><i class="fa-solid fa-map-location-dot"></i> Mappa</label>
            </div>
        </div>
        <button id="toggleFilters" class="btn btn-outline-primary"><i class="fa-solid fa-sliders"></i> Filtri</button>
    </div>
</section>

<section id="dynamicViewSection" class="dynamic-view-section">
    <!-- La vista parziale 'grid' verrà caricata qui dinamicamente -->
</section>

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
                        <div class="input-wrapper select-wrapper">
                            <select class="form-control filter" id="filterAuthor">
                                <option value="">Tutti</option>
                                @foreach($authors as $author)
                                    <option value="{{ $author }}">{{ $author }}</option>
                                @endforeach
                            </select>
                            <i class="fa-solid fa-caret-down"></i>
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
                        <div class="input-wrapper select-wrapper">
                            <select class="form-control filter" id="filterFormat">
                                <option value="">Tutti</option>
                                @foreach($formats as $format)
                                    <option value="{{ $format }}">{{ $format }}</option>
                                @endforeach
                            </select>
                            <i class="fa-solid fa-caret-down"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="filter-row">
                <!-- Filtro Famiglia -->
                <div class="mb-3">
                    <div class="slider-container">
                        <span class="slider-label">Famiglia</span>
                        <div class="input-wrapper select-wrapper">
                            <select class="form-control filter" id="filterFamily">
                                <option value="">Tutti</option>
                                @foreach($families as $family)
                                    <option value="{{ $family }}">{{ $family }}</option>
                                @endforeach
                            </select>
                            <i class="fa-solid fa-caret-down"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="filter-row">
                <!-- Filtro Luogo -->
                <div class="mb-3">
                    <div class="slider-container">
                        <span class="slider-label">Luogo</span>
                        <div class="input-wrapper select-wrapper">
                            <select class="form-control filter" id="filterLocation">
                                <option value="">Tutti</option>
                                @foreach($locations as $location)
                                    <option value="{{ $location }}">{{ $location }}</option>
                                @endforeach
                            </select>
                            <i class="fa-solid fa-caret-down"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="filter-row">
                <!-- Filtro Tag -->
                <div class="mb-3">
                    <div class="slider-container">
                        <span class="slider-label">Tag</span>
                        <div class="input-wrapper select-wrapper" style="width:100%;">
                            <button type="button" class="toggle-button" id="toggleTagFilter" style="margin-bottom:8px;">Mostra</button>
                            <div class="tag-list d-none" id="tagList" style="margin-top:8px;">
                                @foreach($tags as $tag)
                                    <div class="tag-item" style="display:inline-block; margin-right:10px;">
                                        <input type="checkbox" id="tag_{{ Str::slug($tag) }}" value="{{ $tag }}" class="form-check-input filter-tag">
                                        <label for="tag_{{ Str::slug($tag) }}">{{ $tag }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="filter-row">
                <button type="button" id="resetFilters" class="reset-button w-100">Resetta Filtri</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
    <script src="{{ asset('js/archivio.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
@endpush