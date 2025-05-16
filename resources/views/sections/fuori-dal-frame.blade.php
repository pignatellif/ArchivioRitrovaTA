@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/fuori-dal-frame.css') }}">
@endpush

@section('content')

<section class="hero-section">
    <div class="hero-container">
        <h2>Le voci dietro le immagini</h2>
        <p>Mini-interviste autentiche ai protagonisti dei filmati dell’archivio. Storie personali, ricordi vivi, emozioni sussurrate che completano ciò che la pellicola non dice.</p>
    </div>
</section>

<div class="section-divider"></div>

<section class="archive-section">
    <div class="archive-half text">
        <h2>I Personaggi</h2>
        <p>
            Conosci i protagonisti dei filmati. Le loro storie, i loro ricordi e le loro emozioni sono il cuore pulsante di questo archivio.
        </p>
        <a href="{{ route('personaggi') }}" class="archive-button">Guarda i filmati</a>
    </div>
    <div class="archive-half image">
        <img src="{{ asset('img/home/image3.png') }}" alt="Archivio" class="archive-image">
    </div>
</section>

<div class="section-divider"></div>

<section class="archive-section">
    <div class="archive-half image">
        <img src="{{ asset('img/home/image4.png') }}" alt="Archivio" class="archive-image">
    </div>
    <div class="archive-half text">
        <h2>Gli Autori</h2>
        <p>
            Scopri chi ha reso possibile tutto questo. Gli autori sono le menti creative e i narratori che hanno dato vita a queste storie uniche.
        </p>
        <a href="{{ route('autori') }}" class="archive-button">Scopri chi c'è dietro</a>
    </div>
</section>

<div class="section-divider"></div>
@endsection

@section('scripts')
    <!-- Aggiunta dei file JS -->
    <script src="{{ asset('js/fuori-dal-frame.js') }}"></script>
@endsection