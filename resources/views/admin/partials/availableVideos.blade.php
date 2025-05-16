<ul id="availableVideos" class="list-group list-group-flush">
    @forelse($availableVideos as $video)
        <li class="list-group-item d-flex justify-content-between align-items-start flex-wrap" data-id="{{ $video->id }}">
            <div class="me-2">
                <strong>{{ $video->titolo }}</strong><br>
                <small>Anno: {{ $video->anno }}</small><br>
                <small>Formato: {{ $video->formato }}</small><br>
                <small>Autore: {{ $video->autore?->nome ?? 'N/A' }}</small><br>
                <small>Luogo: {{ $video->location?->name ?? 'N/A' }}</small><br>
                <small>Tag:
                    @foreach($video->tags as $tag)
                        <span class="badge bg-secondary">{{ $tag->nome }}</span>
                    @endforeach
                </small>
            </div>
            <button type="button" class="btn btn-success btn-sm add-video mt-2" data-id="{{ $video->id }}">Aggiungi</button>
        </li>
    @empty
        <li class="list-group-item text-center">Nessun video disponibile.</li>
    @endforelse
</ul>