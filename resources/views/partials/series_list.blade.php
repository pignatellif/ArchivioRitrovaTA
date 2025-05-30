@foreach($series as $serie)
<div class="series-block">
    <h2 class="series-title">{{ $serie->nome }}</h2>
    <p class="series-description">{{ $serie->descrizione }}</p>

    <div class="carousel-wrapper">
        <button class="arrow__btn left-arrow" onclick="scrollCarousel({{ $loop->index }}, -1)">
            <i class="fa-solid fa-caret-left"></i>
        </button>

        <div class="carousel" id="carousel-{{ $loop->index }}">
            @foreach($serie->videos as $video)
            <div class="video-card">
                <a href="{{ route('video.show', $video->id) }}" class="video-link">
                    <img src="https://img.youtube.com/vi/{{ $video->youtube_id }}/hqdefault.jpg"
                        alt="Anteprima del video {{ $video->titolo }}"
                        class="video-thumbnail"
                        onerror="this.onerror=null; this.src='/path/to/fallback-image.jpg';">
                    <div class="overlay">
                        <div class="play-icon"><i class="fa-solid fa-play"></i></div>
                    </div>
                </a>
                <div class="card-body">
                    <h5 class="card-title">{{ $video->titolo }}</h5>
                    <div class="card-meta">
                        <div class="meta-row">
                            <span>{{ $video->autore->nome ?? 'Autore sconosciuto' }}, {{ $video->anno }}, {{ $video->location->name ?? 'Luogo sconosciuto' }}</span>
                        </div>
                        <div class="meta-row">
                            <span>{{ gmdate('i:s', $video->durata_secondi) }} min</span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <button class="arrow__btn right-arrow" onclick="scrollCarousel({{ $loop->index }}, 1)">
            <i class="fa-solid fa-caret-right"></i>
        </button>
    </div>

</div>
@endforeach

<div class="pagination">
    {{ $series->links() }}
</div>