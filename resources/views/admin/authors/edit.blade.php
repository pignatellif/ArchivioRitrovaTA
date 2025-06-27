@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="mb-4 d-flex justify-content-between align-items-center">
                <h2 class="mb-0"><i class="fas fa-user-edit me-2 text-primary"></i>Modifica Autore</h2>
                <a href="{{ route('authors.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Torna agli Autori
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

            <form action="{{ route('authors.update', $author->id) }}" method="POST" enctype="multipart/form-data" class="card shadow-sm">
                @csrf
                @method('PUT')
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label for="nome" class="form-label fw-bold">Nome <span class="text-danger">*</span></label>
                        <input type="text" name="nome" id="nome" class="form-control @error('nome') is-invalid @enderror"
                               value="{{ old('nome', $author->nome) }}" required maxlength="255" placeholder="Nome autore">
                        @error('nome')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="anno_nascita" class="form-label fw-bold">Anno di Nascita</label>
                        <input type="number" name="anno_nascita" id="anno_nascita" class="form-control @error('anno_nascita') is-invalid @enderror"
                               value="{{ old('anno_nascita', $author->anno_nascita) }}" min="1800" max="{{ date('Y') + 1 }}" placeholder="es. 1980">
                        @error('anno_nascita')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="biografia" class="form-label fw-bold">Biografia</label>
                        <textarea name="biografia" id="biografia" rows="5" maxlength="2000"
                                  class="form-control @error('biografia') is-invalid @enderror"
                                  placeholder="Breve biografia">{{ old('biografia', $author->biografia) }}</textarea>
                        @error('biografia')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Max 2000 caratteri</div>
                    </div>

                    <div class="mb-3">
                        <label for="formati" class="form-label fw-bold">Formati associati</label>
                        <select name="formati[]" id="formati" class="form-select @error('formati') is-invalid @enderror" multiple>
                            @foreach($formati as $formato)
                                <option value="{{ $formato->id }}"
                                    {{ collect(old('formati', $author->formati->pluck('id')->toArray()))->contains($formato->id) ? 'selected' : '' }}>
                                    {{ $formato->nome }}
                                </option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Tieni premuto <b>CTRL</b> (Windows) o <b>CMD</b> (Mac) per selezionare più formati.</small>
                        @error('formati')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="immagine_profilo" class="form-label fw-bold">Immagine del Profilo</label>
                        <input type="file" name="immagine_profilo" id="immagine_profilo" class="form-control @error('immagine_profilo') is-invalid @enderror" accept="image/*">
                        @error('immagine_profilo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @if($author->immagine_profilo)
                            <div class="mt-3" id="profile-image-preview">
                                <p class="mb-1">Immagine attuale:</p>
                                <img src="{{ asset($author->immagine_profilo) }}" alt="Immagine Profilo" style="width: 120px; height: 120px; object-fit:cover; border-radius:8px;">
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" name="remove_immagine_profilo" id="remove_immagine_profilo" value="1">
                                    <label class="form-check-label" for="remove_immagine_profilo">
                                        Rimuovi immagine attuale
                                    </label>
                                </div>
                            </div>
                        @endif
                        <div class="form-text">Opzionale. JPG, PNG, max 2MB.</div>
                    </div>
                </div>
                <div class="card-footer bg-light text-end">
                    <a href="{{ route('authors.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-1"></i>Annulla
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-1"></i>Salva modifiche
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