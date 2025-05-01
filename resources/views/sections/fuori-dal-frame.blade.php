@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/fuori-dal-frame.css') }}">
@endpush

@section('content')
    <!-- HERO SECTION -->
    <section class="hero-section">
        <div class="hero-content">
            <h1>Le voci dietro le immagini.</h1>
            <hr class="hero-divider">
            <p>Mini-interviste autentiche ai protagonisti dei filmati dell’archivio. Storie personali, ricordi vivi, emozioni sussurrate che completano ciò che la pellicola non dice.</p>
        </div>
    </section>
    
    <!-- AUTHORS AND CHARACTERS SECTION -->
    <section class="intro-section">
        <div class="container">
            <div class="row">
                <!-- Authors Column -->
                <div class="col-md-6">
                    <h2>Gli Autori</h2>
                    <p>Scopri chi ha reso possibile tutto questo. Gli autori sono le menti creative e i narratori che hanno dato vita a queste storie uniche.</p>
                    <a href="{{ route ('personaggi') }}" class="btn section-btn">Scopri gli Autori</a>
                </div>
                <!-- Characters Column -->
                <div class="col-md-6">
                    <h2>I Personaggi</h2>
                    <p>Conosci i protagonisti dei filmati. Le loro storie, i loro ricordi e le loro emozioni sono il cuore pulsante di questo archivio.</p>
                    <a href="{{ route ('personaggi') }}" class="btn section-btn">Scopri i Personaggi</a>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <!-- Aggiunta dei file JS -->
    <script src="{{ asset('js/fuori-dal-frame.js') }}"></script>
@endsection