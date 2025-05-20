@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('css/sostienici.css') }}">
@push('styles')

@endpush

@section('content')
<section class="support-section">
    <h2 class="section-title">Sostienici</h2>
    <p class="support-text">
        Aiutaci a mantenere vivo e accessibile l’archivio. Ogni contributo, anche piccolo, fa la differenza.
    </p>

    <div class="support-options">
        <a href="https://paypal.com/donate?hosted_button_id=XYZ" class="donate-btn">Fai una donazione</a>
        
        <ul class="support-list">
            <li>Condividi il progetto con chi ama la storia del territorio</li>
            <li>Inviaci materiale video o fotografico</li>
            <li>Partecipa come volontario</li>
            <li>Segnalaci un errore o una storia dimenticata</li>
        </ul>

        <form class="newsletter-form">
            <input type="email" placeholder="La tua email per restare aggiornato">
            <button type="submit">Iscriviti alla newsletter</button>
        </form>
    </div>
</section>
@endsection

@push('scripts')
<script src="{{ asset('js/sostienici.js') }}"></script>
@endpush