@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Gestione Eventi</h2>

    <!-- Messaggi di successo -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Pulsanti di navigazione -->
    <div class="mb-3 d-flex justify-content-between">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Torna alla Dashboard</a>
        <a href="{{ route('events.create') }}" class="btn btn-primary">Aggiungi Evento</a>
    </div>

    <!-- Tabella con gli eventi -->
    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Copertina</th>
                    <th>Titolo</th>
                    <th>Data di Inizio</th>
                    <th>Data di Fine</th>
                    <th>Luogo</th>
                    <th>Azioni</th>
                </tr>
            </thead>
            <tbody>
                @forelse($events as $event)
                    <tr>
                        <td>
                            @if($event->cover_image)
                                <img src="{{ asset($event->cover_image) }}" alt="Copertina" style="width: 80px; height: auto; border-radius: 8px;">
                            @else
                                <span class="text-muted">Nessuna immagine</span>
                            @endif
                        </td>
                        <td>{{ $event->titolo }}</td>
                        <td>{{ $event->start_date ? \Carbon\Carbon::parse($event->start_date)->format('d/m/Y') : '-' }}</td>
                        <td>{{ $event->end_date ? \Carbon\Carbon::parse($event->end_date)->format('d/m/Y') : '-' }}</td>
                        <td>{{ $event->luogo ?? '-' }}</td>
                        <td class="text-nowrap">
                            <a href="{{ route('events.edit', $event->id) }}" class="btn btn-warning btn-sm" title="Modifica">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <form action="{{ route('events.destroy', $event->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Eliminare questo evento?')" title="Elimina">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">Nessun evento disponibile.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection