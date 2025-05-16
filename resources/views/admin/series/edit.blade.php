@extends('layouts.admin')

@section('content')
<div class="container-fluid mt-4">
    <h2 class="mb-4 text-center">Modifica Serie</h2>

    <div class="row gx-4">
        {{-- Colonna sinistra: Form modifica serie e video --}}
        <div class="col-12 col-lg-8 mb-4">
            <form action="{{ route('series.update', $series->id) }}" method="POST" class="card p-4 shadow-sm h-100">
                @csrf
                @method('PUT')

                {{-- Nome --}}
                <div class="mb-4">
                    <label for="name" class="form-label fw-bold">Nome della serie</label>
                    <input type="text" id="name" name="name" class="form-control" value="{{ old('nome', $series->nome) }}" required>
                </div>

                {{-- Descrizione --}}
                <div class="mb-4">
                    <label for="description" class="form-label fw-bold">Descrizione</label>
                    <textarea id="description" name="description" class="form-control" rows="5" style="resize: vertical;">{{ old('descrizione', $series->descrizione) }}</textarea>
                </div>

                {{-- Video nella serie e disponibili --}}
                <div class="row">
                    {{-- Video nella serie --}}
                    <div class="col-md-6 mb-3">
                        <h5 class="fw-bold mb-2">Video nella serie</h5>
                        <div class="card shadow-sm" style="height: 600px; overflow-y: auto;">
                            <ul id="seriesVideos" class="list-group list-group-flush">
                                @foreach($series->videos as $video)
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
                                        <button type="button" class="btn btn-danger btn-sm remove-video mt-2" data-id="{{ $video->id }}">Rimuovi</button>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    {{-- Video disponibili --}}
                    <div class="col-md-6 mb-3">
                        <h5 class="fw-bold mb-2">Video disponibili</h5>
                        <div class="card shadow-sm" style="height: 600px; overflow-y: auto;">
                            <ul id="availableVideos" class="list-group list-group-flush">
                                @foreach($availableVideos as $video)
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
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Hidden input --}}
                <input type="hidden" id="selectedVideosInput" name="selected_videos" value="{{ $series->videos->pluck('id')->join(',') }}">

                {{-- Pulsanti --}}
                <div class="d-flex justify-content-between mt-4">
                    <button type="submit" class="btn btn-primary">Salva Modifiche</button>
                    <a href="{{ route('series.index') }}" class="btn btn-outline-secondary">Annulla</a>
                </div>
            </form>
        </div>

        {{-- Colonna destra: Filtri --}}
        <div class="col-12 col-lg-4 mb-4">
            <form method="GET" action="{{ route('series.edit', $series->id) }}" class="card p-4 shadow-sm h-100">
                <h4 class="mb-3 fw-bold">Filtra i video</h4>

                <div class="mb-3">
                    <label for="title" class="form-label">Titolo</label>
                    <input type="text" name="title" id="title" class="form-control" value="{{ request('title') }}">
                </div>

                <div class="mb-3">
                    <label for="year" class="form-label">Anno</label>
                    <input type="text" name="year" id="year" class="form-control" value="{{ request('year') }}">
                </div>

                <div class="mb-3">
                    <label for="format" class="form-label">Formato</label>
                    <select name="format" id="format" class="form-select">
                        <option value="">Tutti</option>
                        @foreach($formats as $format)
                            <option value="{{ $format }}" {{ request('format') == $format ? 'selected' : '' }}>{{ $format }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="author" class="form-label">Autore</label>
                    <select name="author" id="author" class="form-select">
                        <option value="">Tutti</option>
                        @foreach ($authors as $id => $nome)
                            <option value="{{ $id }}" {{ request('author') == $id ? 'selected' : '' }}>{{ $nome }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="location" class="form-label">Luogo</label>
                    <select name="location" id="location" class="form-select">
                        <option value="">Tutti</option>
                        @foreach ($locations as $id => $name)
                            <option value="{{ $id }}" {{ request('location') == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="family" class="form-label">Famiglia</label>
                    <select name="family" id="family" class="form-select">
                        <option value="">Tutti</option>
                        @foreach($families as $family)
                            <option value="{{ $family }}" {{ request('family') == $family ? 'selected' : '' }}>{{ $family }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tag</label>
                    <div id="filter-tag" class="border rounded p-2" style="max-height: 250px; overflow-y: auto;">
                        @foreach($tags as $id => $nome)
                            <div class="form-check">
                                <input class="form-check-input filter-tag" type="checkbox" value="{{ $id }}" id="tag-{{ $id }}"
                                    {{ collect(explode(',', request('tags')))->contains($id) ? 'checked' : '' }}>
                                <label class="form-check-label" for="tag-{{ $id }}">{{ $nome }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <input type="hidden" name="tags" id="tags-hidden-input" value="{{ request('tags') }}">

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">Applica Filtri</button>
                    <a href="{{ route('series.edit', $series->id) }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="{{ asset('js/edit-series.js') }}"></script>

@endsection
