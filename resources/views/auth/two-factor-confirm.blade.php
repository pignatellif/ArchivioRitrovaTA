@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Conferma verifica a due fattori</h2>

    <div class="alert alert-info">
        Inserisci il codice generato dalla tua app di autenticazione per completare l'attivazione della 2FA.
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ url('user/two-factor-confirm') }}">
        @csrf
        <div class="mb-3">
            <label for="code" class="form-label">Codice di autenticazione</label>
            <input type="text" name="code" id="code" class="form-control" required autofocus>
        </div>
        <button type="submit" class="btn btn-success">
            Conferma 2FA
        </button>
    </form>
</div>
@endsection