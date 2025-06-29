@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Verifica a Due Fattori</h4>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-4">
                        Inserisci il codice generato dalla tua app di autenticazione per completare il login.
                    </p>

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ url('/two-factor-challenge') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="code" class="form-label">Codice di Autenticazione</label>
                            <input type="text" 
                                   name="code" 
                                   id="code"
                                   class="form-control @error('code') is-invalid @enderror" 
                                   placeholder="123456"
                                   required 
                                   autofocus
                                   autocomplete="one-time-code">
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                Inserisci il codice a 6 cifre dalla tua app di autenticazione.
                            </div>
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-shield-alt me-2"></i>
                                Verifica e Accedi
                            </button>
                        </div>
                    </form>

                    <hr>

                    <p class="text-muted mb-3">
                        <small>
                            <i class="fas fa-info-circle me-1"></i>
                            Non riesci ad accedere? Usa un codice di recupero:
                        </small>
                    </p>

                    <form method="POST" action="{{ url('/two-factor-challenge') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="recovery_code" class="form-label">Codice di Recupero</label>
                            <input type="text" 
                                   name="recovery_code" 
                                   id="recovery_code"
                                   class="form-control @error('recovery_code') is-invalid @enderror" 
                                   placeholder="xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx">
                            @error('recovery_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                Inserisci uno dei codici di recupero che hai salvato.
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-outline-secondary">
                                <i class="fas fa-key me-2"></i>
                                Usa Codice di Recupero
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-3">
                        <a href="{{ route('login') }}" class="btn btn-link text-muted">
                            <i class="fas fa-arrow-left me-1"></i>
                            Torna al Login
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection