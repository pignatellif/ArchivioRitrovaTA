@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/serie.css') }}">
@endpush

@section('content')
<section class="hero-section">
    <div class="hero-overlay">
        <div class="hero-text">
            <h2>serie</h2>
            <hr>
            <h3>Ogni pellicola è un ricordo che torna a parlare.</h3>
            <p>Scopri i filmati che compongono la nostra memoria condivisa.</p>
        </div>
    </div>
</section>

<section class="series-section">
    <div class="series-container">
        @include('partials.series_list', ['series' => $series])
    </div>
</section>

<section class="spacer-section">
    <div class="container">
        <!-- Spazio per separare il contenuto dal footer -->
    </div>
</section>

@endsection

@push('scripts')
    <script src="{{ asset('js/serie.js') }}"></script>
@endpush