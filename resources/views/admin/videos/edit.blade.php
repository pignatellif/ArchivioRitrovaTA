@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Modifica Video</h2>

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

        <div class="mb-3">
            <label for="titolo" class="form-label">Titolo</label>
            <input type="text" name="titolo" id="titolo" class="form-control" 
                   value="{{ old('titolo', $video->titolo) }}" required>
        </div>

        <div class="mb-3">
            <label for="descrizione" class="form-label">Descrizione</label>
            <textarea name="descrizione" id="descrizione" class="form-control" rows="4">{{ old('descrizione', $video->descrizione) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="anno" class="form-label">Anno</label>
            <input type="number" name="anno" id="anno" class="form-control" 
                   value="{{ old('anno', $video->anno) }}">
        </div>

        <div class="mb-3">
            <label for="durata_secondi" class="form-label">Durata (secondi)</label>
            <input type="number" name="durata_secondi" id="durata_secondi" class="form-control" 
                   value="{{ old('durata_secondi', $video->durata_secondi) }}" required>
        </div>

        <div class="mb-3">
            <label for="formato" class="form-label">Formato</label>
            <input type="text" name="formato" id="formato" class="form-control" 
                   value="{{ old('formato', $video->formato) }}">
        </div>

        <div class="mb-3">
            <label for="famiglia" class="form-label">Famiglia</label>
            <input type="text" name="famiglia" id="famiglia" class="form-control" 
                   value="{{ old('famiglia', $video->famiglia) }}">
        </div>

        <div class="mb-3">
            <label for="luogo" class="form-label">Luogo</label>
            <input type="text" name="luogo" id="luogo" class="form-control" 
                   value="{{ old('luogo', $video->location->name ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label for="tags" class="form-label">Tag (separati da virgole)</label>
            <input type="text" name="tags" id="tags" class="form-control" 
                   value="{{ old('tags', $video->tags->pluck('nome')->implode(', ')) }}">
            <small class="form-text text-muted">Inserisci i tag separati da virgole (es. "tag1, tag2, tag3").</small>
        </div>

        <div class="mb-3">
            <label for="link_youtube" class="form-label">Link YouTube</label>
            <input type="url" name="link_youtube" id="link_youtube" class="form-control" 
                   value="{{ old('link_youtube', $video->link_youtube) }}" required>
        </div>

        <div class="mb-3">
            <label for="autore_nome" class="form-label">Autore</label>
            <input type="text" name="autore_nome" id="autore_nome" class="form-control" 
                   value="{{ old('autore_nome', $video->autore->nome ?? '') }}" required>
            <small class="form-text text-muted">Inserisci il nome dell'autore. Se non esiste, verrà creato automaticamente.</small>
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('videos.index') }}" class="btn btn-secondary">⬅ Torna alla lista</a>
            <button type="submit" class="btn btn-success">💾 Salva Modifiche</button>
        </div>
    </form>
</div>
@endsection
