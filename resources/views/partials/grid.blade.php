<div id="videoContainer" class="video-container">
    @forelse($videos as $video)
        <div class="video-card"
            data-title="{{ $video->titolo }}" 
            data-year="{{ $video->anno }}" 
            data-author="{{ $video->autore->nome ?? 'N/A' }}" 
            data-duration="{{ $video->durata_secondi }}" 
            data-location="{{ $video->luogo }}">
            <div class="card">
                <a href="{{ route('video.show', $video->id) }}">
                    <img 
                        src="https://img.youtube.com/vi/{{ $video->youtube_id }}/hqdefault.jpg" 
                        alt="{{ $video->titolo }}" 
                        class="video-thumbnail"
                        onerror="this.onerror=null; this.src='/path/to/fallback-image.jpg';"
                    >
                </a>
                <div class="card-body">
                    <h5 class="card-title">{{ $video->titolo }}</h5>
                    <p class="card-text">
                        {{ $video->autore->nome ?? 'Autore sconosciuto' }},
                        {{ $video->anno }},
                        {{ gmdate('i:s', $video->durata_secondi) }},
                        {{ $video->location->name ?? 'Luogo sconosciuto' }}
                    </p>
                    @php
                        $filteredTags = $video->tags->reject(function($tag) {
                            $name = strtolower($tag->nome);
                            return $name === 'fuori dal tacco' || $name === 'fuori dal frame';
                        });
                    @endphp
                    @if($filteredTags->isNotEmpty())
                        <div class="tags">
                            @foreach($filteredTags as $tag)
                                <span class="custom-tag">{{ $tag->nome }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <p class="no-videos">Nessun video trovato.</p>
    @endforelse
</div>

<div class="section-divider"></div>

<div class="pagination-container">
    {{ $videos->links('pagination::bootstrap-4') }}
</div>
