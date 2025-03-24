@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Gestione Video</h2>

    <!-- Messaggi di successo -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Pulsanti di navigazione -->
    <div class="mb-3 d-flex justify-content-between">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Torna alla Dashboard</a>
        <a href="{{ route('videos.create') }}" class="btn btn-primary"> Aggiungi un nuovo video</a>    </div>

    <!-- Tabella con i video -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Titolo</th>
                <th>Anno</th>
                <th>Durata</th>
                <th>Autore</th>
                <th>Azioni</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($videos as $video)
            <tr>
                <td>{{ $video->title }}</td>
                <td>{{ $video->year }}</td>
                <td>{{ $video->duration }} sec</td>
                <td>{{ $video->author }}</td>
                <td>
                    <a href="{{ route('videos.edit', $video->id) }}" class="btn btn-warning btn-sm">Modifica</a>
                    <form action="{{ route('videos.destroy', $video->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Sei sicuro di voler eliminare questo video?')">Elimina</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
