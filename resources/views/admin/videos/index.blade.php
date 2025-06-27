@extends('layouts.admin')

@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <!-- Header con titolo e statistiche -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-1"><i class="fas fa-video me-2"></i>Gestione Video</h2>
                    <p class="text-muted mb-0">Totale video: {{ $videos->count() }}</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Dashboard
                    </a>
                    <a href="{{ route('videos.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Nuovo Video
                    </a>
                </div>
            </div>

            <!-- Messaggi di successo -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Filtri e ricerca -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Ricerca</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" class="form-control" id="searchInput" placeholder="Cerca per titolo, autore...">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Anno</label>
                            <select class="form-select" id="filterAnno">
                                <option value="">Tutti</option>
                                @foreach($videos->pluck('anno')->unique()->sort() as $anno)
                                    <option value="{{ $anno }}">{{ $anno }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Formato</label>
                            <select class="form-select" id="filterFormato">
                                <option value="">Tutti</option>
                                @foreach($videos->pluck('formato.nome')->unique()->filter() as $formato)
                                    <option value="{{ $formato }}">{{ $formato }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Location</label>
                            <select class="form-select" id="filterLocation">
                                <option value="">Tutte</option>
                                @foreach($videos->pluck('location.name')->unique()->filter() as $location)
                                    <option value="{{ $location }}">{{ $location }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Azioni</label>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="clearFilters">
                                    <i class="fas fa-times"></i> Resetta filtri
                                </button>
                                <!-- Bottone esporta video rimosso -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabella video -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-list me-2"></i>Elenco Video</h5>
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-outline-secondary active" id="viewTable">
                            <i class="fas fa-table"></i>
                        </button>
                        <button type="button" class="btn btn-outline-secondary" id="viewGrid">
                            <i class="fas fa-th"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <!-- Vista Tabella -->
                    <div id="tableView" class="table-responsive">
                        <table class="table table-hover mb-0" id="videosTable">
                            <thead class="table-dark sticky-top">
                                <tr>
                                    <th>
                                        <input type="checkbox" class="form-check-input" id="selectAll">
                                    </th>
                                    <th class="sortable" data-sort="titolo">
                                        Titolo <i class="fas fa-sort ms-1"></i>
                                    </th>
                                    <th class="sortable" data-sort="anno">
                                        Anno <i class="fas fa-sort ms-1"></i>
                                    </th>
                                    <th class="sortable" data-sort="durata">
                                        Durata <i class="fas fa-sort ms-1"></i>
                                    </th>
                                    <th>Autore</th>
                                    <th>Formato</th>
                                    <th>Location</th>
                                    <th>Serie</th>
                                    <th>Famiglie</th>
                                    <th>Tag</th>
                                    <th class="text-center">Azioni</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($videos as $video)
                                <tr data-video-id="{{ $video->id }}">
                                    <td>
                                        <input type="checkbox" class="form-check-input row-select" value="{{ $video->id }}">
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <div class="video-thumbnail bg-light rounded d-flex align-items-center justify-content-center" style="width: 50px; height: 35px; overflow: hidden;">
                                                    @if($video->thumbnail_url)
                                                        <img src="{{ $video->thumbnail_url }}" alt="Thumbnail" style="width: 100%; height: 100%; object-fit: cover;">
                                                    @else
                                                        <i class="fas fa-play text-muted"></i>
                                                    @endif
                                                </div>
                                            </div>
                                            <div>
                                                <strong>{{ $video->titolo }}</strong>
                                                @if($video->descrizione)
                                                    <br><small class="text-muted">{{ Str::limit($video->descrizione, 60) }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $video->anno }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $minutes = intdiv($video->durata_secondi, 60);
                                            $seconds = $video->durata_secondi % 60;
                                        @endphp
                                        <span class="text-nowrap">
                                            <i class="fas fa-clock text-muted me-1"></i>
                                            {{ $minutes }}:{{ str_pad($seconds, 2, '0', STR_PAD_LEFT) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($video->autore)
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 30px; height: 30px;">
                                                    <span class="text-white small">{{ substr($video->autore->nome, 0, 1) }}</span>
                                                </div>
                                                {{ $video->autore->nome }}
                                            </div>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($video->formato)
                                            <span class="badge bg-info">{{ $video->formato->nome }}</span>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($video->location)
                                            <span class="text-nowrap">
                                                <i class="fas fa-map-marker-alt text-danger me-1"></i>
                                                {{ $video->location->name }}
                                            </span>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($video->series->isNotEmpty())
                                            @foreach($video->series->take(2) as $serie)
                                                <span class="badge bg-success me-1">{{ $serie->nome }}</span>
                                            @endforeach
                                            @if($video->series->count() > 2)
                                                <span class="badge bg-light text-dark">+{{ $video->series->count() - 2 }}</span>
                                            @endif
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($video->famiglie->isNotEmpty())
                                            @foreach($video->famiglie->take(2) as $famiglia)
                                                <span class="badge bg-warning text-dark me-1">{{ $famiglia->nome }}</span>
                                            @endforeach
                                            @if($video->famiglie->count() > 2)
                                                <span class="badge bg-light text-dark">+{{ $video->famiglie->count() - 2 }}</span>
                                            @endif
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($video->tags->isNotEmpty())
                                            @foreach($video->tags->take(3) as $tag)
                                                <span class="badge bg-primary me-1">{{ $tag->nome }}</span>
                                            @endforeach
                                            @if($video->tags->count() > 3)
                                                <span class="badge bg-light text-dark">+{{ $video->tags->count() - 3 }}</span>
                                            @endif
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button type="button" class="btn btn-outline-info" data-bs-toggle="tooltip" title="Visualizza" onclick="viewVideo({{ $video->id }})">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <a href="{{ route('videos.edit', $video->id) }}" class="btn btn-outline-warning" data-bs-toggle="tooltip" title="Modifica">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-outline-danger" data-bs-toggle="tooltip" title="Elimina" onclick="deleteVideo({{ $video->id }}, '{{ $video->titolo }}')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="11" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="fas fa-video fa-3x mb-3"></i>
                                            <h5>Nessun video disponibile</h5>
                                            <p>Inizia aggiungendo il tuo primo video</p>
                                            <a href="{{ route('videos.create') }}" class="btn btn-primary">
                                                <i class="fas fa-plus me-2"></i>Aggiungi Video
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Vista Griglia -->
                    <div id="gridView" class="d-none p-3">
                        <div class="row g-3">
                            @foreach($videos as $video)
                            <div class="col-md-6 col-lg-4 col-xl-3">
                                <div class="card h-100 video-card">
                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 150px; overflow: hidden;">
                                        @if($video->thumbnail_url)
                                            <img src="{{ $video->thumbnail_url }}" alt="Thumbnail" style="width: 100%; height: 100%; object-fit: cover;">
                                        @else
                                            <i class="fas fa-play fa-2x text-muted"></i>
                                        @endif
                                    </div>
                                    <div class="card-body">
                                        <h6 class="card-title">{{ Str::limit($video->titolo, 40) }}</h6>
                                        <p class="card-text text-muted small">
                                            <i class="fas fa-calendar me-1"></i>{{ $video->anno }}
                                            <br><i class="fas fa-clock me-1"></i>
                                            @php
                                                $minutes = intdiv($video->durata_secondi, 60);
                                                $seconds = $video->durata_secondi % 60;
                                            @endphp
                                            {{ $minutes }}:{{ str_pad($seconds, 2, '0', STR_PAD_LEFT) }}
                                        </p>
                                        <div class="d-flex justify-content-between align-items-center mt-3">
                                            <div class="btn-group btn-group-sm">
                                                <button class="btn btn-outline-info" onclick="viewVideo({{ $video->id }})">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <a href="{{ route('videos.edit', $video->id) }}" class="btn btn-outline-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button class="btn btn-outline-danger" onclick="deleteVideo({{ $video->id }}, '{{ $video->titolo }}')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Azioni batch -->
            <div class="card mt-3 d-none" id="batchActions">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <span id="selectedCount">0 video selezionati</span>
                        <div class="btn-group">
                            <button type="button" class="btn btn-outline-danger" onclick="batchDelete()">
                                <i class="fas fa-trash me-2"></i>Elimina Selezionati
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal per visualizzazione video -->
<div class="modal fade" id="videoModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Dettagli Video</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="videoDetails">
                <!-- Contenuto caricato dinamicamente -->
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inizializza tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Gestione ricerca
    const searchInput = document.getElementById('searchInput');
    const table = document.getElementById('videosTable');
    
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = table.querySelectorAll('tbody tr');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });

    // Gestione filtri
    const filters = ['filterAnno', 'filterFormato', 'filterLocation'];
    filters.forEach(filterId => {
        document.getElementById(filterId).addEventListener('change', applyFilters);
    });

    // Gestione selezione multipla
    const selectAll = document.getElementById('selectAll');
    const rowSelects = document.querySelectorAll('.row-select');
    const batchActions = document.getElementById('batchActions');
    const selectedCount = document.getElementById('selectedCount');

    selectAll.addEventListener('change', function() {
        rowSelects.forEach(checkbox => checkbox.checked = this.checked);
        updateBatchActions();
    });

    rowSelects.forEach(checkbox => {
        checkbox.addEventListener('change', updateBatchActions);
    });

    // Gestione cambio vista
    document.getElementById('viewTable').addEventListener('click', function() {
        document.getElementById('tableView').classList.remove('d-none');
        document.getElementById('gridView').classList.add('d-none');
        this.classList.add('active');
        document.getElementById('viewGrid').classList.remove('active');
    });

    document.getElementById('viewGrid').addEventListener('click', function() {
        document.getElementById('tableView').classList.add('d-none');
        document.getElementById('gridView').classList.remove('d-none');
        this.classList.add('active');
        document.getElementById('viewTable').classList.remove('active');
    });

    // Pulizia filtri
    document.getElementById('clearFilters').addEventListener('click', function() {
        searchInput.value = '';
        filters.forEach(filterId => {
            document.getElementById(filterId).value = '';
        });
        applyFilters();
    });

    // Ordinamento tabella
    let currentSort = { column: null, asc: true };

    document.querySelectorAll('.sortable').forEach(function(header) {
        header.addEventListener('click', function() {
            const sortKey = header.getAttribute('data-sort');
            sortTableBy(sortKey, header);
        });
    });

    function sortTableBy(key, header) {
        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr')).filter(row => row.style.display !== 'none');
        let getValue;

        switch (key) {
            case 'titolo':
                getValue = row => row.querySelector('td:nth-child(2) strong').textContent.trim().toLowerCase();
                break;
            case 'anno':
                getValue = row => row.querySelector('td:nth-child(3) .badge').textContent.trim();
                break;
            case 'durata':
                getValue = row => {
                    const val = row.querySelector('td:nth-child(4) span').textContent.match(/(\d+):(\d+)/);
                    return val ? (parseInt(val[1],10) * 60 + parseInt(val[2],10)) : 0;
                };
                break;
            default:
                return;
        }

        // Toggle sort direction if clicking the same header
        if (currentSort.column === key) {
            currentSort.asc = !currentSort.asc;
        } else {
            currentSort = { column: key, asc: true };
        }

        // Remove sort icon highlight from all headers
        document.querySelectorAll('.sortable').forEach(h => h.classList.remove('table-active'));

        // Highlight current sort header
        header.classList.add('table-active');

        rows.sort((a, b) => {
            const aValue = getValue(a);
            const bValue = getValue(b);
            if (aValue < bValue) return currentSort.asc ? -1 : 1;
            if (aValue > bValue) return currentSort.asc ? 1 : -1;
            return 0;
        });

        // Re-append sorted rows
        rows.forEach(row => tbody.appendChild(row));
    }

    function applyFilters() {
        const rows = table.querySelectorAll('tbody tr');
        const searchTerm = searchInput.value.toLowerCase();
        const annoFilter = document.getElementById('filterAnno').value;
        const formatoFilter = document.getElementById('filterFormato').value;
        const locationFilter = document.getElementById('filterLocation').value;

        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            if (cells.length > 1) {
                const text = row.textContent.toLowerCase();
                const anno = cells[2].textContent.trim();
                const formato = cells[5].textContent.trim();
                const location = cells[6].textContent.trim();

                const matchesSearch = text.includes(searchTerm);
                const matchesAnno = !annoFilter || anno.includes(annoFilter);
                const matchesFormato = !formatoFilter || formato.includes(formatoFilter);
                const matchesLocation = !locationFilter || location.includes(locationFilter);

                row.style.display = (matchesSearch && matchesAnno && matchesFormato && matchesLocation) ? '' : 'none';
            }
        });
    }

    function updateBatchActions() {
        const selected = document.querySelectorAll('.row-select:checked');
        selectedCount.textContent = `${selected.length} video selezionati`;
        
        if (selected.length > 0) {
            batchActions.classList.remove('d-none');
        } else {
            batchActions.classList.add('d-none');
        }
    }
});

