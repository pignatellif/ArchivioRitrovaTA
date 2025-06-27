@extends('layouts.admin')

@section('content')
<div class="container mt-4">

    {{-- Bottone di ritorno --}}
    <div class="mb-3">
        <a href="{{ route('videos.index') }}" class="btn btn-outline-secondary" id="back-to-list-btn">
            <i class="fas fa-arrow-left me-2"></i>Torna all'elenco Video
        </a>
    </div>

    <h2 class="mb-4"><i class="fas fa-film me-2 text-primary"></i>Modifica Video</h2>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('videos.update', $video->id) }}" method="POST" id="edit-video-form" autocomplete="off">
                @csrf
                @method('PUT')

                {{-- =======================
                     METADATI VIDEO
                ======================== --}}
                <h4 class="mt-3"><i class="fas fa-info-circle me-2 text-primary"></i>Metadati Video</h4>

                <div class="mb-3">
                    <label for="titolo" class="form-label fw-bold">Titolo <span class="text-danger">*</span></label>
                    <input type="text" name="titolo" class="form-control @error('titolo') is-invalid @enderror"
                        value="{{ old('titolo', $video->titolo) }}" required maxlength="255" title="Campo obbligatorio">
                    @error('titolo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="descrizione" class="form-label fw-bold">Descrizione</label>
                    <textarea name="descrizione" id="descrizione" class="form-control @error('descrizione') is-invalid @enderror"
                        rows="4" maxlength="1000" title="Inserisci una descrizione (max 1000 caratteri)">{{ old('descrizione', $video->descrizione) }}</textarea>
                    @error('descrizione')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">Massimo 1000 caratteri</div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="anno" class="form-label fw-bold">Anno</label>
                        <input type="number" name="anno" class="form-control @error('anno') is-invalid @enderror"
                            value="{{ old('anno', $video->anno) }}" title="Anno del video">
                        @error('anno')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="durata_secondi" class="form-label fw-bold">Durata (secondi) <span class="text-danger">*</span></label>
                        <input type="number" name="durata_secondi" id="durata_secondi"
                            class="form-control @error('durata_secondi') is-invalid @enderror"
                            value="{{ old('durata_secondi', $video->durata_secondi) }}" required min="1" title="Campo obbligatorio">
                        @error('durata_secondi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text" id="duration-display"></div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="formato" class="form-label fw-bold">Formato</label>
                        <input type="text" name="formato" class="form-control @error('formato') is-invalid @enderror"
                            placeholder="es. MP4, MOV, AVI" value="{{ old('formato', $video->formato->nome ?? '') }}">
                        @error('formato')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- =======================
                     LOCALIZZAZIONE
                ======================== --}}
                <h4 class="mt-4"><i class="fas fa-map-marker-alt me-2 text-primary"></i>Localizzazione</h4>

                <div class="mb-3">
                    <label for="location" class="form-label fw-bold">Luogo <span class="text-danger">*</span></label>
                    <input type="text" name="location" id="location-input" class="form-control @error('location') is-invalid @enderror"
                        placeholder="Digita una città o località (es: Roma)" value="{{ old('location', optional($video->location)->name) }}"
                        autocomplete="off" required title="Campo obbligatorio">
                    <input type="hidden" name="location_lat" id="location-lat" value="{{ old('location_lat', optional($video->location)->lat) }}">
                    <input type="hidden" name="location_lon" id="location-lon" value="{{ old('location_lon', optional($video->location)->lon) }}">
                    @error('location')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div id="map-container" class="mb-3" style="position: relative; min-height: 400px;">
                    <div id="map" style="height: 400px; border-radius: 8px;"></div>
                    <div id="location-list-card" class="card shadow-sm"
                        style="position: absolute; top: 12px; right: 12px; min-width: 320px; max-width: 380px; z-index: 1001; display: none;">
                        <div class="card-header py-2 px-3">
                            <strong><i class="fas fa-search me-2"></i>Risultati ricerca luogo</strong>
                        </div>
                        <ul class="list-group list-group-flush" id="location-list"></ul>
                    </div>
                </div>

                <div id="location-preview" class="mt-3" style="display: none;">
                    <div class="card">
                        <div class="card-body p-3">
                            <h6 class="card-title mb-2 text-success">
                                <i class="fas fa-check-circle me-2"></i>Location Selezionata
                            </h6>
                            <p class="mb-1"><strong id="preview-name"></strong></p>
                            <p class="mb-1 text-muted" id="preview-address"></p>
                            <small class="text-muted">
                                Tipo: <span id="preview-type"></span> |
                                Coordinate: <span id="preview-coordinates"></span>
                            </small>
                            <br>
                            <button type="button" class="btn btn-sm btn-outline-secondary mt-2" onclick="clearLocationSelection()">
                                <i class="fas fa-times"></i> Cambia Location
                            </button>
                        </div>
                    </div>
                </div>

                {{-- =======================
                     MULTIMEDIA
                ======================== --}}
                <h4 class="mt-4"><i class="fab fa-youtube me-2 text-danger"></i>Contenuti Multimediali</h4>

                <div class="mb-3">
                    <label for="youtube_id" class="form-label fw-bold">
                        <i class="fab fa-youtube me-2 text-danger"></i>Link o ID YouTube
                    </label>
                    <input type="text" id="youtube_id" name="youtube_id"
                        class="form-control @error('youtube_id') is-invalid @enderror"
                        value="{{ old('youtube_id', $video->youtube_id) }}">
                    @error('youtube_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3" id="youtube-preview" style="display: none;">
                    <label class="form-label">Anteprima Video</label>
                    <div class="ratio ratio-16x9">
                        <iframe id="youtube-iframe" src="" frameborder="0" allowfullscreen></iframe>
                    </div>
                </div>

                {{-- =======================
                     AUTORI & TAG
                ======================== --}}
                <h4 class="mt-4"><i class="fas fa-user-edit me-2 text-primary"></i>Autori e Tag</h4>

                <div class="mb-3">
                    <label for="autore" class="form-label fw-bold">Autore</label>
                    <input type="text" name="autore" class="form-control @error('autore') is-invalid @enderror"
                        placeholder="Nome autore" value="{{ old('autore', optional($video->autore)->nome) }}" maxlength="100">
                    @error('autore')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="famiglie" class="form-label fw-bold">Famiglie</label>
                    <input type="text" name="famiglie" id="famiglie" class="form-control @error('famiglie') is-invalid @enderror"
                        placeholder="Nomi famiglie separati da virgola"
                        value="{{ old('famiglie', $video->famiglie->pluck('nome')->implode(', ')) }}">
                    <small class="form-text text-muted">Inserisci uno o più nomi famiglia separati da virgola.</small>
                    @error('famiglie')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="tags" class="form-label fw-bold">Tag</label>
                    <input type="text" name="tags" id="tags" class="form-control @error('tags') is-invalid @enderror"
                        placeholder="Nomi tag separati da virgola"
                        value="{{ old('tags', $video->tags->pluck('nome')->implode(', ')) }}">
                    <small class="form-text text-muted">Inserisci uno o più tag separati da virgola.</small>
                    @error('tags')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- =======================
                     PULSANTI AZIONE
                ======================== --}}
                <div class="d-flex justify-content-end gap-3 mt-4">
                    <button type="submit" class="btn btn-success"><i class="fas fa-save me-1"></i>Salva Modifiche</button>
                    <a href="{{ route('videos.index') }}" class="btn btn-secondary" id="annulla-btn"><i class="fas fa-times me-1"></i>Annulla</a>
                </div>
            </form>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<style>
    #map { width: 100%; }
    #location-list-card {
        max-height: 350px;
        overflow-y: auto;
    }
    .location-list-item {
        cursor: pointer;
        padding: 12px 14px;
        border-bottom: 1px solid #f1f1f1;
        transition: background 0.18s;
    }
    .location-list-item:hover, .location-list-item.active {
        background: #e9ecef;
    }
    @media (max-width: 768px) {
        #location-list-card {
            width: 100%;
            max-width: none;
            right: 0;
            left: 0;
            top: auto;
            bottom: 0;
            border-radius: 0;
        }
    }
