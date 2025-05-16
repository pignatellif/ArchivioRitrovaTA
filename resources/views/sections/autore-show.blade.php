@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/fuori-dal-frame.css') }}">
@endpush

@section('content')
<section class="hero-section">
    <div class="container py-5">
        <div class="row align-items-center">
            <div class="col-md-4 text-center mb-4 mb-md-0">
                <img src="{{ asset('storage/autori/' . $autore->immagine) }}" alt="{{ $autore->nome }}" class="img-fluid rounded shadow">
            </div>
            <div class="col-md-8">
                <h2>{{ $autore->nome }}</h2>
                @if ($autore->anno_nascita)
                    <p><strong>Anno di nascita:</strong> {{ $autore->anno_nascita }}</p>
                @endif

                @if ($autore->bio)
                    <p>{{ $autore->bio }}</p>
                @else
                    <p>Autore di {{ $autore->videos->count() }} {{ Str::plural('video', $autore->videos->count()) }} nell’archivio.</p>
                @endif
            </div>
        </div>
    </div>
</section>


<div class="container py-5">
    <h3 class="mb-4">I suoi video</h3>

    <div class="row">
        @forelse($autore->videos as $video)
            <div class="col-12 col-md-6 col-lg-4 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $video->titolo }}</h5>
                        <p class="card-text">
                            <strong>Anno:</strong> {{ $video->anno ?? 'N/D' }}<br>
                            <strong>Formato:</strong> {{ $video->formato ?? 'N/D' }}<br>
                            <strong>Luogo:</strong> {{ $video->location?->name ?? 'N/D' }}<br>
                            <strong>Famiglia:</strong> {{ $video->famiglia ?? 'N/D' }}
                        </p>

                        <div class="mt-auto">
                            <p class="mb-1"><strong>Tag:</strong></p>
                            @foreach($video->tags as $tag)
                                <span class="badge bg-secondary me-1">{{ $tag->nome }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-center">Nessun video associato a questo autore.</p>
        @endforelse
    </div>
</div>
@endsection
