@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/video.css') }}">
@endpush

@section('content')
    <div class="video-page" id="video-show">
        <!-- Sezione video -->
        <div class="video-main-player">
            <iframe 
                id="youtube-iframe"
                src="https://www.youtube.com/embed/{{ \Illuminate\Support\Str::afterLast($video->link_youtube, 'v=') }}"
                frameborder="0" 
                allow="autoplay; encrypted-media"
                allowfullscreen>
            </iframe>
        </div>

        <!-- Sezione dettagli video -->
        <div class="video-details-wrapper">
            <!-- Colonna descrizione -->
            <div class="video-description-container">
                <h2 class="video-title">{{ $video->titolo }}</h2>
                <p class="video-description">{{ $video->descrizione }}</p>
                <p>
                    <strong>Autore:</strong>
                    @if($video->autore)
                        {{ $video->autore->nome }}
                    @else
                        <em>Non disponibile</em>
                    @endif
                </p>
                <p><strong>Durata:</strong> {{ gmdate('i:s', $video->durata_secondi) }}</p>
            </div>

            <!-- Colonna informazioni aggiuntive -->
            <div class="video-extra-info">
                <ul>
                    <li><strong>Anno di produzione:</strong> {{ $video->anno }}</li>
                    <li><strong>Formato:</strong>
                        @if($video->formato)
                            {{ $video->formato }}
                        @else
                            <em>Non disponibile</em>
                        @endif
                    </li>
                    <li><strong>Famiglia:</strong> {{ $video->famiglia }}</li>
                    <li><strong>Luogo:</strong>
                        @if($video->location)
                            {{ $video->location->name }}
                        @else
                            <em>Non disponibile</em>
                        @endif
                    </li>
                    <li>
                        <strong>Tag:</strong>
                        @if($video->tags && $video->tags->count())
                            @foreach($video->tags as $tag)
                                <span class="video-tag">{{ $tag->name ?? $tag->nome }}</span>
                            @endforeach
                        @else
                            <em>Non disponibili</em>
                        @endif
                    </li>
                </ul>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var iframe = document.getElementById('youtube-iframe');
    // Recupera l'URL originale
    var originalSrc = iframe.getAttribute('src');
    // Aggiungi parametri JS API, autoplay e mute
    var separator = originalSrc.includes('?') ? '&' : '?';
    var newSrc = originalSrc + separator + 'enablejsapi=1&autoplay=1&mute=1';
    iframe.setAttribute('src', newSrc);

    // Carica l'API di YouTube solo dopo che l'iframe è stato aggiornato
    var tag = document.createElement('script');
    tag.src = "https://www.youtube.com/iframe_api";
    var firstScriptTag = document.getElementsByTagName('script')[0];
    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

    window.onYouTubeIframeAPIReady = function() {
        var player = new YT.Player('youtube-iframe', {
            events: {
                'onReady': function(event) {
                    event.target.mute();
                    event.target.playVideo();
                }
            }
        });
    }
});
</script>
@endpush