function viewVideo(id) {
    fetch(`/admin/videos/${id}/details`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('videoDetails').innerHTML = `
                <div class="row">
                    <div class="col-md-4 mb-3">
                        ${data.thumbnail_url ? 
                            `<img src="${data.thumbnail_url}" alt="Thumbnail" style="width:100%;border-radius:8px;">`
                            : `<div class="bg-light d-flex align-items-center justify-content-center" style="width:100%;height:120px;border-radius:8px;"><i class='fas fa-play fa-2x text-muted'></i></div>`
                        }
                    </div>
                    <div class="col-md-8">
                        <h6>Informazioni Generali</h6>
                        <table class="table table-sm">
                            <tr><td><strong>Titolo:</strong></td><td>${data.titolo}</td></tr>
                            <tr><td><strong>Anno:</strong></td><td>${data.anno}</td></tr>
                            <tr><td><strong>Durata:</strong></td><td>${data.durata}</td></tr>
                            <tr><td><strong>Autore:</strong></td><td>${data.autore || 'N/A'}</td></tr>
                            <tr><td><strong>Formato:</strong></td><td>${data.formato || 'N/A'}</td></tr>
                            <tr><td><strong>Location:</strong></td><td>${data.location || 'N/A'}</td></tr>
                        </table>
                        <h6>Categorie</h6>
                        <p><strong>Serie:</strong> ${data.series || 'Nessuna'}</p>
                        <p><strong>Famiglie:</strong> ${data.famiglie || 'Nessuna'}</p>
                        <p><strong>Tag:</strong> ${data.tags || 'Nessun tag'}</p>
                        ${data.descrizione ? `<div class="mt-3"><h6>Descrizione</h6><p>${data.descrizione}</p></div>` : ''}
                    </div>
                </div>
            `;
            new bootstrap.Modal(document.getElementById('videoModal')).show();
        });
}

function deleteVideo(id, title) {
    if (confirm(`Sei sicuro di voler eliminare il video "${title}"?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/videos/${id}`;
        form.innerHTML = `
            @csrf
            @method('DELETE')
        `;
        document.body.appendChild(form);
        form.submit();
    }
}

function batchDelete() {
    const selected = document.querySelectorAll('.row-select:checked');
    if (selected.length > 0 && confirm(`Sei sicuro di voler eliminare ${selected.length} video?`)) {
        const ids = Array.from(selected).map(cb => cb.value);
        // Implementa logica di eliminazione batch
        console.log('Elimina video:', ids);
    }
}
</script>

<style>
    .video-card {
        transition: transform 0.2s;
    }
    .video-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .sortable {
        cursor: pointer;
        user-select: none;
    }
    .table tbody tr:hover {
        background-color: rgba(0,0,0,0.025);
    }
    .sticky-top {
        z-index: 10;
    }
    /* Rimosso l'hover sui th.sortable */
</style>
@endpush
@endsection