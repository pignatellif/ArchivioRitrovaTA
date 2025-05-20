@foreach($series as $serie)
<div class="series-block">
    <h2 class="series-title">{{ $serie->nome }}</h2>
    <p class="series-description">{{ $serie->descrizione }}</p>

    <div class="carousel-wrapper">
        <button class="arrow__btn left-arrow" onclick="scrollCarousel({{ $loop->index }}, -1)"><i class="fa-solid fa-caret-left"></i></button>

        <div class="carousel" id="carousel-{{ $loop->index }}">
            @foreach($serie->videos as $video)
            <div class="item">
                <a href="{{ route('video.show', $video->id) }}">
                    <img src="https://img.youtube.com/vi/{{ $video->youtube_id }}/hqdefault.jpg"
                        alt="Anteprima del video {{ $video->titolo }}">
                    <span class="item-info">
                        <strong>{{ $video->titolo }}</strong><br>
                        <small>{{ $video->anno }} - {{ floor($video->durata_secondi / 60) }}:{{ str_pad($video->durata_secondi % 60, 2, '0', STR_PAD_LEFT) }}</small>
                    </span>
                </a>
            </div>
            @endforeach
        </div>

        <button class="arrow__btn right-arrow" onclick="scrollCarousel({{ $loop->index }}, 1)"><i class="fa-solid fa-caret-right"></i></button>
    </div>

    <div class="section-divider"></div>
</div>
@endforeach
{{ $series->links() }}
