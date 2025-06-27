@extends('layouts.admin')

@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <!-- Header con titolo e statistiche -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-1"><i class="fas fa-play-circle me-2 text-primary"></i>Gestione Serie</h2>
                    <p class="text-muted mb-0">Totale serie: {{ $series->count() }}</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Dashboard
                    </a>
                    <a href="{{ route('series.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Nuova Serie
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
                        <div class="col-md-6">
                            <label class="form-label">Ricerca</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" class="form-control" id="searchInput" placeholder="Cerca per nome o descrizione...">
                            </div>
                        </div>
                        <div class="col-md-6 text-end">
                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button" class="btn btn-outline-secondary active" id="viewTable">
                                    <i class="fas fa-table"></i>
                                </button>
                                <button type="button" class="btn btn-outline-secondary" id="viewGrid">
                                    <i class="fas fa-th"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Vista Tabella -->
            <div id="tableView" class="table-responsive">
                <table class="table table-hover mb-0" id="seriesTable">
                    <thead class="table-dark sticky-top">
                        <tr>
                            <th class="sortable" data-sort="nome">Nome <i class="fas fa-sort ms-1"></i></th>
                            <th class="sortable" data-sort="descrizione">Descrizione <i class="fas fa-sort ms-1"></i></th>
                            <th class="sortable" data-sort="totale_video">Totale Video <i class="fas fa-sort ms-1"></i></th>
                            <th class="sortable" data-sort="data_creazione">Creato il <i class="fas fa-sort ms-1"></i></th>
                            <th class="text-center">Azioni</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($series as $serie)
                        <tr class="serie-item" data-name="{{ strtolower($serie->nome) }}" data-description="{{ strtolower($serie->descrizione) }}">
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="serie-avatar-sm bg-gradient-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                        <i class="fas fa-play"></i>
                                    </div>
                                    <span>{{ $serie->nome }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="text-muted small">{{ \Illuminate\Support\Str::limit($serie->descrizione, 80) }}</span>
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $serie->videos->count() }}</span>
                            </td>
                            <td class="text-muted">
                                {{ $serie->created_at ? $serie->created_at->format('d/m/Y') : 'N/A' }}
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('series.edit', $serie->id) }}" class="btn btn-outline-warning" data-bs-toggle="tooltip" title="Modifica">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-outline-danger delete-btn" data-bs-toggle="tooltip" title="Elimina"
                                            data-serie-id="{{ $serie->id }}" data-serie-name="{{ $serie->nome }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-play-circle fa-3x mb-3"></i>
                                    <h5>Nessuna serie disponibile</h5>
                                    <a href="{{ route('series.create') }}" class="btn btn-primary mt-2">
                                        <i class="fas fa-plus me-2"></i>Nuova Serie
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Vista Griglia (card lunga per ogni serie) -->
            <div id="gridView" class="d-none p-3">
                @forelse ($series as $serie)
                <div class="row mb-4 serie-item" data-name="{{ strtolower($serie->nome) }}" data-description="{{ strtolower($serie->descrizione) }}">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm h-100 serie-card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="d-flex align-items-start mb-3">
                                            <div class="serie-avatar bg-gradient-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                                                <i class="fas fa-play"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h5 class="card-title mb-1">{{ $serie->nome }}</h5>
                                                <p class="text-muted mb-2 serie-description">
                                                    {{ \Illuminate\Support\Str::limit($serie->descrizione, 150) }}
                                                </p>
                                                <div class="d-flex align-items-center text-muted small">
                                                    <span class="me-3">
                                                        <i class="fas fa-video me-1"></i>
                                                        {{ $serie->videos->count() }} video
                                                    </span>
                                                    <span class="me-3">
                                                        <i class="fas fa-calendar me-1"></i>
                                                        {{ $serie->created_at ? $serie->created_at->format('d/m/Y') : 'N/A' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="d-flex flex-column h-100">
                                            @if($serie->videos && $serie->videos->count() > 0)
                                                <div class="mb-3 flex-grow-1">
                                                    <h6 class="text-muted mb-2 small">ULTIMI VIDEO</h6>
                                                    <div class="video-list">
                                                        @foreach($serie->videos->take(3) as $video)
                                                            <div class="d-flex align-items-center mb-2">
                                                                <div class="video-thumb bg-light rounded me-2" style="width: 50px; height: 35px; overflow: hidden;">
                                                                    @if($video->thumbnail_url)
                                                                        <img src="{{ $video->thumbnail_url }}" alt="Thumbnail" style="width: 100%; height: 100%; object-fit: cover;">
                                                                    @else
                                                                        <i class="fas fa-play text-muted"></i>
                                                                    @endif
                                                                </div>
                                                                <span class="small text-truncate">{{ $video->titolo }}</span>
                                                            </div>
                                                        @endforeach
                                                        @if($serie->videos->count() > 3)
                                                            <small class="text-muted">+{{ $serie->videos->count() - 3 }} altri video</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            @else
                                                <div class="mb-3 flex-grow-1 d-flex align-items-center justify-content-center">
                                                    <div class="text-center text-muted">
                                                        <i class="fas fa-video fa-2x mb-2 opacity-50"></i>
                                                        <p class="small mb-0">Nessun video</p>
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('series.edit', $serie->id) }}"
                                                   class="btn btn-outline-primary btn-sm flex-fill">
                                                    <i class="fas fa-edit me-1"></i>
                                                    Modifica
                                                </a>
                                                <button type="button"
                                                        class="btn btn-outline-danger btn-sm delete-btn"
                                                        data-serie-id="{{ $serie->id }}"
                                                        data-serie-name="{{ $serie->nome }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-5">
                    <div class="empty-state">
                        <i class="fas fa-video fa-4x text-muted mb-3"></i>
                        <h4 class="text-muted mb-2">Nessuna serie trovata</h4>
                        <p class="text-muted mb-4">Inizia creando la tua prima serie di video</p>
                        <a href="{{ route('series.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>
                            Crea Prima Serie
                        </a>
                    </div>
                </div>
                @endforelse
            </div>

            <!-- Modal di conferma eliminazione -->
            <div class="modal fade" id="deleteModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow">
                        <div class="modal-header border-0 pb-0">
                            <h5 class="modal-title text-danger">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Conferma Eliminazione
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body pt-0">
                            <p class="mb-3">Sei sicuro di voler eliminare la serie "<strong id="serieNameToDelete"></strong>"?</p>
                            <div class="alert alert-warning border-0">
                                <i class="fas fa-info-circle me-2"></i>
                                <small>Questa azione eliminerà anche tutti i video associati e non può essere annullata.</small>
                            </div>
                        </div>
                        <div class="modal-footer border-0 pt-0">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times me-1"></i>
                                Annulla
                            </button>
                            <button type="button" class="btn btn-danger" id="confirmDelete">
                                <i class="fas fa-trash me-1"></i>
                                Elimina Definitivamente
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <form id="deleteForm" method="POST" style="display: none;">
                @csrf
                @method('DELETE')
            </form>

        </div>
    </div>
