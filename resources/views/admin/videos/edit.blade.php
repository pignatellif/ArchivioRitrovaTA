@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2>Modifica Video</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('videos.update', $video->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Titolo -->
        <div class="mb-3">
            <label for="titolo" class="form-label">Titolo</label>
            <input type="text" name="titolo" class="form-control" value="{{ old('titolo', $video->titolo) }}" required>
        </div>

        <!-- Descrizione -->
        <div class="mb-3">
            <label for="descrizione" class="form-label">Descrizione</label>
            <textarea name="descrizione" class="form-control" rows="4">{{ old('descrizione', $video->descrizione) }}</textarea>
        </div>

        <!-- Anno -->
        <div class="mb-3">
            <label for="anno" class="form-label">Anno</label>
            <input type="number" name="anno" class="form-control" value="{{ old('anno', $video->anno) }}">
        </div>

        <!-- Durata -->
        <div class="mb-3">
            <label for="durata_secondi" class="form-label">Durata (secondi)</label>
            <input type="number" name="durata_secondi" class="form-control" value="{{ old('durata_secondi', $video->durata_secondi) }}" required>
        </div>

        <!-- Formato (nome) -->
        <div class="mb-3">
            <label for="formato" class="form-label">Formato</label>
            <input type="text" name="formato" class="form-control" placeholder="Nome formato" value="{{ old('formato', $video->formato->nome ?? '') }}">
        </div>

        <!-- Location -->
        <div class="mb-3">
            <label for="location" class="form-label">Luogo</label>
            <input type="text" name="location" id="location" class="form-control" placeholder="Nome luogo" value="{{ old('location', optional($video->location)->name) }}">
        </div>

        <!-- Famiglie (nomi separati da virgola) -->
        <div class="mb-3">
            <label for="famiglie" class="form-label">Famiglie</label>
            <input type="text" name="famiglie" class="form-control" placeholder="Nomi famiglie separati da virgola"
                value="{{ old('famiglie', $video->famiglie->pluck('nome')->implode(', ')) }}">
            <small class="form-text text-muted">Inserisci uno o più nomi famiglia separati da virgola.</small>
        </div>

        <!-- YouTube -->
        <div class="mb-3">
            <label for="youtube_id" class="form-label">Link o ID YouTube</label>
            <input type="text" id="youtube_id" name="youtube_id" class="form-control" value="{{ old('youtube_id', $video->youtube_id) }}">
        </div>

        <!-- Anteprima YouTube -->
        <div class="mb-3" id="youtube-preview" style="display: none;">
            <label class="form-label">Anteprima Video</label>
            <div class="ratio ratio-16x9">
                <iframe id="youtube-iframe" src="" title="Anteprima Video" frameborder="0" allowfullscreen></iframe>
            </div>
        </div>

        <!-- Autore (nome) -->
        <div class="mb-3">
            <label for="autore" class="form-label">Autore</label>
            <input type="text" name="autore" class="form-control" placeholder="Nome autore" value="{{ old('autore', optional($video->autore)->nome) }}">
        </div>

        <!-- Tag (nomi separati da virgola) -->
        <div class="mb-3">
            <label for="tags" class="form-label">Tag</label>
            <input type="text" name="tags" class="form-control" placeholder="Nomi tag separati da virgola"
                value="{{ old('tags', $video->tags->pluck('nome')->implode(', ')) }}">
            <small class="form-text text-muted">Inserisci uno o più nomi tag separati da virgola.</small>
        </div>

        <!-- Pulsanti -->
        <button type="submit" class="btn btn-success">Salva Modifiche</button>
        <a href="{{ route('videos.index') }}" class="btn btn-secondary">Annulla</a>
    </form>
</div>
@endsection

@section('scripts')
<script>
    function extractYoutubeId(urlOrId) {
        if (!urlOrId) return null;
        // already an 11-char youtube id
        if (/^[a-zA-Z0-9_-]{11}$/.test(urlOrId)) return urlOrId;
        // try to extract from url
        const patterns = [
            /(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/|youtube\.com\/shorts\/)([a-zA-Z0-9_-]{11})/,
        ];
        for (const p of patterns) {
            const m = urlOrId.match(p);
            if (m) return m[1];
        }
        return null;
    }

    function updatePreview() {
        const val = document.getElementById('youtube_id').value;
        const code = extractYoutubeId(val);
        const preview = document.getElementById('youtube-preview');
        const iframe = document.getElementById('youtube-iframe');
        if (code) {
            iframe.src = 'https://www.youtube.com/embed/' + code;
            preview.style.display = 'block';
        } else {
            iframe.src = '';
            preview.style.display = 'none';
        }
    }

    document.getElementById('youtube_id').addEventListener('input', updatePreview);
    window.addEventListener('DOMContentLoaded', updatePreview);
</script>
@endsection