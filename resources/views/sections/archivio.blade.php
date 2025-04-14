@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/archivio.css') }}">
@endpush

@section('content')
    <!-- HERO SECTION -->
    <section class="hero-section">
        <div class="hero-content">
            <h2>Ogni pellicola è un ricordo che torna a parlare. </h2>
            <hr class="border-light w-25 mx-auto my-3">
            <p>Scopri i filmati che compongono la nostra memoria condivisa</p>
        </div>
    </section>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Sezione filtri -->
    <form id="filterForm" class="filter-container container">
        <div class="row w-100 d-flex">
            <!-- Filtro Titolo -->
            <div class="col-md-3">
                <input type="text" class="form-control filter" id="filterTitle" placeholder="Titolo">
            </div>

            <!-- Filtro Anno -->
            <div class="col-md-3">
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

            <!-- Filtro Regista -->
            <div class="col-md-3">
                <input type="text" class="form-control filter" id="filterAuthor" placeholder="Regista">
            </div>

            <!-- Filtro Durata -->
            <div class="col-md-3">
                <div class="slider-container">
                    <span class="slider-label">Minuti</span>
                    <span id="durationValue1" class="slider-value">{{ $minDuration }}</span>
                    <div class="slider-wrapper">
                        <input type="range" min="{{ $minDuration }}" max="{{ $maxDuration }}" value="{{ $minDuration }}" class="slider" id="durationSlider1">
                        <input type="range" min="{{ $minDuration }}" max="{{ $maxDuration }}" value="{{ $maxDuration }}" class="slider" id="durationSlider2">
                    </div>
                    <span id="durationValue2" class="slider-value">{{ $maxDuration }}</span>
                </div>
            </div>
        </div>
    </form>

    <!-- Griglia video -->
    <div id="videoContainer" class="video-container">
        @include('partials.video_list', ['videos' => $videos])
    </div>

    <!-- Link di paginazione -->
    <div class="pagination-container">
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
@endsection

@push('scripts')
    <script src="{{ asset('js/archivio.js') }}"></script>
@endpush