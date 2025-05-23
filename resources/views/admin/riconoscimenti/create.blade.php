@extends('layouts.admin')

@section('content')
    <h2>Aggiungi Riconoscimento</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('riconoscimenti.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="titolo" class="form-label">Titolo <span class="text-danger">*</span></label>
            <input type="text" name="titolo" id="titolo" class="form-control" value="{{ old('titolo') }}" required>
        </div>

        <div class="mb-3">
            <label for="fonte" class="form-label">Fonte</label>
            <input type="text" name="fonte" id="fonte" class="form-control" value="{{ old('fonte') }}">
        </div>

        <div class="mb-3">
            <label for="url" class="form-label">URL <span class="text-danger">*</span></label>
            <input type="url" name="url" id="url" class="form-control" value="{{ old('url') }}" required>
        </div>

        <div class="mb-3">
            <label for="data_pubblicazione" class="form-label">Data di pubblicazione</label>
            <input type="date" name="data_pubblicazione" id="data_pubblicazione" class="form-control" value="{{ old('data_pubblicazione') }}">
        </div>

        <div class="mb-3">
            <label for="estratto" class="form-label">Estratto</label>
            <textarea name="estratto" id="estratto" class="form-control" rows="3">{{ old('estratto') }}</textarea>
        </div>

        <button type="submit" class="btn btn-success">Salva</button>
        <a href="{{ route('riconoscimenti.index') }}" class="btn btn-secondary">Annulla</a>
    </form>
@endsection