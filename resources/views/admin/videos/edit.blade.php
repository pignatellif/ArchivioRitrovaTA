@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Modifica Video</h2>

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

    <!-- Form per modificare il video -->
    <form action="{{ route('videos.update', $video->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="title" class="form-label">Titolo</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $video->title) }}" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Descrizione</label>
            <textarea name="description" id="description" class="form-control" rows="4" required>{{ old('description', $video->description) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="year" class="form-label">Anno</label>
            <input type="number" name="year" id="year" class="form-control" value="{{ old('year', $video->year) }}" required>
        </div>

        <div class="mb-3">
            <label for="duration" class="form-label">Durata (secondi)</label>
            <input type="number" name="duration" id="duration" class="form-control" value="{{ old('duration', $video->duration) }}" required>
        </div>

        <div class="mb-3">
            <label for="format" class="form-label">Formato</label>
            <input type="text" name="format" id="format" class="form-control" value="{{ old('format', $video->format) }}" required>
        </div>

        <div class="mb-3">
            <label for="family" class="form-label">Famiglia</label>
            <input type="text" name="family" id="family" class="form-control" value="{{ old('family', $video->family) }}" required>
        </div>

        <div class="mb-3">
            <label for="location" class="form-label">Luogo</label>
            <input type="text" name="location" id="location" class="form-control" value="{{ old('location', $video->location) }}" required>
        </div>

        <div class="mb-3">
            <label for="tags" class="form-label">Tag (separati da virgole)</label>
            <input type="text" name="tags" id="tags" class="form-control" value="{{ old('tags', $video->tags) }}">
        </div>

        <div class="mb-3">
            <label for="yt_link" class="form-label">Link YouTube</label>
            <input type="url" name="yt_link" id="yt_link" class="form-control" value="{{ old('yt_link', $video->yt_link) }}" required>
        </div>

        <div class="mb-3">
            <label for="author" class="form-label">Autore</label>
            <input type="text" name="author" id="author" class="form-control" value="{{ old('author', $video->author) }}" required>
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('videos.index') }}" class="btn btn-secondary">⬅ Torna alla lista</a>
            <button type="submit" class="btn btn-success">💾 Salva Modifiche</button>
        </div>
    </form>
</div>
@endsection
