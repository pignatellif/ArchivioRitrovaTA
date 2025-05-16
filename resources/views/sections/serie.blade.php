@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/serie.css') }}">
@endpush

@section('content')

<!-- HERO SECTION -->
<section class="hero-section">
    <div class="hero-container">
        <h2>Archivio Regionale dei Filmini di Famiglia</h2>
        <p>Archivio RitrovaTA raccoglie e valorizza i filmini di famiglia della Puglia, custodendo la memoria privata come patrimonio collettivo.</p>
    </div>
</section>

<div class="section-divider"></div>

<section class="series-section">
    <div class="series-container">
        @include('partials.series_list', ['series' => $series])
    </div>
</section>

@endsection

@push('scripts')
    <script src="{{ asset('js/serie.js') }}"></script>
@endpush