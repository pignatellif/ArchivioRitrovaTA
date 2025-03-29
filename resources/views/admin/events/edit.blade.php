@extends('layouts.admin')

@section('content')
    <h2>Modifica Evento</h2>

    <form action="{{ route('events.update', $event->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <label for="title">Titolo:</label>
        <input type="text" name="title" class="form-control" value="{{ old('title', $event->title) }}" required>

        <label for="description">Descrizione:</label>
        <textarea name="description" class="form-control">{{ old('description', $event->description) }}</textarea>

        <label for="date">Data:</label>
        <input type="date" name="date" class="form-control" value="{{ old('date', $event->date) }}" required>

        <label for="cover_image">Immagine di copertina:</label>
        <input type="file" name="cover_image" class="form-control">
        
        @if($event->cover_image)
            <div class="mt-2">
                <img src="{{ asset('storage/' . $event->cover_image) }}" alt="Cover Image" style="max-width: 200px;">
            </div>
        @endif

        <button type="submit" class="btn btn-primary mt-3">Aggiorna</button>
        <a href="{{ route('events.index') }}" class="btn btn-secondary mt-3">Annulla</a>
    </form>
@endsection