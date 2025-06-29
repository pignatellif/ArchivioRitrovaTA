@extends('layouts.admin')

@section('content')
    <h2>Profilo Amministratore</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title mb-3"><i class="fa fa-user"></i> Dati Profilo</h5>
            <p><strong>Email:</strong> {{ $user->email }}</p>
        </div>
    </div>

    <div class="mb-3 d-flex gap-2">
        <button class="btn btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#changeEmailForm" aria-expanded="false" aria-controls="changeEmailForm">
            Cambia Email
        </button>
        <button class="btn btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#changePasswordForm" aria-expanded="false" aria-controls="changePasswordForm">
            Cambia Password
        </button>
    </div>

    <a href="{{ route('admin.twofactor') }}" class="btn btn-outline-primary">Gestisci verifica a due fattori</a>
    
    {{-- Cambia Email --}}
    <div class="collapse mb-4" id="changeEmailForm">
        <div class="card card-body">
            <form method="POST" action="{{ route('admin.profile.updateEmail') }}">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Nuova Email</label>
                    <input type="email" name="email" class="form-control" id="email" value="{{ old('email', $user->email) }}" required>
                    @error('email')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Aggiorna Email</button>
            </form>
        </div>
    </div>

    {{-- Cambia Password --}}
    <div class="collapse" id="changePasswordForm">
        <div class="card card-body">
            <form method="POST" action="{{ route('admin.profile.updatePassword') }}">
                @csrf
                <div class="mb-3">
                    <label for="password" class="form-label">Nuova Password</label>
                    <input type="password" name="password" class="form-control" id="password" required>
                    @error('password')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Conferma Password</label>
                    <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" required>
                </div>
                <button type="submit" class="btn btn-primary">Aggiorna Password</button>
            </form>
        </div>
    </div>
@endsection