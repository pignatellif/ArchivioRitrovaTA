@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2>Aggiungi un Nuovo Video</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('videos.store') }}" method="POST">
        @csrf

        <!-- Titolo -->
        <div class="mb-3">
            <label for="titolo" class="form-label">Titolo</label>
            <input type="text" name="titolo" class="form-control" value="{{ old('titolo') }}" required>
        </div>

        <!-- Descrizione -->
        <div class="mb-3">
            <label for="descrizione" class="form-label">Descrizione</label>
            <textarea name="descrizione" class="form-control" rows="4">{{ old('descrizione') }}</textarea>
        </div>

        <!-- Anno -->
        <div class="mb-3">
            <label for="anno" class="form-label">Anno</label>
            <input type="number" name="anno" class="form-control" value="{{ old('anno') }}">
        </div>

        <!-- Durata -->
        <div class="mb-3">
            <label for="durata_secondi" class="form-label">Durata (secondi)</label>
            <input type="number" name="durata_secondi" class="form-control" value="{{ old('durata_secondi') }}" required>
        </div>

        <!-- Formato (nome) -->
        <div class="mb-3">
            <label for="formato" class="form-label">Formato</label>
            <input type="text" name="formato" class="form-control" placeholder="Nome formato" value="{{ old('formato') }}">
        </div>

        <!-- Location -->
        <div class="mb-3">
            <label for="location" class="form-label">Luogo</label>
            <input type="text" name="location" id="location" class="form-control" placeholder="Nome luogo" value="{{ old('location') }}">
        </div>

        <!-- Famiglie (nomi separati da virgola) -->
        <div class="mb-3">
            <label for="famiglie" class="form-label">Famiglie</label>
            <input type="text" name="famiglie" class="form-control" placeholder="Nomi famiglie separati da virgola"
                value="{{ is_array(old('famiglie')) ? implode(',', old('famiglie')) : old('famiglie') }}">
            <small class="form-text text-muted">Inserisci uno o più nomi famiglia separati da virgola.</small>
        </div>

        <!-- YouTube -->
        <div class="mb-3">
            <label for="youtube_id" class="form-label">Link o ID YouTube</label>
            <input type="text" id="youtube_id" name="youtube_id" class="form-control" value="{{ old('youtube_id') }}">
        </div>

        <!-- Anteprima YouTube -->
        <div class="mb-3" id="youtube-preview" style="display: none;">
            <label class="form-label">Anteprima Video</label>
            <div class="ratio ratio-16x9">
                <iframe id="youtube-iframe" src="" frameborder="0" allowfullscreen></iframe>
            </div>
        </div>

        <!-- Autore (nome) -->
        <div class="mb-3">
            <label for="autore" class="form-label">Autore</label>
            <input type="text" name="autore" class="form-control" placeholder="Nome autore" value="{{ old('autore') }}">
        </div>

        <!-- Tag (nomi separati da virgola) -->
        <div class="mb-3">
            <label for="tags" class="form-label">Tag</label>
            <input type="text" name="tags" class="form-control" placeholder="Nomi tag separati da virgola"
                value="{{ is_array(old('tags')) ? implode(',', old('tags')) : old('tags') }}">
            <small class="form-text text-muted">Inserisci uno o più nomi tag separati da virgola.</small>
        </div>

        <!-- Pulsanti -->
        <button type="submit" class="btn btn-success">Salva Video</button>
        <a href="{{ route('videos.index') }}" class="btn btn-secondary">Annulla</a>
    </form>
</div>

<script>
    function extractYoutubeId(url) {
        const regex = /(?:youtube\.com\/(?:.*v=|.*\/(?:v|embed|shorts)\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/;
        const match = url.match(regex);
        return match ? match[1] : null;
    }

    document.getElementById('youtube_id').addEventListener('input', function () {
        const videoId = extractYoutubeId(this.value);
        const preview = document.getElementById('youtube-preview');
        const iframe = document.getElementById('youtube-iframe');

        if (videoId) {
            iframe.src = `https://www.youtube.com/embed/${videoId}`;
            preview.style.display = 'block';
        } else {
            iframe.src = '';
            preview.style.display = 'none';
        }
    });

    window.addEventListener('DOMContentLoaded', () => {
        const input = document.getElementById('youtube_id');
        const event = new Event('input');
        input.dispatchEvent(event);
    });
</script>
@endsection