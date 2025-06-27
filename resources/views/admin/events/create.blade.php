@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="mb-4 d-flex justify-content-between align-items-center">
                <h2 class="mb-0"><i class="fas fa-calendar-plus me-2 text-primary"></i>Aggiungi Evento</h2>
                <a href="{{ route('events.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Torna agli Eventi
                </a>
            </div>

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong><i class="fas fa-exclamation-triangle me-2"></i>Attenzione!</strong> Correggi i seguenti errori:
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li class="small">{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Chiudi"></button>
                </div>
            @endif

            <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data" class="card shadow-sm">
                @csrf
                <div class="card-body p-4">
                    <!-- Titolo -->
                    <div class="mb-3">
                        <label for="titolo" class="form-label fw-bold">Titolo <span class="text-danger">*</span></label>
                        <input type="text" name="titolo" id="titolo" class="form-control @error('titolo') is-invalid @enderror" value="{{ old('titolo') }}" required maxlength="255" placeholder="Titolo evento">
                        @error('titolo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Descrizione -->
                    <div class="mb-3">
                        <label for="descrizione" class="form-label fw-bold">Descrizione</label>
                        <textarea name="descrizione" id="descrizione" class="form-control @error('descrizione') is-invalid @enderror" rows="4" maxlength="1000" placeholder="Descrizione dettagliata dell'evento">{{ old('descrizione') }}</textarea>
                        @error('descrizione')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Date -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="start_date" class="form-label fw-bold">Data di Inizio <span class="text-danger">*</span></label>
                            <input type="date" name="start_date" id="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date') }}" required>
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="end_date" class="form-label fw-bold">Data di Fine</label>
                            <input type="date" name="end_date" id="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date') }}">
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Luogo -->
                    <div class="mb-3">
                        <label for="luogo" class="form-label fw-bold">Luogo di Svolgimento <span class="text-danger">*</span></label>
                        <input type="text" name="luogo" id="luogo" class="form-control @error('luogo') is-invalid @enderror" value="{{ old('luogo') }}" required maxlength="255" placeholder="Città, struttura, etc.">
                        @error('luogo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Copertina -->
                    <div class="mb-3">
                        <label for="cover_image" class="form-label fw-bold">Immagine di Copertina</label>
                        <input type="file" name="cover_image" id="cover_image" class="form-control @error('cover_image') is-invalid @enderror" accept="image/*">
                        @error('cover_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Opzionale. JPG, PNG, max 2MB.</div>
                    </div>
                </div>

                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('events.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i>Annulla
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Salva Evento
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card { border-radius: 0.75rem; }
    .form-label.fw-bold { color: #495057; }
    .form-control:focus { border-color: #86b7fe; box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.09);}
    .alert { border-radius: 0.6rem; border: none; }
    .btn { border-radius: 0.5rem; font-weight: 500; }
    .card-footer { border-radius: 0 0 0.75rem 0.75rem; }
</style>
@endpush