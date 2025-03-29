@extends('layouts.admin')

@section('content')
    <h2>Aggiungi Evento</h2>

    <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <label for="title">Titolo:</label>
        <input type="text" name="title" class="form-control" required>

        <label for="description">Descrizione:</label>
        <textarea name="description" class="form-control"></textarea>

        <label for="date">Data:</label>
        <input type="date" name="date" class="form-control" required>

        <label for="cover_image">Immagine di copertina:</label>
        <input type="file" name="cover_image" class="form-control">

        <button type="submit" class="btn btn-primary mt-3">Salva</button>
        <a href="{{ route('events.index') }}" class="btn btn-secondary mt-3">Annulla</a>
    </form>
@endsection