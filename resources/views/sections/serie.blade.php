@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/serie.css') }}">
@endpush

@section('content')

<!-- HERO SECTION -->
<section class="hero-section">
    <div class="hero-container">
        <h2>Le nostre serie</h2>
        <p>Esplora le serie che raccontano la memoria privata della Puglia, trasformandola in un patrimonio collettivo.</p>
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