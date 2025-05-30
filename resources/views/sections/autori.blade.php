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

<section class="format-authors-section">
    <div class="format-container">
        @forelse($formati as $nomeFormato => $autori)
            @if($autori->isNotEmpty())
                <h3 class="format-name">{{ $nomeFormato }}</h3>
                <div class="author-row">
                    @foreach($autori as $autore)
                        <div class="card author-card">
                            <div class="card-body">
                                {{-- Foto autore --}}
                                <div class="text-center mb-3">
                                    <img src="{{ asset($autore->immagine_profilo) }}" alt="Foto di {{ $autore->nome }}"
                                         class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
                                </div>

                                <h5 class="card-title text-center">{{ $autore->nome }}</h5>

                                <p class="text-center text-muted">{{ $autore->videos->count() }} video nell’archivio.</p>

                                <div class="text-center mt-2">
                                    <a href="{{ route('autore.show', $autore->id) }}" class="btn btn-outline-primary btn-sm">
                                        Scopri i video
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        @empty
            <p class="text-center">Nessun autore trovato.</p>
        @endforelse
    </div>
</section>
@endsection

@push('scripts')
<script src="{{ asset('js/fuori-dal-frame.js') }}"></script>
@endpush