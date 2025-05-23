@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/fuori-dal-frame.css') }}">
@endpush

@section('content')
<section class="hero-section">
    <div class="container py-5">
        <div class="row align-items-center">
            <div class="col-md-4 text-center mb-4 mb-md-0">
                <img src="{{ asset($autore->immagine_profilo) }}" alt="{{ $autore->nome }}" class="img-fluid rounded shadow">
            </div>
            <div class="col-md-8">
                <h2>{{ $autore->nome }}</h2>
                @if ($autore->anno_nascita)
                    <p><strong>Anno di nascita:</strong> {{ $autore->anno_nascita }}</p>
                @endif

                @if ($autore->bio)
                    <p>{{ $autore->bio }}</p>
                @else
                    <p>Autore di {{ $autore->videos->count() }} video nell’archivio.</p>
                @endif
            </div>
        </div>
    </div>
</section>

<div class="container">
    <h3 class="mb-4">I suoi video</h3>
    @include('partials.grid', ['videos' => $videos])
</div>

@endsection
