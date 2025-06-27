@extends('layouts.admin')

@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <!-- Header con titolo e statistiche -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-1"><i class="fas fa-calendar-alt me-2 text-primary"></i>Gestione Eventi</h2>
                    <p class="text-muted mb-0">Totale eventi: {{ $events->count() }}</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Dashboard
                    </a>
                    <a href="{{ route('events.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Nuovo Evento
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

            <!-- Ricerca -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Ricerca</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" class="form-control" id="searchInput" placeholder="Cerca per titolo, luogo, date...">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabella eventi -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-list me-2"></i>Elenco Eventi</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="eventsTable">
                            <thead class="table-dark sticky-top">
                                <tr>
                                    <th>Copertina</th>
                                    <th class="sortable" data-sort="titolo">Titolo <i class="fas fa-sort ms-1"></i></th>
                                    <th class="sortable" data-sort="start_date">Inizio <i class="fas fa-sort ms-1"></i></th>
                                    <th class="sortable" data-sort="end_date">Fine <i class="fas fa-sort ms-1"></i></th>
                                    <th class="sortable" data-sort="luogo">Luogo <i class="fas fa-sort ms-1"></i></th>
                                    <th class="text-center">Azioni</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($events as $event)
                                    <tr class="event-row" data-titolo="{{ strtolower($event->titolo) }}" data-luogo="{{ strtolower($event->luogo) }}">
                                        <td>
                                            @if($event->cover_image)
                                                <img src="{{ asset($event->cover_image) }}" alt="Copertina" style="width: 80px; height: 50px; object-fit:cover; border-radius: 8px;">
                                            @else
                                                <span class="text-muted">Nessuna immagine</span>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $event->titolo }}</strong>
                                        </td>
                                        <td>
                                            {{ $event->start_date ? \Carbon\Carbon::parse($event->start_date)->format('d/m/Y') : '-' }}
                                        </td>
                                        <td>
                                            {{ $event->end_date ? \Carbon\Carbon::parse($event->end_date)->format('d/m/Y') : '-' }}
                                        </td>
                                        <td>
                                            {{ $event->luogo ?? '-' }}
                                        </td>
                                        <td class="text-center text-nowrap">
                                            <a href="{{ route('events.edit', $event->id) }}" class="btn btn-outline-warning btn-sm" data-bs-toggle="tooltip" title="Modifica">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('events.contents.create', $event->id) }}" class="btn btn-outline-info btn-sm" data-bs-toggle="tooltip" title="Gestisci Contenuto">
                                                <i class="fas fa-file-lines"></i>
                                            </a>
                                            <form action="{{ route('events.destroy', $event->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Eliminare questo evento?')" data-bs-toggle="tooltip" title="Elimina">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="fas fa-calendar-alt fa-3x mb-3"></i>
                                            <h5>Nessun evento disponibile</h5>
                                            <a href="{{ route('events.create') }}" class="btn btn-primary mt-2">
                                                <i class="fas fa-plus me-2"></i>Aggiungi Evento
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .table th {
        font-weight: 600;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .table tbody tr:hover {
        background-color: rgba(0,0,0,0.025);
    }
    .sortable {
        cursor: pointer;
        user-select: none;
    }
    .sortable.table-active, .sortable:active, .sortable:focus {
        background: #e5e7ef;
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

    // Ricerca live
    const searchInput = document.getElementById('searchInput');
    const eventRows = document.querySelectorAll('.event-row');
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase().trim();
        eventRows.forEach(row => {
            const titolo = row.dataset.titolo || '';
            const luogo = row.dataset.luogo || '';
            const rowText = row.innerText.toLowerCase();
            if (titolo.includes(searchTerm) || luogo.includes(searchTerm) || rowText.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
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
        const table = document.getElementById('eventsTable');
        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr')).filter(row => row.style.display !== 'none');
        let getValue;
        switch (key) {
            case 'titolo':
                getValue = row => row.querySelector('td:nth-child(2) strong').textContent.trim().toLowerCase();
                break;
            case 'start_date':
                getValue = row => {
                    const val = row.querySelector('td:nth-child(3)').textContent.trim();
                    if (val === '-' || val === '') return 0;
                    const [d, m, y] = val.split('/');
                    return new Date(`${y}-${m}-${d}`).getTime();
                };
                break;
            case 'end_date':
                getValue = row => {
                    const val = row.querySelector('td:nth-child(4)').textContent.trim();
                    if (val === '-' || val === '') return 0;
                    const [d, m, y] = val.split('/');
                    return new Date(`${y}-${m}-${d}`).getTime();
                };
                break;
            case 'luogo':
                getValue = row => row.querySelector('td:nth-child(5)').textContent.trim().toLowerCase();
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