</style>

<script>
    let map = null;
    let markers = [];
    let lastResults = [];
    let selectedLocation = null;

    const locationInput = document.getElementById('location-input');
    const latInput = document.getElementById('location-lat');
    const lonInput = document.getElementById('location-lon');
    const locationListCard = document.getElementById('location-list-card');
    const locationList = document.getElementById('location-list');
    const previewContainer = document.getElementById('location-preview');
    const durationInput = document.getElementById('durata_secondi');
    const durationDisplay = document.getElementById('duration-display');
    let formChanged = false;

    // ========== MAPPA ==========
    function highlightIcon() {
        return L.icon({
            iconUrl: "https://cdn.jsdelivr.net/gh/pointhi/leaflet-color-markers@master/img/marker-icon-red.png",
            shadowUrl: "https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png",
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });
    }
    function defaultIcon() {
        return L.icon({
            iconUrl: "https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png",
            shadowUrl: "https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png",
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });
    }

    function initMap() {
        const lat = parseFloat(latInput.value) || 41.9028;
        const lon = parseFloat(lonInput.value) || 12.4964;
        const zoom = (latInput.value && lonInput.value) ? 12 : 5;
        map = L.map('map').setView([lat, lon], zoom);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(map);

        if (latInput.value && lonInput.value) {
            const marker = L.marker([lat, lon], {icon: highlightIcon()}).addTo(map);
            markers.push(marker);
            if (locationInput.value) {
                updateLocationPreview({
                    name: locationInput.value,
                    display_name: locationInput.value,
                    type: '',
                    lat: latInput.value,
                    lon: lonInput.value,
                    country: ''
                });
                previewContainer.style.display = 'block';
            }
        }
    }
    initMap();

    function clearMarkers() {
        markers.forEach(m => map.removeLayer(m));
        markers = [];
    }

    function showMarkers(results) {
        clearMarkers();
        results.forEach((loc, idx) => {
            const marker = L.marker([loc.lat, loc.lon], {
                title: loc.name,
                icon: idx === 0 ? highlightIcon() : defaultIcon()
            }).addTo(map);
            marker.on('click', () => selectLocation(loc, true));
            markers.push(marker);
        });
        if (markers.length) {
            const group = new L.featureGroup(markers);
            map.fitBounds(group.getBounds().pad(0.4));
        }
    }

    function updateMarkersHighlight(selectedLoc) {
        markers.forEach((marker) => {
            if (selectedLoc && marker.getLatLng().lat == selectedLoc.lat && marker.getLatLng().lng == selectedLoc.lon) {
                marker.setIcon(highlightIcon());
            } else {
                marker.setIcon(defaultIcon());
            }
        });
    }

    // ========== AUTOCOMPLETE LOGIC ==========
    let searchTimeout;
    locationInput.addEventListener('input', function() {
        const query = this.value.trim();
        clearTimeout(searchTimeout);
        unselectLocation();
        if (query.length < 2) {
            closeLocationList();
            return;
        }
        searchTimeout = setTimeout(() => {
            searchLocations(query);
        }, 350);
    });

    async function searchLocations(query) {
        closeLocationList();
        try {
            const response = await fetch(`{{ route('videos.search-locations') }}?query=${encodeURIComponent(query)}`);
            const data = await response.json();
            if (data.success && data.results.length) {
                lastResults = data.results;
                showLocationList(data.results);
                showMarkers(data.results);
            } else {
                lastResults = [];
                showLocationList([]);
                clearMarkers();
            }
        } catch (e) {
            showLocationList([]);
            clearMarkers();
        }
    }

    function showLocationList(results) {
        locationList.innerHTML = '';
        if (!results.length) {
            locationList.innerHTML = '<li class="list-group-item text-muted"><i class="fas fa-search me-2"></i>Nessun risultato</li>';
            locationListCard.style.display = 'block';
            return;
        }
        results.forEach((loc, idx) => {
            const li = document.createElement('li');
            li.className = 'location-list-item list-group-item';
            li.innerHTML = `
                <div><strong>${escapeHtml(loc.name)}</strong></div>
                <div style="font-size: 0.95em; color:#666;">${escapeHtml(loc.display_name)}</div>
                <small class="text-muted"><i class="fas fa-map-pin me-1"></i>${translateType(loc.type)} &middot; ${escapeHtml(loc.country)}</small>
            `;
            li.onclick = () => selectLocation(loc, false, idx);
            locationList.appendChild(li);
        });
        locationListCard.style.display = 'block';
    }

    function closeLocationList() {
        locationListCard.style.display = 'none';
    }

    function selectLocation(loc, fromMap = false, idx=null) {
        selectedLocation = loc;
        locationInput.value = loc.name;
        latInput.value = loc.lat;
        lonInput.value = loc.lon;
        updateLocationPreview(loc);
        closeLocationList();
        updateMarkersHighlight(loc);

        Array.from(locationList.children).forEach((li, i) => {
            if ((idx !== null && i === idx) || (fromMap && loc.place_id == lastResults[i]?.place_id)) {
                li.classList.add('active');
            } else {
                li.classList.remove('active');
            }
        });
    }

    function updateLocationPreview(loc) {
        document.getElementById('preview-name').textContent = loc.name;
        document.getElementById('preview-address').textContent = loc.display_name;
        document.getElementById('preview-type').textContent = translateType(loc.type);
        document.getElementById('preview-coordinates').textContent = `${parseFloat(loc.lat).toFixed(4)}, ${parseFloat(loc.lon).toFixed(4)}`;
        previewContainer.style.display = 'block';
    }

    function unselectLocation() {
        selectedLocation = null;
        latInput.value = '';
        lonInput.value = '';
        previewContainer.style.display = 'none';
        updateMarkersHighlight(null);
        Array.from(locationList.children).forEach(li => li.classList.remove('active'));
    }

    function clearLocationSelection() {
        locationInput.value = '';
        unselectLocation();
        closeLocationList();
        clearMarkers();
        lastResults = [];
    }

    function translateType(type) {
        const translations = {
            'city': 'Città',
            'town': 'Cittadina',
            'village': 'Paese',
            'hamlet': 'Frazione',
            'country': 'Nazione',
            'state': 'Stato/Regione',
            'county': 'Provincia',
            'municipality': 'Comune',
            'administrative': 'Area Amministrativa'
        };
        return translations[type] || (type ? type.charAt(0).toUpperCase() + type.slice(1) : 'Sconosciuto');
    }

    // ========== UTIL ==========
    function escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text ? text.replace(/[&<>"']/g, m => map[m]) : '';
    }

    document.addEventListener('click', function(e) {
        if (!e.target.closest('#location-input') && !e.target.closest('#location-list-card')) {
            closeLocationList();
        }
    });

    // ========== YOUTUBE PREVIEW ==========
    function extractYoutubeId(urlOrId) {
        if (!urlOrId) return null;
        if (/^[a-zA-Z0-9_-]{11}$/.test(urlOrId.trim())) return urlOrId.trim();
        const patterns = [
            /(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/|youtube\.com\/shorts\/)([a-zA-Z0-9_-]{11})/,
            /youtube\.com\/watch\?.*v=([a-zA-Z0-9_-]{11})/,
            /m\.youtube\.com\/watch\?.*v=([a-zA-Z0-9_-]{11})/
        ];
        for (const pattern of patterns) {
            const match = urlOrId.match(pattern);
            if (match && match[1]) {
                return match[1];
            }
        }
        return null;
    }
    function updateYoutubePreview() {
        const input = document.getElementById('youtube_id');
        const preview = document.getElementById('youtube-preview');
        const iframe = document.getElementById('youtube-iframe');
        const val = input.value.trim();
        const videoId = extractYoutubeId(val);
        if (videoId) {
            iframe.src = `https://www.youtube.com/embed/${videoId}?rel=0&modestbranding=1`;
            preview.style.display = 'block';
            input.classList.remove('is-invalid');
            input.classList.add('is-valid');
        } else {
            iframe.src = '';
            preview.style.display = 'none';
            if (!val) {
                input.classList.remove('is-valid', 'is-invalid');
            }
        }
    }
    document.getElementById('youtube_id').addEventListener('input', function() {
        clearTimeout(this.youtubeTimeout);
        this.youtubeTimeout = setTimeout(updateYoutubePreview, 500);
    });

    // ========== DURATA UMANA ==========
    function updateDurationDisplay() {
        const seconds = parseInt(durationInput.value) || 0;
        if (seconds > 0) {
            const hours = Math.floor(seconds / 3600);
            const minutes = Math.floor((seconds % 3600) / 60);
            const remainingSeconds = seconds % 60;
            let display = '';
            if (hours > 0) display += `${hours}h `;
            if (minutes > 0) display += `${minutes}m `;
            if (remainingSeconds > 0 || seconds < 60) display += `${remainingSeconds}s`;
            durationDisplay.textContent = `≈ ${display.trim()}`;
            durationDisplay.className = 'form-text text-success';
        } else {
            durationDisplay.textContent = '';
        }
    }
    if (durationInput) {
        durationInput.addEventListener('input', updateDurationDisplay);
        updateDurationDisplay();
    }

    // ========== AUTO-RESIZE TEXTAREA ==========
    const textarea = document.getElementById('descrizione');
    if (textarea) {
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
        textarea.style.height = 'auto';
        textarea.style.height = (textarea.scrollHeight) + 'px';
    }

    // ========== FORMATTAZIONE TAG E FAMIGLIE ==========
    function formatCommaSeparatedInput(input) {
        input.addEventListener('blur', function() {
            const value = this.value.trim();
            if (value) {
                // Rimuovi spazi extra e virgole duplicate
                const formatted = value
                    .split(',')
                    .map(item => item.trim())
                    .filter(item => item.length > 0)
                    .join(', ');
                this.value = formatted;
            }
        });
    }
    formatCommaSeparatedInput(document.getElementById('famiglie'));
    formatCommaSeparatedInput(document.getElementById('tags'));

    // ========== MODIFICHE NON SALVATE ==========
    const form = document.getElementById('edit-video-form');
    const formInputs = form.querySelectorAll('input, textarea, select');
    formInputs.forEach(input => {
        const initialValue = input.value;
        input.addEventListener('input', function() {
            if (this.value !== initialValue) {
                formChanged = true;
            }
        });
    });

    // Intercetta annulla/back
    function handleAbandon(e) {
        if (formChanged) {
            const confirmMsg = "Ci sono modifiche non salvate. Se prosegui, le modifiche verranno perse. Vuoi continuare?";
            if (!confirm(confirmMsg)) {
                e.preventDefault();
                return false;
            }
        }
        return true;
    }
    document.getElementById('annulla-btn').addEventListener('click', handleAbandon);
    document.getElementById('back-to-list-btn').addEventListener('click', handleAbandon);

    window.addEventListener('beforeunload', function(e) {
        if (formChanged) {
            e.preventDefault();
            e.returnValue = '';
        }
    });
    form.addEventListener('submit', function() {
        formChanged = false;
    });
</script>
@endsection