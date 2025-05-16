@foreach($series as $serie)
<div>
    <h2 class="series-title">{{ $serie->nome }}</h2>
    
    <div class="wrapper mb-4">
        <div class="carousel" id="carousel-{{ $loop->index }}">
            <button class="arrow__btn left-arrow" aria-label="Previous">‹</button>
            
            <div class="video-list">
                @foreach($serie->videos as $video)
                <div class="item">
                    <a href="{{ route('video.show', $video->id) }}">
                        <img src="https://img.youtube.com/vi/{{ $video->youtube_id }}/hqdefault.jpg" 
                        alt="Anteprima del video {{ $video->titolo }}" 
                        class="video-thumbnail">
                        <div class="video-title-wrapper">
                            <p class="video-title">{{ $video->titolo }}</p>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
            
            <button class="arrow__btn right-arrow" aria-label="Next">›</button>
        </div>
    </div>
    
    <div class="series-description-wrapper mb-3">
        <p class="series-description">{{ $serie->descrizione }}</p>
    </div>

    <div class="section-divider"></div>
</div>
@endforeach
{{ $series->links() }}