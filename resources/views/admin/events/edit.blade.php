@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="mb-4 d-flex justify-content-between align-items-center">
                <h2 class="mb-0"><i class="fas fa-calendar-alt me-2 text-primary"></i>Modifica Evento</h2>
                <a href="{{ route('events.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Torna agli Eventi
                </a>
            </div>

            @if($errors->any())
                <div class="alert alert-danger">
                    <strong>Attenzione!</strong> Ci sono problemi con i dati inseriti:
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li class="small">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('events.update', $event->id) }}" method="POST" enctype="multipart/form-data" class="card p-4 shadow-sm">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="titolo" class="form-label">Titolo <span class="text-danger">*</span></label>
                    <input type="text" name="titolo" id="titolo" class="form-control @error('titolo') is-invalid @enderror" value="{{ old('titolo', $event->titolo) }}" required>
                    @error('titolo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="descrizione" class="form-label">Descrizione</label>
                    <textarea name="descrizione" id="descrizione" class="form-control @error('descrizione') is-invalid @enderror" rows="4">{{ old('descrizione', $event->descrizione) }}</textarea>
                    @error('descrizione')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="start_date" class="form-label">Data di Inizio <span class="text-danger">*</span></label>
                        <input type="date" name="start_date" id="start_date" class="form-control @error('start_date') is-invalid @enderror"
                               value="{{ old('start_date', $event->start_date ? $event->start_date->format('Y-m-d') : '') }}" required>
                        @error('start_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="end_date" class="form-label">Data di Fine</label>
                        <input type="date" name="end_date" id="end_date" class="form-control @error('end_date') is-invalid @enderror"
                               value="{{ old('end_date', $event->end_date ? $event->end_date->format('Y-m-d') : '') }}">
                        @error('end_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="luogo" class="form-label">Luogo di Svolgimento <span class="text-danger">*</span></label>
                    <input type="text" name="luogo" id="luogo" class="form-control @error('luogo') is-invalid @enderror" value="{{ old('luogo', $event->luogo) }}" required>
                    @error('luogo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="cover_image" class="form-label">Immagine di Copertina</label>
                    <input type="file" name="cover_image" id="cover_image" class="form-control @error('cover_image') is-invalid @enderror" accept="image/*">
                    @error('cover_image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    @if($event->cover_image)
                        <div class="mt-3" id="cover-image-container">
                            <img src="{{ asset($event->cover_image) }}" alt="Cover Image" style="max-width: 220px; border-radius: 8px;">
                            <button type="button" id="remove-cover-image" class="btn btn-outline-danger btn-sm mt-2 ms-2">
                                <i class="fas fa-trash me-1"></i>Rimuovi Immagine
                            </button>
                        </div>
                    @endif
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check me-1"></i>Salva Modifiche
                    </button>
                    <a href="{{ route('events.index') }}" class="btn btn-secondary">Annulla</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
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
@endpush