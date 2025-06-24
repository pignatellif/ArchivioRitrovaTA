@extends('layouts.app')

@section('content')
<!-- Hero Section con Layout Diagonale -->
<section class="hero-diagonal" style="background-image: url('{{ asset($evento->cover_image) }}')">
    <div class="event-info">
        <h1 class="event-title">{{ $evento->titolo }}</h1>
        <div class="event-meta">
            @if($evento->start_date || $evento->end_date)
            <div class="meta-item">
                <div class="meta-icon">
                    <i class="fas fa-calendar"></i>
                </div>
                <span>
                    @if($evento->start_date && $evento->end_date)
                        {{ \Carbon\Carbon::parse($evento->start_date)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($evento->end_date)->format('d/m/Y') }}
                    @elseif($evento->start_date)
                        {{ \Carbon\Carbon::parse($evento->start_date)->format('d/m/Y') }}
                    @elseif($evento->end_date)
                        Fino al {{ \Carbon\Carbon::parse($evento->end_date)->format('d/m/Y') }}
                    @endif
                </span>
            </div>
            @endif

            @if($evento->luogo)
            <div class="meta-item">
                <div class="meta-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <span>{{ $evento->luogo }}</span>
            </div>
            @endif

            <!-- Puoi aggiungere altre meta informazioni se disponibili -->
            @if(isset($evento->categoria))
            <div class="meta-item">
                <div class="meta-icon">
                    <i class="fas fa-tag"></i>
                </div>
                <span>{{ $evento->categoria }}</span>
            </div>
            @endif

            @if(isset($evento->tipo) || isset($evento->accesso))
            <div class="meta-item">
                <div class="meta-icon">
                    <i class="fas fa-users"></i>
                </div>
                <span>{{ $evento->tipo ?? $evento->accesso ?? 'Evento Pubblico' }}</span>
            </div>
            @endif
        </div>

        @if($evento->descrizione)
        <p class="event-description">{{ $evento->descrizione }}</p>
        @endif
    </div>
</section>

<div class="container py-4">

    {{-- Blocchi contenuti --}}
    @forelse($contents as $content)
        <div class="event-content-block">
            @if($content->template_type === 'testo')
                <div class="text-content">
                    <!-- Wrapper con classe ql-editor per applicare gli stili Quill -->
                    <div class="ql-editor quill-content-display">
                        {!! $content->content['testo'] ?? '' !!}
                    </div>
                </div>
            @elseif($content->template_type === 'galleria')
                <div class="gallery-content">
                    @php $images = $content->content['immagini'] ?? []; @endphp
                    @if(count($images) > 0)
                        <div id="carousel-{{ $loop->index }}" class="carousel slide gallery-carousel" data-bs-ride="carousel" data-bs-interval="4000">
                            <div class="carousel-inner">
                                @foreach($images as $index => $img)
                                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                        <img src="{{ asset('storage/'.$img) }}" class="d-block w-100 carousel-image" alt="Immagine evento">
                                    </div>
                                @endforeach
                            </div>
                            
                            @if(count($images) > 1)
                                <button class="carousel-control-prev" type="button" data-bs-target="#carousel-{{ $loop->index }}" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Precedente</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carousel-{{ $loop->index }}" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Successivo</span>
                                </button>
                                
                                <!-- Indicatori -->
                                <div class="carousel-indicators">
                                    @foreach($images as $index => $img)
                                        <button type="button" data-bs-target="#carousel-{{ $loop->parent->index }}" data-bs-slide-to="{{ $index }}" 
                                                class="{{ $index === 0 ? 'active' : '' }}" aria-current="{{ $index === 0 ? 'true' : 'false' }}" 
                                                aria-label="Slide {{ $index + 1 }}"></button>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            @elseif($content->template_type === 'video')
                <div class="video-content">
                    @php $url = $content->content['url'] ?? ''; @endphp
                    @if(\Illuminate\Support\Str::startsWith($url, 'eventi/video'))
                        <div class="video-wrapper">
                            <video controls preload="metadata" class="custom-video">
                                <source src="{{ asset('storage/'.$url) }}" type="video/mp4">
                                Il tuo browser non supporta il tag video.
                            </video>
                        </div>
                    @elseif($url)
                        <div class="external-video">
                            <a href="{{ $url }}" target="_blank" class="video-link">
                                <i class="fas fa-play-circle"></i>
                                Guarda il video
                            </a>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    @empty
        <div class="empty-state">
            <div class="empty-content">
                <div class="empty-icon">
                    <i class="fa-solid fa-film"></i>
                </div>
                <h3>Nessun contenuto disponibile per questo evento</h3>
                <p>Siamo al lavoro per arricchire questa pagina.<br> Torna presto!</p>
            </div>
        </div>
    @endforelse
