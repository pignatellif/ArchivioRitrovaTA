@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Gestione Serie</h2>

    <!-- Messaggi di successo -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Messaggi di errore -->
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Pulsanti di navigazione -->
    <div class="mb-3 d-flex justify-content-between">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Torna alla Dashboard</a>
        <a href="{{ route('series.create') }}" class="btn btn-primary">Crea Nuova Serie</a>
    </div>

    <!-- Tabella con le serie -->
    <div class="table-responsive">
        <table class="table table-striped align-middle" id="seriesTable">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Descrizione</th>
                    <th>Numero Video</th>
                    <th>Video</th>
                    <th>Azioni</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($series as $serie)
                <tr id="serie-{{ $serie->id }}">
                    <td>{{ $serie->id }}</td>
                    <td>{{ $serie->nome }}</td>
                    <td class="truncate">{{ \Illuminate\Support\Str::limit($serie->descrizione, 100) }}</td>
                    <td>{{ $serie->videos->count() }}</td>
                    <td>
                        @if($serie->videos && $serie->videos->count())
                            <ul class="list-unstyled mb-0">
                                @foreach($serie->videos as $video)
                                    <li>{{ $video->titolo }}</li>
                                @endforeach
                            </ul>
                        @else
                            <span class="text-muted">Nessuno</span>
                        @endif
                    </td>
                    <td class="text-nowrap">
                        <a href="{{ route('series.edit', $serie->id) }}" class="btn btn-warning btn-sm"><i class="fa-solid fa-pen-to-square"></i></a>
                        <form action="{{ route('series.destroy', $serie->id) }}" method="POST" class="d-inline delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm delete-btn" 
                                    data-serie-id="{{ $serie->id }}"
                                    data-serie-name="{{ $serie->nome }}">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">Nessuna serie trovata. <a href="{{ route('series.create') }}">Crea la prima serie</a></td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal di conferma eliminazione -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Conferma Eliminazione</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Sei sicuro di voler eliminare la serie "<span id="serieNameToDelete"></span>"?</p>
                <p class="text-danger"><small>Questa azione non può essere annullata.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Elimina</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .truncate {
        max-width: 200px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .table-responsive {
        border-radius: 0.375rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .alert {
        border: none;
        border-radius: 0.5rem;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const deleteButtons = document.querySelectorAll('.delete-btn');
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    const confirmDeleteBtn = document.getElementById('confirmDelete');
    const serieNameSpan = document.getElementById('serieNameToDelete');
    let currentForm = null;

    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();

            const serieId = this.dataset.serieId;
            const serieName = this.dataset.serieName;
            currentForm = this.closest('form');

            serieNameSpan.textContent = serieName;
            deleteModal.show();
        });
    });

    confirmDeleteBtn.addEventListener('click', function() {
        if (currentForm) {
            this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Eliminando...';
            this.disabled = true;

            currentForm.submit();
        }
    });

    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert-dismissible');
        alerts.forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});
</script>
@endpush