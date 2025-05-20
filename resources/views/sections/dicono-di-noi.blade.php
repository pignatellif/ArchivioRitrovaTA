@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('css/dicono-di-noi.css') }}">
@push('styles')

@endpush

@section('content')
<section class="testimonials-section">
    <h2 class="section-title">Dicono di noi</h2>
    <div class="testimonials-container">
        <div class="testimonial-card">
            <p class="testimonial-text">"Un progetto straordinario per riscoprire la memoria collettiva. Intuitivo, coinvolgente, necessario."</p>
            <div class="testimonial-author">– La Repubblica</div>
        </div>
        <div class="testimonial-card">
            <p class="testimonial-text">"Un lavoro impeccabile di valorizzazione del patrimonio audiovisivo locale. Da seguire con attenzione."</p>
            <div class="testimonial-author">– Archivio Digitale Puglia</div>
        </div>
        <div class="testimonial-card">
            <p class="testimonial-text">"Il sito è semplice da usare e pieno di contenuti interessanti. Finalmente un archivio che parla alle persone."</p>
            <div class="testimonial-author">– Utente registrato</div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="{{ asset('js/dicono-di-noi.js') }}"></script>
@endpush