@extends('layouts.admin')

@section('content')
    <h2>Gestione Autori</h2>
    <a href="{{ route('authors.create') }}" class="btn btn-primary mb-3">Aggiungi Autore</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table mt-3">
        <thead>
            <tr>
                <th>Immagine</th>
                <th>Nome</th>
                <th>Anno di Nascita</th>
                <th>Biografia</th>
                <th>Azioni</th>
            </tr>
        </thead>
        <tbody>
            @foreach($authors as $author)
                <tr>
                    <td>
                        @if($author->immagine_profilo)
                            <img src="{{ asset($author->immagine_profilo) }}" alt="Profilo" style="width: 100px; height: auto;">
                        @else
                            Nessuna immagine
                        @endif
                    </td>
                    <td>{{ $author->nome }}</td>
                    <td>{{ $author->anno_nascita ?? 'N/D' }}</td>
                    <td>{{ \Illuminate\Support\Str::limit($author->biografia, 100, '...') }}</td>
                    <td>
                        <a href="{{ route('authors.edit', $author->id) }}" class="btn btn-warning mb-1">Modifica</a>
                        <form action="{{ route('authors.destroy', $author->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Eliminare questo autore?')">Elimina</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
