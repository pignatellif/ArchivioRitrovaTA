@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/video.css') }}">
@endpush

@section('content')
    <div class="container py-5" id="video-show">
        <!-- Sezione video -->
        <div class="video-container">
            <iframe 
                src="https://www.youtube.com/embed/{{ $video->youtube_id }}" 
                frameborder="0" 
                allowfullscreen>
            </iframe>
        </div>

        <!-- Sezione dettagli video -->
        <div class="row mt-4">
            <!-- Colonna descrizione -->
            <div class="col-md-8">
                <h2 class="video-title">{{ $video->title }}</h2>
                <p class="video-description">{{ $video->description }}</p>
                <p><strong>Autore:</strong> {{ $video->author }}</p>
                <p><strong>Durata:</strong> {{ gmdate('i:s', $video->duration) }}</p>
            </div>

            <!-- Colonna informazioni aggiuntive -->
            <div class="col-md-4 video-info">
                <ul>
                    <li><strong>Anno di produzione:</strong> {{ $video->year }}</li>
                    <li><strong>Formato:</strong> {{ $video->format }}</li>
                    <li><strong>Famiglia:</strong> {{ $video->family }}</li>
                    <li><strong>Luogo:</strong> {{ $video->location }}</li>
                    <li><strong>Tag:</strong> {{ $video->tags }}</li>
                </ul>
            </div>
        </div>
    </div>
@endsection