</div>

@push('styles')
<style>
    .serie-card {
        transition: transform 0.2s;
        border-radius: 12px;
    }
    .serie-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(102, 126, 234, 0.10);
    }
    .serie-avatar, .serie-avatar-sm {
        width: 60px;
        height: 60px;
        flex-shrink: 0;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .serie-avatar-sm {
        width: 40px;
        height: 40px;
    }
    .video-thumbnail, .video-thumb {
        width: 50px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .table th {
        font-weight: 600;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .btn {
        border-radius: 8px;
        transition: all 0.2s ease;
    }
    .btn:hover {
        transform: translateY(-1px);
    }
    .card {
        border-radius: 12px;
    }
    .modal-content {
        border-radius: 16px;
    }
    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    .video-list {
        max-height: 120px;
        overflow-y: auto;
    }
    .video-list::-webkit-scrollbar {
        width: 4px;
    }
    .video-list::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 2px;
    }
    .video-list::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 2px;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inizializza tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Gestione modal eliminazione
    const deleteButtons = document.querySelectorAll('.delete-btn');
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    const confirmDeleteBtn = document.getElementById('confirmDelete');
    const serieNameSpan = document.getElementById('serieNameToDelete');
    const deleteForm = document.getElementById('deleteForm');
    let currentSerieId = null;

    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            currentSerieId = this.dataset.serieId;
            const serieName = this.dataset.serieName;
            serieNameSpan.textContent = serieName;
            deleteForm.action = `/admin/series/${currentSerieId}`;
            deleteModal.show();
        });
    });

    confirmDeleteBtn.addEventListener('click', function() {
        if (currentSerieId) {
            this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Eliminando...';
            this.disabled = true;
            deleteForm.submit();
        }
    });

    // Vista: tabella <-> griglia
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

    // Ricerca live
    const searchInput = document.getElementById('searchInput');
    const serieItems = document.querySelectorAll('.serie-item');
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase().trim();
        serieItems.forEach(item => {
            const name = item.dataset.name || '';
            const description = item.dataset.description || '';
            if (name.includes(searchTerm) || description.includes(searchTerm)) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
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
        const table = document.getElementById('seriesTable');
        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr')).filter(row => row.style.display !== 'none');
        let getValue;

        switch (key) {
            case 'nome':
                getValue = row => row.querySelector('td:nth-child(1) span').textContent.trim().toLowerCase();
                break;
            case 'descrizione':
                getValue = row => row.querySelector('td:nth-child(2)').textContent.trim().toLowerCase();
                break;
            case 'totale_video':
                getValue = row => parseInt(row.querySelector('td:nth-child(3) .badge').textContent.trim(), 10);
                break;
            case 'data_creazione':
                getValue = row => {
                    const val = row.querySelector('td:nth-child(4)').textContent.trim();
                    if(val === 'N/A') return 0;
                    const [d, m, y] = val.split('/');
                    return new Date(`${y}-${m}-${d}`).getTime();
                };
                break;
            default:
                return;
        }

        if (currentSort.column === key) {
            currentSort.asc = !currentSort.asc;
        } else {
            currentSort = { column: key, asc: true };
        }
        document.querySelectorAll('.sortable').forEach(h => h.classList.remove('table-active'));
        header.classList.add('table-active');

        rows.sort((a, b) => {
            const aValue = getValue(a);
            const bValue = getValue(b);
            if (aValue < bValue) return currentSort.asc ? -1 : 1;
            if (aValue > bValue) return currentSort.asc ? 1 : -1;
            return 0;
        });

        rows.forEach(row => tbody.appendChild(row));
    }
});
</script>
@endpush
@endsection