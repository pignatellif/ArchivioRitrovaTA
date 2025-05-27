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
        <a href="{{ route('videos.create') }}" class="btn btn-primary">Aggiungi un nuovo video</a>
    </div>

    <!-- Tabella con i video -->
    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Titolo</th>
                    <th>Anno</th>
                    <th>Durata</th>
                    <th>Autore</th>
                    <th>Formato</th>
                    <th>Location</th>
                    <th>Serie</th>
                    <th>Famiglie</th>
                    <th>Tag</th>
                    <th>Azioni</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($videos as $video)
                <tr>
                    <td>{{ $video->titolo }}</td>
                    <td>{{ $video->anno }}</td>
                    <td>
                        @php
                            $minutes = intdiv($video->durata_secondi, 60);
                            $seconds = $video->durata_secondi % 60;
                        @endphp
                        {{ $minutes }} min {{ $seconds }} sec
                    </td>
                    <td>{{ $video->autore->nome ?? 'N/A' }}</td>
                    <td>{{ $video->formato->nome ?? 'N/A' }}</td>
                    <td>{{ $video->location->name ?? 'N/A' }}</td>
                    <td>
                        @if($video->series->isNotEmpty())
                            <ul class="list-unstyled mb-0">
                                @foreach($video->series as $serie)
                                    <li>{{ $serie->nome }}</li>
                                @endforeach
                            </ul>
                        @else
                            <span class="text-muted">Nessuna</span>
                        @endif
                    </td>
                    <td>
                        @if($video->famiglie->isNotEmpty())
                            <ul class="list-unstyled mb-0">
                                @foreach($video->famiglie as $famiglia)
                                    <li>{{ $famiglia->nome }}</li>
                                @endforeach
                            </ul>
                        @else
                            <span class="text-muted">Nessuna</span>
                        @endif
                    </td>
                    <td>
                        @if($video->tags->isNotEmpty())
                            @foreach($video->tags as $tag)
                                <span class="badge bg-primary">{{ $tag->nome }}</span>
                            @endforeach
                        @else
                            <span class="text-muted">Nessun tag</span>
                        @endif
                    </td>
                    <td class="text-nowrap">
                        <a href="{{ route('videos.edit', $video->id) }}" class="btn btn-warning btn-sm"><i class="fa-solid fa-pen-to-square"></i></a>
                        <form action="{{ route('videos.destroy', $video->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Sei sicuro di voler eliminare questo video?')"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-center">Nessun video disponibile.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection