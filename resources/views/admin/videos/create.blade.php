@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2>Aggiungi un Nuovo Video</h2>

    <!-- Messaggi di errore -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form per creare un video -->
    <form action="{{ route('videos.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="title" class="form-label">Titolo</label>
            <input type="text" name="title" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Descrizione</label>
            <textarea name="description" class="form-control" rows="4" required></textarea>
        </div>

        <div class="mb-3">
            <label for="year" class="form-label">Anno</label>
            <input type="number" name="year" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="duration" class="form-label">Durata (secondi)</label>
            <input type="number" name="duration" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="format" class="form-label">Formato</label>
            <input type="text" name="format" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="family" class="form-label">Famiglia</label>
            <input type="text" name="family" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="location" class="form-label">Luogo</label>
            <input type="text" name="location" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="tags" class="form-label">Tag (separati da virgole)</label>
            <input type="text" name="tags" class="form-control">
        </div>

        <div class="mb-3">
            <label for="yt_link" class="form-label">Link YouTube</label>
            <input type="url" name="yt_link" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="author" class="form-label">Autore</label>
            <input type="text" name="author" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">Salva Video</button>
        <a href="{{ route('videos.index') }}" class="btn btn-secondary">Annulla</a>
    </form>
</div>
@endsection
