@forelse($videos as $video)
    <div class="video-card"
        data-title="{{ $video->title }}" 
        data-year="{{ $video->year }}" 
        data-author="{{ $video->author }}" 
        data-duration="{{ $video->duration }}">
        <div class="card">
            <a href="{{ route('video.show', $video->id) }}">
                <img 
                    src="https://img.youtube.com/vi/{{ $video->youtube_id }}/hqdefault.jpg" 
                    alt="{{ $video->title }}" 
                    class="video-thumbnail"
                >
            </a>
            <div class="card-body">
                <h5 class="card-title">{{ $video->title }}</h5>
                <p class="card-text">{{ $video->author }}, {{ $video->year }}, {{ gmdate('i:s', $video->duration) }}</p>
            </div>
        </div>
    </div>
@empty
    <p class="no-videos">Nessun video trovato.</p>
@endforelse