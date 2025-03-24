@foreach($series as $serie)
    <h2 class="series-title">{{ $serie->name }}</h2>
    <div class="row series-row">
        <div class="col-md-4 series-description-wrapper">
            <p class="series-description">{{ $serie->description }}</p>
        </div>
        <div class="col-md-8">
            <div class="wrapper">
                <div class="carousel" id="carousel-{{ $loop->index }}">
                    <button class="arrow__btn left-arrow" aria-label="Previous">‹</button>

                    <div class="video-list">
                        @foreach($serie->videos as $video)
                            <div class="item">
                                <a href="{{ route('video.show', $video->id) }}">
                                    <img src="https://img.youtube.com/vi/{{ $video->youtube_id }}/hqdefault.jpg" 
                                         alt="Anteprima del video {{ $video->title }}" 
                                         class="video-thumbnail">
                                    <div class="video-title-wrapper">
                                        <p class="video-title">{{ $video->title }}</p>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>

                    <button class="arrow__btn right-arrow" aria-label="Next">›</button>
                </div>
            </div>
        </div>
    </div>
    <hr class="divider">
@endforeach
{{ $series->links() }}