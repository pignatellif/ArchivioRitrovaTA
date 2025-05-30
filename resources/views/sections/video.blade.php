@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/video.css') }}">
@endpush

@section('content')
    <div class="cinema-container">
        <!-- Video Player Section -->
        <div class="video-player-section">
            <div class="video-wrapper">
                <iframe 
                    id="youtube-iframe"
                    src="https://www.youtube.com/embed/{{ $video->youtube_id }}"
                    frameborder="0" 
                    allow="autoplay; encrypted-media"
                    allowfullscreen>
                </iframe>
            </div>
        </div>

        <!-- Video Info Section -->
        <div class="video-info-section">
            <div class="video-main-info">
                <h1 class="video-title">{{ $video->titolo }}</h1>
                
                <div class="video-meta">
                    <span class="meta-item">
                        <i class="fa-regular fa-clock"></i>
                        {{ gmdate('i:s', $video->durata_secondi) }}
                    </span>
                    <span class="meta-item">
                        <i class="fa-regular fa-calendar"></i>
                        {{ $video->anno }}
                    </span>
                    @if($video->autore)
                        <span class="meta-item">
                            <i class="fa-regular fa-user"></i>
                            {{ $video->autore->nome }}
                        </span>
                    @endif
                </div>

                @if($video->descrizione)
                    <div class="video-description">
                        <p>{{ $video->descrizione }}</p>
                    </div>
                @endif
            </div>

            <div class="video-details-grid">
                @if($video->formato)
                    <div class="detail-item">
                        <span class="detail-label">Formato</span>
                        <span class="detail-value">{{ $video->formato->nome ?? $video->formato }}</span>
                    </div>
                @endif

                @if($video->location)
                    <div class="detail-item">
                        <span class="detail-label">Luogo</span>
                        <span class="detail-value">{{ $video->location->name }}</span>
                    </div>
                @endif

                @if($video->famiglie && $video->famiglie->count())
                    <div class="detail-item">
                        <span class="detail-label">Famiglie</span>
                        <div class="tags-container">
                            @foreach($video->famiglie as $famiglia)
                                <span class="tag">{{ $famiglia->nome }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($video->tags && $video->tags->count())
                    <div class="detail-item">
                        <span class="detail-label">Tag</span>
                        <div class="tags-container">
                            @foreach($video->tags as $tag)
                                <span class="tag">{{ $tag->nome ?? $tag->name }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Similar Videos Section -->
        @if($similarVideos->count())
        <div class="correlati-block">
            <h2 class="section-title">Video correlati</h2>
            <div class="carousel-wrapper">
                <button class="arrow__btn left-arrow btn-prev" onclick="scrollCarousel('correlati', -1)">
                    <i class="fa-solid fa-caret-left"></i>
                </button>
                    <div class="carousel" id="carousel-correlati">
                    @foreach($similarVideos as $similarVideo)
                        <div class="video-card item">
                            <a href="{{ route('video.show', $similarVideo->id) }}" class="video-link">
                                <img src="https://img.youtube.com/vi/{{ $similarVideo->youtube_id }}/hqdefault.jpg"
                                    alt="Anteprima del video {{ $similarVideo->titolo }}"
                                    class="video-thumbnail"
                                    onerror="this.onerror=null; this.src='/path/to/fallback-image.jpg';">
                                <div class="overlay">
                                    <div class="play-icon"><i class="fa-solid fa-play"></i></div>
                                </div>
                            </a>
                            <div class="card-body">
                                <h5 class="card-title">{{ $similarVideo->titolo }}</h5>
                                <div class="card-meta">
                                    <div class="meta-row">
                                        <span>{{ $similarVideo->autore->nome ?? 'Autore sconosciuto' }}, {{ $similarVideo->anno }}, {{ $similarVideo->location->name ?? 'Luogo sconosciuto' }}</span>
                                    </div>
                                    <div class="meta-row">
                                        <span>{{ gmdate('i:s', $similarVideo->durata_secondi) }} min</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <button class="arrow__btn right-arrow btn-next" onclick="scrollCarousel('correlati', 1)">
                    <i class="fa-solid fa-caret-right"></i>
                </button>
            </div>
        </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/video.js') }}"></script>
@endpush