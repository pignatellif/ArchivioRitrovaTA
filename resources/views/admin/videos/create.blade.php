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

        <!-- Formato -->
        <div class="mb-3">
            <label for="formato" class="form-label">Formato</label>
            <input type="text" name="formato" class="form-control" value="{{ old('formato') }}">
        </div>

        <!-- Famiglia -->
        <div class="mb-3">
            <label for="famiglia" class="form-label">Famiglia</label>
            <input type="text" name="famiglia" class="form-control" value="{{ old('famiglia') }}">
        </div>

        <!-- Luogo -->
        <div class="mb-3">
            <label for="luogo" class="form-label">Luogo</label>
            <input type="text" name="luogo" class="form-control" value="{{ old('luogo') }}" required>
        </div>

        <!-- YouTube -->
        <div class="mb-3">
            <label for="link_youtube" class="form-label">Link YouTube</label>
            <input type="url" name="link_youtube" class="form-control" value="{{ old('link_youtube') }}" required>
        </div>

        <!-- Autore -->
        <div class="mb-3">
            <label for="autore_nome" class="form-label">Autore</label>
            <input type="text" name="autore_nome" id="autore_nome" class="form-control" required>
        </div>

        <!-- Tag -->
        <div class="mb-3">
            <label for="tags" class="form-label">Tag</label>
            <input type="text" name="tags" class="form-control" value="{{ old('tags') }}">
            <small class="form-text text-muted">Inserisci i tag separati da virgole.</small>
        </div>

        <!-- Pulsanti -->
        <button type="submit" class="btn btn-success">Salva Video</button>
        <a href="{{ route('videos.index') }}" class="btn btn-secondary">Annulla</a>
    </form>
</div>
@endsection
