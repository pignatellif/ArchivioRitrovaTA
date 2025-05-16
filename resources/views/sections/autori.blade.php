@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/fuori-dal-frame.css') }}">
@endpush

@section('content')
<section class="hero-section">
    <div class="hero-container">
        <h2>Gli Autori dell'Archivio</h2>
        <p>Scopri le menti creative che hanno raccontato storie, catturato emozioni e dato vita ai filmati dell’archivio.</p>
    </div>
</section>

<div class="container py-5">
    <div class="row">
        @forelse($autori as $autore)
            <div class="col-12 col-md-6 col-lg-4 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex flex-column">
                        {{-- Foto autore, se disponibile --}}
                        <div class="mb-3 text-center">
                            <img src="{{ asset('img/autori/placeholder.jpg') }}" alt="Foto di {{ $autore->nome }}"
                                 class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
                        </div>

                        <h5 class="card-title text-center">{{ $autore->nome }}</h5>

                        @if ($autore->anno_nascita)
                            <p class="text-center text-muted">Nato nel {{ $autore->anno_nascita }}</p>
                        @endif

                        <p class="text-center mt-auto">
                            <span class="badge bg-primary">{{ $autore->videos_count }} video</span>
                        </p>

                        <div class="text-center mt-3">
                            <a href="{{ route('autore.show', $autore->id) }}" class="btn btn-outline-primary btn-sm">
                                Scopri i video
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-center">Nessun autore trovato.</p>
        @endforelse
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/fuori-dal-frame.js') }}"></script>
@endsection
