@extends('layouts.admin')

@section('content')
    <h2>Modifica Evento</h2>

    <form action="{{ route('events.update', $event->id) }}" method="POST">
        @csrf
        @method('PUT')

        <label for="title">Titolo:</label>
        <input type="text" name="title" class="form-control" value="{{ old('title', $event->title) }}" required>

        <label for="description">Descrizione:</label>
        <textarea name="description" class="form-control">{{ old('description', $event->description) }}</textarea>

        <label for="date">Data:</label>
        <input type="date" name="date" class="form-control" value="{{ old('date', $event->date) }}" required>

        <button type="submit" class="btn btn-primary mt-3">Aggiorna</button>
        <a href="{{ route('events.index') }}" class="btn btn-secondary mt-3">Annulla</a>
    </form>
@endsection
