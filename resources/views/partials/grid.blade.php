<div id="videoContainer" class="video-container recognition-section">
    @forelse($videos as $video)
        <div class="video-card"
            data-title="{{ $video->titolo }}" 
            data-year="{{ $video->anno }}" 
            data-author="{{ $video->autore->nome ?? 'N/A' }}" 
            data-duration="{{ $video->durata_secondi }}" 
            data-location="{{ $video->luogo }}">
            <div class="card">
                <a href="{{ route('video.show', $video->id) }}" class="video-link">
                    <img 
                        src="https://img.youtube.com/vi/{{ $video->youtube_id }}/hqdefault.jpg" 
                        alt="{{ $video->titolo }}" 
                        class="video-thumbnail"
                        onerror="this.onerror=null; this.src='/path/to/fallback-image.jpg';"
                    >
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
        </div>
    @empty
        <div class="empty-state">
            <div class="empty-content">
                <div class="empty-icon">
                    <i class="fa-solid fa-film"></i>
                </div>
                <h3>Nessun video disponibile</h3>
                <p>Al momento non ci sono video pubblicati.<br>
                Torna presto per scoprire nuovi contenuti!</p>
            </div>
        </div>
    @endforelse
</div>
