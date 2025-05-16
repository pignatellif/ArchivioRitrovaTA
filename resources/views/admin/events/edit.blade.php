@extends('layouts.admin')

@section('content')
    <h2>Modifica Evento</h2>

    <form action="{{ route('events.update', $event->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <label for="titolo">Titolo:</label>
        <input type="text" name="titolo" class="form-control" value="{{ old('titolo', $event->titolo) }}" required>

        <label for="descrizione">Descrizione:</label>
        <textarea name="descrizione" class="form-control">{{ old('descrizione', $event->descrizione) }}</textarea>

        <label for="start_date">Data di Inizio:</label>
        <input type="date" name="start_date" class="form-control" value="{{ old('start_date', $event->start_date->format('Y-m-d')) }}" required>

        <label for="end_date">Data di Fine:</label>
        <input type="date" name="end_date" class="form-control" value="{{ old('end_date', optional($event->end_date)->format('Y-m-d')) }}">

        <label for="luogo">Luogo di Svolgimento:</label>
        <input type="text" name="luogo" class="form-control" value="{{ old('luogo', $event->luogo) }}" required>

        <label for="cover_image">Immagine di Copertina:</label>
        <input type="file" name="cover_image" class="form-control">
        
        @if($event->cover_image)
            <div class="mt-2" id="cover-image-container">
                <img src="{{ asset($event->cover_image) }}" alt="Cover Image" style="max-width: 200px;">
                <button type="button" id="remove-cover-image" class="btn btn-danger btn-sm mt-2">Rimuovi Immagine</button>
            </div>
        @endif

        <button type="submit" class="btn btn-primary mt-3">Aggiorna</button>
        <a href="{{ route('events.index') }}" class="btn btn-secondary mt-3">Annulla</a>
    </form>
@endsection

@section('scripts')
<script>
    document.getElementById('remove-cover-image')?.addEventListener('click', function () {
        if (confirm('Vuoi davvero rimuovere l\'immagine di copertina?')) {
            fetch('{{ route('events.removeCoverImage', $event->id) }}', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Rimuove l'immagine dalla vista
                    document.getElementById('cover-image-container').remove();
                    alert(data.message);
                } else {
                    alert('Errore durante la rimozione dell\'immagine.');
                }
            })
            .catch(err => console.error(err));
        }
    });
</script>
@endsection