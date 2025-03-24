@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Crea Nuova Serie</h2>

    <form action="{{ route('series.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Nome della Serie</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Descrizione</label>
            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
        </div>

        <h4>Filtri per selezionare i video</h4>
        <div class="row mb-3">
            <div class="col-md-3">
                <label for="filter-year" class="form-label">Anno</label>
                <select id="filter-year" class="form-select">
                    <option value="">Tutti</option>
                    @foreach($years as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label for="filter-format" class="form-label">Formato</label>
                <select id="filter-format" class="form-select">
                    <option value="">Tutti</option>
                    @foreach($formats as $format)
                        <option value="{{ $format }}">{{ $format }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label for="filter-author" class="form-label">Autore</label>
                <select id="filter-author" class="form-select">
                    <option value="">Tutti</option>
                    @foreach($authors as $author)
                        <option value="{{ $author }}">{{ $author }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label for="filter-location" class="form-label">Luogo</label>
                <select id="filter-location" class="form-select">
                    <option value="">Tutti</option>
                    @foreach($locations as $location)
                        <option value="{{ $location }}">{{ $location }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Lista video filtrabili -->
        <h4>Seleziona Video</h4>
        <div id="video-list">
            @foreach($videos as $video)
                <div class="form-check video-item" data-year="{{ $video->year }}" data-format="{{ $video->format }}" data-author="{{ $video->author }}" data-location="{{ $video->location }}">
                    <input class="form-check-input" type="checkbox" name="videos[]" value="{{ $video->id }}" id="video-{{ $video->id }}">
                    <label class="form-check-label" for="video-{{ $video->id }}">
                        {{ $video->title }} ({{ $video->year }}) - {{ $video->format }} - {{ $video->author }} - {{ $video->location }}
                    </label>
                </div>
            @endforeach
        </div>

        <button type="submit" class="btn btn-success mt-3">Crea Serie</button>
        <a href="{{ route('series.index') }}" class="btn btn-secondary mt-3">Annulla</a>
    </form>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const filters = {
            year: document.getElementById("filter-year"),
            format: document.getElementById("filter-format"),
            author: document.getElementById("filter-author"),
            location: document.getElementById("filter-location"),
        };

        const videoItems = document.querySelectorAll(".video-item");

        function filterVideos() {
            videoItems.forEach(video => {
                let matches = true;

                for (let key in filters) {
                    const filterValue = filters[key].value;
                    const videoValue = video.dataset[key];

                    if (filterValue && videoValue !== filterValue) {
                        matches = false;
                        break;
                    }
                }

                video.style.display = matches ? "block" : "none";
            });
        }

        Object.values(filters).forEach(filter => filter.addEventListener("change", filterVideos));
    });
</script>
@endsection
