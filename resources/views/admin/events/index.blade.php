@extends('layouts.admin')

@section('content')
    <h2>Gestione Eventi</h2>
    <a href="{{ route('events.create') }}" class="btn btn-primary">Aggiungi Evento</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table mt-3">
        <thead>
            <tr>
                <th>Titolo</th>
                <th>Data</th>
                <th>Azioni</th>
            </tr>
        </thead>
        <tbody>
            @foreach($events as $event)
                <tr>
                    <td>{{ $event->title }}</td>
                    <td>{{ $event->date }}</td>
                    <td>
                        <a href="{{ route('events.edit', $event->id) }}" class="btn btn-warning">Modifica</a>
                        <form action="{{ route('events.destroy', $event->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Eliminare questo evento?')">Elimina</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
