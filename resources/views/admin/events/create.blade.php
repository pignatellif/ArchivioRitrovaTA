@extends('layouts.admin')

@section('content')
    <h2>Aggiungi Evento</h2>

    <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <label for="titolo">Titolo:</label>
        <input type="text" name="titolo" class="form-control" required>

        <label for="descrizione">Descrizione:</label>
        <textarea name="descrizione" class="form-control"></textarea>

        <label for="start_date">Data di Inizio:</label>
        <input type="date" name="start_date" class="form-control" required>

        <label for="end_date">Data di Fine:</label>
        <input type="date" name="end_date" class="form-control">

        <label for="luogo">Luogo di Svolgimento:</label>
        <input type="text" name="luogo" class="form-control" required>

        <label for="cover_image">Immagine di Copertina:</label>
        <input type="file" name="cover_image" class="form-control">

        <button type="submit" class="btn btn-primary mt-3">Salva</button>
        <a href="{{ route('events.index') }}" class="btn btn-secondary mt-3">Annulla</a>
    </form>
@endsection