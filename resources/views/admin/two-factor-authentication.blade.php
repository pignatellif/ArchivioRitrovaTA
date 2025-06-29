@extends('layouts.admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Verifica a Due Fattori</h2>
        <a href="{{ route('admin.profile') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Torna al Profilo
        </a>
    </div>

    @if(session('status') == 'two-factor-authentication-enabled')
        <div class="alert alert-success">
            <i class="fas fa-check-circle me-2"></i>
            Verifica a due fattori abilitata con successo! Ora devi confermarla inserendo un codice.
        </div>
    @endif

    @if(session('status') == 'two-factor-authentication-confirmed')
        <div class="alert alert-success">
            <i class="fas fa-check-circle me-2"></i>
            Verifica a due fattori confermata e attivata con successo!
        </div>
    @endif

    @if(session('status') == 'two-factor-authentication-disabled')
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle me-2"></i>
            Verifica a due fattori disabilitata.
        </div>
    @endif

    @if(session('status') == 'recovery-codes-generated')
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            Nuovi codici di recupero generati.
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    @if($user->two_factor_secret)
                        {{-- 2FA È ABILITATA --}}
                        @if(is_null($user->two_factor_confirmed_at))
                            {{-- 2FA NON ANCORA CONFERMATA --}}
                            <div class="d-flex align-items-center mb-4">
                                <div class="badge bg-warning me-3 p-2">
                                    <i class="fas fa-shield-alt fa-lg"></i>
                                </div>
                                <div>
                                    <h5 class="mb-1">Verifica a Due Fattori in Attesa di Conferma</h5>
                                    <p class="text-muted mb-0">Scansiona il QR code e conferma con un codice per attivare la 2FA.</p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="mb-3">1. Scansiona il QR Code:</h6>
                                    <div class="text-center mb-3">
                                        {!! $qrCodeSvg !!}
                                    </div>
                                    <p class="small text-muted">
                                        Scansiona questo codice QR con la tua app di autenticazione 
                                        (Google Authenticator, Authy, ecc.).
                                    </p>
                                </div>
                                
                                <div class="col-md-6">
                                    <h6 class="mb-3">2. Conferma l'attivazione:</h6>
                                    <div class="alert alert-warning">
                                        <strong>Importante!</strong> Dopo aver scansionato il QR code, 
                                        inserisci il codice generato dalla tua app per confermare l'attivazione.
                                    </div>
                                    
                                    <form method="POST" action="{{ url('/user/confirmed-two-factor-authentication') }}">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="code" class="form-label">Codice di verifica</label>
                                            <input type="text" 
                                                   name="code" 
                                                   id="code" 
                                                   class="form-control @error('code') is-invalid @enderror" 
                                                   placeholder="123456"
                                                   required 
                                                   autofocus>
                                            @error('code')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-check me-2"></i>
                                            Conferma e Attiva 2FA
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <hr>
                            
                            <form method="POST" action="{{ url('user/two-factor-authentication') }}" onsubmit="return confirm('Sei sicuro di voler annullare l\'attivazione della 2FA?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger">
                                    <i class="fas fa-times me-2"></i>
                                    Annulla Attivazione 2FA
                                </button>
                            </form>

                        @else
                            {{-- 2FA COMPLETAMENTE ATTIVA --}}
                            <div class="d-flex align-items-center mb-4">
                                <div class="badge bg-success me-3 p-2">
                                    <i class="fas fa-shield-alt fa-lg"></i>
                                </div>
                                <div>
                                    <h5 class="mb-1">Verifica a Due Fattori Attiva</h5>
                                    <p class="text-muted mb-0">Il tuo account è protetto dalla verifica a due fattori.</p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="mb-3">QR Code (per backup):</h6>
                                    <div class="text-center mb-3">
                                        {!! $qrCodeSvg !!}
                                    </div>
                                    <p class="small text-muted">
                                        Puoi usare questo QR code per configurare la 2FA su un altro dispositivo.
                                    </p>
                                </div>
                                
                                <div class="col-md-6">
                                    <h6 class="mb-3">Codici di Recupero:</h6>
                                    @if($recoveryCodes)
                                        <div class="bg-light p-3 rounded mb-3">
                                            @foreach($recoveryCodes as $code)
                                                <code class="d-block mb-1">{{ $code }}</code>
                                            @endforeach
                                        </div>
                                        <p class="small text-muted mb-3">
                                            <i class="fas fa-exclamation-triangle text-warning me-1"></i>
                                            Salva questi codici in un posto sicuro. Puoi usarli per accedere se perdi il dispositivo.
                                        </p>
                                    @endif
                                    
                                    <form method="POST" action="{{ url('user/two-factor-recovery-codes') }}" class="mb-3">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-secondary btn-sm">
                                            <i class="fas fa-sync-alt me-2"></i>
                                            Rigenera Codici
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <hr>
                            
                            <form method="POST" action="{{ url('user/two-factor-authentication') }}" onsubmit="return confirm('Sei sicuro di voler disabilitare la verifica a due fattori?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-times me-2"></i>
                                    Disabilita Verifica a Due Fattori
                                </button>
                            </form>
                        @endif

                    @else
                        {{-- 2FA NON È ABILITATA --}}
                        <div class="d-flex align-items-center mb-4">
                            <div class="badge bg-warning me-3 p-2">
                                <i class="fas fa-shield-alt fa-lg"></i>
                            </div>
                            <div>
                                <h5 class="mb-1">Verifica a Due Fattori Non Attiva</h5>
                                <p class="text-muted mb-0">Abilita la verifica a due fattori per una maggiore sicurezza.</p>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <h6 class="alert-heading">
                                <i class="fas fa-info-circle me-2"></i>
                                Perché abilitare la verifica a due fattori?
                            </h6>
                            <p class="mb-0">
                                La verifica a due fattori aggiunge un ulteriore livello di sicurezza al tuo account. 
                                Anche se qualcuno conosce la tua password, non potrà accedere senza il codice dal tuo dispositivo.
                            </p>
                        </div>

                        <form method="POST" action="{{ url('user/two-factor-authentication') }}">
                            @csrf
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-shield-alt me-2"></i>
                                Abilita Verifica a Due Fattori
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="fas fa-mobile-alt me-2"></i>
                        App Consigliate
                    </h6>
                    <div class="list-group list-group-flush">
                        <div class="list-group-item px-0">
                            <strong>Google Authenticator</strong>
                            <p class="small text-muted mb-0">App gratuita di Google per iOS e Android</p>
                        </div>
                        <div class="list-group-item px-0">
                            <strong>Authy</strong>
                            <p class="small text-muted mb-0">App con backup cloud e multi-dispositivo</p>
                        </div>
                        <div class="list-group-item px-0">
                            <strong>Microsoft Authenticator</strong>
                            <p class="small text-muted mb-0">App gratuita di Microsoft</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="fas fa-question-circle me-2"></i>
                        Come Funziona?
                    </h6>
                    <ol class="small">
                        <li>Installa un'app di autenticazione sul tuo telefono</li>
                        <li>Clicca "Abilita Verifica a Due Fattori"</li>
                        <li>Scansiona il codice QR mostrato</li>
                        <li>Inserisci il codice generato per confermare</li>
                        <li>Salva i codici di recupero in un posto sicuro</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@endsection