@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Conferma Azione Sicura</h4>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-4">
                        Per motivi di sicurezza, per favore conferma la tua password per continuare.
                    </p>

                    <form method="POST" action="{{ route('password.confirm') }}"> {{-- Usa la rotta standard di Fortify --}}
                        @csrf

                        {{-- CAMPO PASSWORD (esistente) --}}
                        <div class="mb-3">
                            <label for="password" class="form-label">{{ __('Password') }}</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" autofocus>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- NUOVO CAMPO 2FA --}}
                        @if (auth()->user()->two_factor_secret)
                            <div class="mb-3">
                                <label for="code" class="form-label">Codice di Autenticazione (2FA)</label>
                                <input type="text"
                                       name="code"
                                       id="code"
                                       class="form-control @error('code') is-invalid @enderror"
                                       placeholder="123456"
                                       autocomplete="one-time-code">
                                @error('code')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <div class="form-text">
                                    Obbligatorio se la verifica a due fattori è attiva.
                                </div>
                            </div>
                        @endif
                        {{-- FINE NUOVO CAMPO 2FA --}}

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-lock me-2"></i>
                                Conferma
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection