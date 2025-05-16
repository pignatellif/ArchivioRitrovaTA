@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Gestione Serie</h2>

    <!-- Messaggi di successo -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Pulsante per aggiungere una nuova serie -->
    <a href="{{ route('series.create') }}" class="btn btn-primary mb-3">Crea Nuova Serie</a>

    <!-- Tabella con le serie -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Descrizione</th>
                <th>Numero Video</th>
                <th>Azioni</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($series as $serie)
            <tr>
                <td>{{ $serie->nome }}</td>
                <td class="truncate">{{ Str::limit($serie->descrizione, 100) }}</td> <!-- Troncamento della descrizione -->
                <td>{{ $serie->videos->count() }}</td>
                <td>
                    <a href="{{ route('series.edit', $serie->id) }}" class="btn btn-warning btn-sm">Modifica</a>
                    <form action="{{ route('series.destroy', $serie->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Sei sicuro di voler eliminare questa serie?')">Elimina</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary mt-3">Torna alla Dashboard</a>
</div>
@endsection

@push('styles')
    <style>
        .truncate {
            max-width: 200px; /* Limita la larghezza della descrizione */
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
    </style>
@endpush