</div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/eventi-show.css') }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<!-- Quill CSS per la visualizzazione del contenuto formattato -->
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">

<style>
/* Stili personalizzati per la visualizzazione del contenuto Quill */
.quill-content-display {
    border: none !important;
    padding: 0 !important;
    background: transparent !important;
}

.quill-content-display.ql-editor {
    font-size: 16px;
    line-height: 1.6;
    color: #333;
    font-family: inherit;
}

/* Personalizza gli stili delle intestazioni per il tuo design */
.quill-content-display h1 {
    font-size: 2.5rem;
    font-weight: bold;
    margin: 1.5rem 0 1rem 0;
    color: #2c3e50;
}

.quill-content-display h2 {
    font-size: 2rem;
    font-weight: bold;
    margin: 1.3rem 0 0.8rem 0;
    color: #34495e;
}

.quill-content-display h3 {
    font-size: 1.5rem;
    font-weight: 600;
    margin: 1.2rem 0 0.6rem 0;
    color: #5d6d7e;
}

/* Stili per le liste */
.quill-content-display ul, .quill-content-display ol {
    margin: 1rem 0;
    padding-left: 1.5rem;
}

.quill-content-display li {
    margin: 0.5rem 0;
}

/* Stili per le citazioni */
.quill-content-display blockquote {
    border-left: 4px solid #3498db;
    margin: 1.5rem 0;
    padding: 1rem 1.5rem;
    background-color: #f8f9fa;
    font-style: italic;
}

/* Stili per i link */
.quill-content-display a {
    color: #3498db;
    text-decoration: none;
    border-bottom: 1px solid transparent;
    transition: border-color 0.3s ease;
}

.quill-content-display a:hover {
    border-bottom-color: #3498db;
}

/* Stili per il codice */
.quill-content-display code {
    background-color: #f1f2f6;
    padding: 0.2rem 0.4rem;
    border-radius: 3px;
    font-family: 'Monaco', 'Consolas', monospace;
    font-size: 0.9em;
}

.quill-content-display pre {
    background-color: #2f3542;
    color: #f1f2f6;
    padding: 1rem;
    border-radius: 5px;
    overflow-x: auto;
    margin: 1rem 0;
}

/* Stili per il testo colorato */
.quill-content-display .ql-color-red { color: #e74c3c; }
.quill-content-display .ql-color-orange { color: #f39c12; }
.quill-content-display .ql-color-yellow { color: #f1c40f; }
.quill-content-display .ql-color-green { color: #27ae60; }
.quill-content-display .ql-color-blue { color: #3498db; }
.quill-content-display .ql-color-purple { color: #9b59b6; }

/* Stili per gli sfondi colorati */
.quill-content-display .ql-bg-red { background-color: #ffebee; }
.quill-content-display .ql-bg-orange { background-color: #fff3e0; }
.quill-content-display .ql-bg-yellow { background-color: #fffde7; }
.quill-content-display .ql-bg-green { background-color: #e8f5e8; }
.quill-content-display .ql-bg-blue { background-color: #e3f2fd; }
.quill-content-display .ql-bg-purple { background-color: #f3e5f5; }

/* Stili per le diverse dimensioni del font */
.quill-content-display .ql-size-small { font-size: 0.8em; }
.quill-content-display .ql-size-large { font-size: 1.3em; }
.quill-content-display .ql-size-huge { font-size: 1.8em; }

/* Stili per l'allineamento */
.quill-content-display .ql-align-center { text-align: center; }
.quill-content-display .ql-align-right { text-align: right; }
.quill-content-display .ql-align-justify { text-align: justify; }

/* Stili per i diversi font */
.quill-content-display .ql-font-serif { font-family: Georgia, Times, serif; }
.quill-content-display .ql-font-monospace { font-family: Monaco, Courier, monospace; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endpush