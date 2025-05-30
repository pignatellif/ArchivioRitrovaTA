@extends('layouts.admin')

@section('content')
<div class="container mt-5">
    <h2>Modifica Luogo</h2>

    <form method="POST" action="{{ route('locations.update', $location) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Nome</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $location->name) }}" required>
        </div>

        <div class="mb-3">
            <label for="lat" class="form-label">Latitudine</label>
            <input type="text" name="lat" id="lat" class="form-control" value="{{ old('lat', $location->lat) }}">
        </div>

        <div class="mb-3">
            <label for="lon" class="form-label">Longitudine</label>
            <input type="text" name="lon" id="lon" class="form-control" value="{{ old('lon', $location->lon) }}">
        </div>

        <button type="submit" class="btn btn-success">Salva</button>
        <a href="{{ route('locations.index') }}" class="btn btn-secondary">Annulla</a>
    </form>
</div>
@endsection