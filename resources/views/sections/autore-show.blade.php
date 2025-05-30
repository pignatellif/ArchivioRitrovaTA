@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/autore.css') }}">
@endpush

@section('content')
<section class="hero-section">
    <div class="container">
        <div class="row">
            <div class="image-container">
                <img src="{{ asset($autore->immagine_profilo) }}" alt="{{ $autore->nome }}" class="profile-image">
            </div>
            <div class="text-container">
                <h2>{{ $autore->nome }}</h2>
                @if ($autore->anno_nascita)
                    <p><strong>Anno di nascita:</strong> {{ $autore->anno_nascita }}</p>
                @endif

                @if ($autore->biografia)
                    <p>{{ $autore->biografia }}</p>
                @endif
            </div>
        </div>
    </div>
</section>

<section id="dynamicViewSection" class="dynamic-view-section">
    <h3 class="video-container">I suoi video</h3>
    @include('partials.grid', ['videos' => $videos])
</section>

<section class="spacer-section">
    <div class="container">
        <!-- Spazio per separare il contenuto dal footer -->
    </div>
</section>

@endsection
