@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Modifica Serie</h2>

    <form action="{{ route('series.update', $series->id) }}" method="POST" class="card p-4 shadow">
        @csrf
        @method('PUT')

        <!-- Nome -->
        <div class="mb-3">
            <label for="name" class="form-label">Nome della serie:</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $series->name) }}" required>
        </div>

        <!-- Descrizione -->
        <div class="mb-3">
            <label for="description" class="form-label">Descrizione:</label>
            <textarea id="description" name="description" class="form-control">{{ old('description', $series->description) }}</textarea>
        </div>

        <!-- Input nascosto per i video -->
        <input type="hidden" id="selectedVideosInput" name="selected_videos" value="{{ $series->videos->pluck('id')->join(',') }}">

        <!-- Lista video nella serie -->
        <div class="mb-3">
            <h3>Video nella serie</h3>
            <ul id="seriesVideos" class="list-group">
                @foreach($series->videos as $video)
                    <li class="list-group-item d-flex justify-content-between align-items-center" data-id="{{ $video->id }}">
                        {{ $video->title }}
                        <button type="button" class="btn btn-danger remove-video" data-id="{{ $video->id }}">Rimuovi</button>
                    </li>
                @endforeach
            </ul>
        </div>

        <!-- Lista video disponibili -->
        <div class="mb-3">
            <h3>Video disponibili</h3>
            <ul id="availableVideos" class="list-group">
                @foreach($availableVideos as $video)
                    <li class="list-group-item d-flex justify-content-between align-items-center" data-id="{{ $video->id }}">
                        {{ $video->title }}
                        <button type="button" class="btn btn-success add-video" data-id="{{ $video->id }}">Aggiungi</button>
                    </li>
                @endforeach
            </ul>
        </div>

        <!-- Pulsanti -->
        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-primary">Salva Modifiche</button>
            <a href="{{ route('series.index') }}" class="btn btn-secondary">Annulla</a>
        </div>
    </form>
</div>

<script src="{{ asset('js/edit-series.js') }}"></script>
@endsection
