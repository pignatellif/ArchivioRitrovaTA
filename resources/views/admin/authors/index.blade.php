@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Gestione Autori</h2>

    <!-- Messaggi di successo -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Pulsanti di navigazione -->
    <div class="mb-3 d-flex justify-content-between">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Torna alla Dashboard</a>
        <a href="{{ route('authors.create') }}" class="btn btn-primary">Aggiungi un nuovo autore</a>
    </div>

    <!-- Tabella con gli autori -->
    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Immagine</th>
                    <th>Nome</th>
                    <th>Anno di Nascita</th>
                    <th>Biografia</th>
                    <th>Formati</th>
                    <th>Video</th>
                    <th>Azioni</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($authors as $author)
                <tr>
                    <td>
                        @if($author->immagine_profilo)
                            <img src="{{ asset($author->immagine_profilo) }}" alt="Profilo" style="width: 80px; height: auto; border-radius: 8px;">
                        @else
                            <span class="text-muted">Nessuna immagine</span>
                        @endif
                    </td>
                    <td>{{ $author->nome }}</td>
                    <td>{{ $author->anno_nascita ?? 'N/D' }}</td>
                    <td>{{ \Illuminate\Support\Str::limit(strip_tags($author->biografia), 100, '...') }}</td>
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
                                @foreach($author->videos as $video)
                                    <li>{{ $video->titolo }}</li>
                                @endforeach
                            </ul>
                        @else
                            <span class="text-muted">Nessuno</span>
                        @endif
                    </td>
                    <td class="text-nowrap">
                        <a href="{{ route('authors.edit', $author->id) }}" class="btn btn-warning btn-sm"><i class="fa-solid fa-pen-to-square"></i></a>
                        <form action="{{ route('authors.destroy', $author->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Eliminare questo autore?')"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">Nessun autore disponibile.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection