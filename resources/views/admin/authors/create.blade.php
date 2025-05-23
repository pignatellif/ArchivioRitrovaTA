@extends('layouts.admin')

@section('content')
    <h2>Aggiungi Nuovo Autore</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Attenzione!</strong> Correggi gli errori seguenti:<br><br>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('authors.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="nome" class="form-label">Nome</label>
            <input type="text" name="nome" id="nome" class="form-control" value="{{ old('nome') }}" required>
        </div>

        <div class="mb-3">
            <label for="anno_nascita" class="form-label">Anno di Nascita</label>
            <input type="number" name="anno_nascita" id="anno_nascita" class="form-control" value="{{ old('anno_nascita') }}">
        </div>

        <div class="mb-3">
            <label for="biografia" class="form-label">Biografia</label>
            <textarea name="biografia" id="biografia" rows="5" class="form-control">{{ old('biografia') }}</textarea>
        </div>

        <div class="mb-3">
            <label for="immagine_profilo" class="form-label">Immagine del Profilo</label>
            <input type="file" name="immagine_profilo" id="immagine_profilo" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Crea Autore</button>
        <a href="{{ route('authors.index') }}" class="btn btn-secondary">Annulla</a>
    </form>
@endsection
