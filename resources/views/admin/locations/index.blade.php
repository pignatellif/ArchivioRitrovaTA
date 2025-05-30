@extends('layouts.admin')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Gestione Luoghi</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Pulsanti di navigazione -->
    <div class="mb-3 d-flex justify-content-between">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Torna alla Dashboard</a>
        <a href="{{ route('videos.create') }}" class="btn btn-primary">Aggiungi un nuovo video</a>
    </div>

    <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Nome</th>
                        <th>Latitudine</th>
                        <th>Longitudine</th>
                        <th>Azioni</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($locations as $location)
                        <tr>
                            <td>{{ $location->name }}</td>
                            <td>{{ $location->lat }}</td>
                            <td>{{ $location->lon }}</td>
                            <td class="text-nowrap">
                                <a href="{{ route('locations.edit', $location->id) }}" class="btn btn-warning btn-sm"><i class="fa-solid fa-pen-to-square"></i></a>
                                <form action="{{ route('locations.destroy', $location->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Sei sicuro di voler eliminare questa posizione?')"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection