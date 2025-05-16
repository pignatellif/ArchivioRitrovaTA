@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Crea Nuova Serie</h2>

    <form action="{{ route('series.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="nome" class="form-label">Nome della Serie</label>
            <input type="text" class="form-control" id="nome" name="nome" required>
        </div>

        <div class="mb-3">
            <label for="descrizione" class="form-label">Descrizione</label>
            <textarea class="form-control" id="descrizione" name="descrizione" rows="3" required></textarea>
        </div>

        <h4>Filtri per selezionare i video</h4>
        <div class="row mb-3">
            <div class="col-md-2">
                <label for="filter-title" class="form-label">Titolo</label>
                <input type="text" id="filter-title" class="form-control" placeholder="Cerca per titolo">
            </div>

            <div class="col-md-2">
                <label for="filter-year" class="form-label">Anno</label>
                <input type="text" id="filter-year" class="form-control" placeholder="Cerca per anno">
            </div>

            <div class="col-md-2">
                <label for="filter-format" class="form-label">Formato</label>
                <select id="filter-format" class="form-select">
                    <option value="">Tutti</option>
                    @foreach($formats as $format)
                        <option value="{{ $format }}">{{ $format }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label for="filter-author" class="form-label">Autore</label>
                <select id="filter-author" class="form-select">
                    <option value="">Tutti</option>
                    @foreach($authors as $id => $nome)
                        <option value="{{ $nome }}">{{ $nome }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label for="filter-location" class="form-label">Luogo</label>
                <select id="filter-location" class="form-select">
                    <option value="">Tutti</option>
                    @foreach($locations as $id => $nome)
                        <option value="{{ $nome }}">{{ $nome }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label for="filter-family" class="form-label">Famiglia</label>
                <select id="filter-family" class="form-select">
                    <option value="">Tutti</option>
                    @foreach($families as $family)
                        @if($family)
                            <option value="{{ $family }}">{{ $family }}</option>
                        @endif
                    @endforeach
                </select>
            </div>

        </div>

        <div class="row mb-3">
            <div class="col-md-3">
                <label class="form-label">Tag</label>
                <div id="filter-tag">
                    @foreach($tags as $id => $nome)
                        <div class="form-check">
                            <input class="form-check-input filter-tag" type="checkbox" value="{{ $nome }}" id="tag-{{ $id }}">
                            <label class="form-check-label" for="tag-{{ $id }}">
                                {{ $nome }}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="mb-3">
                <button type="button" class="btn btn-outline-secondary" id="reset-filters">Reset filtri</button>
            </div>
        </div>

        <!-- Lista video filtrabili -->
        <h4>Seleziona Video</h4>
        <div id="video-list">
            @foreach($videos as $video)
                <div class="form-check video-item"
                    data-year="{{ $video->anno }}"
                    data-format="{{ $video->formato }}"
                    data-author="{{ $video->autore?->nome }}"
                    data-location="{{ $video->location?->name }}"
                    data-family="{{ $video->famiglia }}"
                    data-tags="{{ $video->tags->pluck('nome')->implode(',') }}">
                    <input class="form-check-input" type="checkbox" name="videos[]" value="{{ $video->id }}" id="video-{{ $video->id }}">
                    <label class="form-check-label" for="video-{{ $video->id }}">
                        {{ $video->titolo }} ({{ $video->anno }}) - {{ $video->formato }} - {{ $video->autore?->nome }} - {{ $video->location?->name }}
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
        title: document.getElementById("filter-title"),
        year: document.getElementById("filter-year"),
        format: document.getElementById("filter-format"),
        author: document.getElementById("filter-author"),
        location: document.getElementById("filter-location"),
        family: document.getElementById("filter-family"),
    };

    const tagCheckboxes = document.querySelectorAll(".filter-tag");
    const videoItems = document.querySelectorAll(".video-item");

    function getSelectedTags() {
        return Array.from(tagCheckboxes)
            .filter(checkbox => checkbox.checked)
            .map(checkbox => checkbox.value.trim());
    }

    function filterVideos() {
        const selectedTags = getSelectedTags();
        const titleFilter = filters.title.value?.trim().toLowerCase();
        const yearFilter = filters.year.value?.trim();

        videoItems.forEach(video => {
            let matches = true;

            // Check title filter
            if (titleFilter && !video.textContent.toLowerCase().includes(titleFilter)) {
                matches = false;
            }

            // Check year filter
            if (matches && yearFilter && video.dataset.year !== yearFilter) {
                matches = false;
            }

            // Check other filters
            if (matches) {
                for (let key of ["format", "author", "location", "family"]) {
                    const filterValue = filters[key].value?.trim();
                    if (filterValue && video.dataset[key] !== filterValue) {
                        matches = false;
                        break;
                    }
                }
            }

            // Check tags
            if (matches && selectedTags.length > 0) {
                const videoTags = (video.dataset.tags || "").split(",");
                const hasMatchingTag = selectedTags.some(tag => videoTags.includes(tag));
                if (!hasMatchingTag) matches = false;
            }

            video.style.display = matches ? "block" : "none";
        });
    }

    // Add event listeners
    Object.values(filters).forEach(filter => filter.addEventListener("input", filterVideos));
    tagCheckboxes.forEach(cb => cb.addEventListener("change", filterVideos));

    document.getElementById("reset-filters").addEventListener("click", () => {
        for (let key in filters) filters[key].value = "";
        tagCheckboxes.forEach(cb => cb.checked = false);
        filterVideos();
    });
});
</script>

@endsection
