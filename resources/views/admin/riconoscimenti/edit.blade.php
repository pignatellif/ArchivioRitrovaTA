@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-7">

            <div class="mb-4 d-flex justify-content-between align-items-center">
                <h2 class="mb-0"><i class="fas fa-trophy me-2 text-warning"></i>Modifica Riconoscimento</h2>
                <a href="{{ route('riconoscimenti.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Torna all'elenco
                </a>
            </div>

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong><i class="fas fa-exclamation-triangle me-2"></i>Attenzione!</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li class="small">{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Chiudi"></button>
                </div>
            @endif

            <form action="{{ route('riconoscimenti.update', $riconoscimento->id) }}" method="POST" class="card shadow-sm">
                @csrf
                @method('PUT')

                <div class="card-body p-4">
                    <div class="mb-3">
                        <label for="titolo" class="form-label fw-bold">Titolo <span class="text-danger">*</span></label>
                        <input type="text" name="titolo" id="titolo" class="form-control @error('titolo') is-invalid @enderror"
                               value="{{ old('titolo', $riconoscimento->titolo) }}" required maxlength="255">
                        @error('titolo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="fonte" class="form-label fw-bold">Fonte</label>
                        <input type="text" name="fonte" id="fonte" class="form-control @error('fonte') is-invalid @enderror"
                               value="{{ old('fonte', $riconoscimento->fonte) }}" maxlength="255">
                        @error('fonte')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="url" class="form-label fw-bold">URL <span class="text-danger">*</span></label>
                        <input type="url" name="url" id="url" class="form-control @error('url') is-invalid @enderror"
                               value="{{ old('url', $riconoscimento->url) }}" required maxlength="512" placeholder="https://...">
                        @error('url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="data_pubblicazione" class="form-label fw-bold">Data di pubblicazione</label>
                        <input type="date" name="data_pubblicazione" id="data_pubblicazione" class="form-control @error('data_pubblicazione') is-invalid @enderror"
                               value="{{ old('data_pubblicazione', $riconoscimento->data_pubblicazione) }}">
                        @error('data_pubblicazione')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="estratto" class="form-label fw-bold">Estratto</label>
                        <textarea name="estratto" id="estratto" class="form-control @error('estratto') is-invalid @enderror" rows="3" maxlength="1000">{{ old('estratto', $riconoscimento->estratto) }}</textarea>
                        @error('estratto')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Max 1000 caratteri</div>
                    </div>
                </div>
                <div class="card-footer bg-light text-end">
                    <a href="{{ route('riconoscimenti.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-1"></i>Annulla
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-1"></i>Aggiorna
                    </button>
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