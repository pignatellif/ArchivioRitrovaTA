@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">

            <div class="mb-4 d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1"><i class="fas fa-user-edit me-2 text-primary"></i>Gestione Autori</h2>
                    <p class="text-muted mb-0">Totale autori: {{ $authors->count() }}</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Dashboard
                    </a>
                    <a href="{{ route('authors.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Aggiungi Autore
                    </a>
                </div>
            </div>

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
                                <input type="text" class="form-control" id="searchInput" placeholder="Cerca per nome, biografia, formato...">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabella autori -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-list me-2"></i>Elenco Autori</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="authorsTable">
                            <thead class="table-dark">
                                <tr>
                                    <th>Immagine</th>
                                    <th class="sortable" data-sort="nome">Nome <i class="fas fa-sort ms-1"></i></th>
                                    <th class="sortable" data-sort="anno">Anno di Nascita <i class="fas fa-sort ms-1"></i></th>
                                    <th>Biografia</th>
                                    <th>Formati</th>
                                    <th>Video</th>
                                    <th class="text-center">Azioni</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($authors as $author)
                                <tr class="author-row"
                                    data-nome="{{ strtolower($author->nome) }}"
                                    data-bio="{{ strtolower(strip_tags($author->biografia)) }}"
                                    data-formati="{{ strtolower(optional($author->formati)->pluck('nome')->implode(',')) }}">
                                    <td>
                                        @if($author->immagine_profilo)
                                            <img src="{{ asset($author->immagine_profilo) }}" alt="Profilo" style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px;">
                                        @else
                                            <span class="text-muted">Nessuna immagine</span>
                                        @endif
                                    </td>
                                    <td class="fw-bold">{{ $author->nome }}</td>
                                    <td>{{ $author->anno_nascita ?? 'N/D' }}</td>
                                    <td>{{ \Illuminate\Support\Str::limit(strip_tags($author->biografia), 80, '...') }}</td>
                                    <td>
                                        @if($author->formati && $author->formati->count())
                                            @foreach($author->formati as $formato)
                                                <span class="badge bg-info text-dark mb-1">{{ $formato->nome }}</span>
                                            @endforeach
                                        @else
                                            <span class="text-muted">Nessuno</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($author->videos && $author->videos->count())
                                            <ul class="list-unstyled mb-0">
                                                @foreach($author->videos->take(3) as $video)
                                                    <li><i class="fas fa-play-circle text-primary me-1"></i>{{ $video->titolo }}</li>
                                                @endforeach
                                                @if($author->videos->count() > 3)
                                                    <li class="text-muted small">+{{ $author->videos->count() - 3 }} altri...</li>
                                                @endif
                                            </ul>
                                        @else
                                            <span class="text-muted">Nessuno</span>
                                        @endif
                                    </td>
                                    <td class="text-nowrap text-center">
                                        <a href="{{ route('authors.edit', $author->id) }}" class="btn btn-outline-warning btn-sm" data-bs-toggle="tooltip" title="Modifica">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('authors.destroy', $author->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Eliminare questo autore?')" data-bs-toggle="tooltip" title="Elimina">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="fas fa-user-edit fa-3x mb-3"></i>
                                            <h5>Nessun autore disponibile</h5>
                                            <a href="{{ route('authors.create') }}" class="btn btn-primary mt-2">
                                                <i class="fas fa-plus me-2"></i>Aggiungi un nuovo autore
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
    const authorRows = document.querySelectorAll('.author-row');
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase().trim();
        authorRows.forEach(row => {
            const nome = row.dataset.nome || '';
            const bio = row.dataset.bio || '';
            const formati = row.dataset.formati || '';
            const rowText = row.innerText.toLowerCase();
            if (
                nome.includes(searchTerm) ||
                bio.includes(searchTerm) ||
                formati.includes(searchTerm) ||
                rowText.includes(searchTerm)
            ) {
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
        const table = document.getElementById('authorsTable');
        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr')).filter(row => row.style.display !== 'none');
        let getValue;
        switch (key) {
            case 'nome':
                getValue = row => row.querySelector('td:nth-child(2)').textContent.trim().toLowerCase();
                break;
            case 'anno':
                getValue = row => {
                    const val = row.querySelector('td:nth-child(3)').textContent.trim();
                    return val === 'N/D' ? 0 : parseInt(val, 10);
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