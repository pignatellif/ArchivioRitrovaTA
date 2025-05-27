@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Crea Nuova Serie</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        {{-- Colonna principale --}}
        <div class="col-md-8">
            <form action="{{ route('series.store') }}" method="POST">
                @csrf

                {{-- Dati Serie --}}
                <div class="mb-4">
                    <label class="form-label fw-bold">Nome della serie</label>
                    <input type="text" class="form-control" name="nome" id="nome_input" 
                           value="{{ request('form_nome', old('nome')) }}" required>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Descrizione</label>
                    <textarea class="form-control" name="descrizione" id="descrizione_input" rows="4">{{ request('form_descrizione', old('descrizione')) }}</textarea>
                </div>

                {{-- Video disponibili --}}
                <div class="mb-4">
                    <h5 class="fw-bold mb-3 text-primary">
                        <i class="fa-solid fa-plus-circle"></i> Video disponibili ({{ $availableVideos->count() }})
                    </h5>
                    
                    @if($availableVideos->count() > 0)
                        <div class="border rounded p-2" style="max-height: 500px; overflow-y: auto;">
                            @foreach($availableVideos as $video)
                                @php
                                    $selectedVideos = request('form_videos', []);
                                    $isSelected = in_array($video->id, $selectedVideos);
                                @endphp
                                <label class="list-group-item d-flex align-items-center gap-2 mb-1 border-primary border-opacity-50">
                                    <input type="checkbox" name="videos[]" value="{{ $video->id }}" {{ $isSelected ? 'checked' : '' }}>
                                    <div class="flex-grow-1">
                                        <strong>{{ $video->titolo }}</strong>
                                        <small class="text-muted d-block">
                                            Anno: {{ $video->anno }} | 
                                            Formato: {{ $video->formato->nome ?? 'N/D' }} | 
                                            Famiglie: 
                                            @if($video->famiglie->count())
                                                {{ $video->famiglie->pluck('nome')->join(', ') }}
                                            @else
                                                N/D
                                            @endif
                                            @if($video->autore)
                                                <br>Autore: {{ $video->autore->nome }}
                                            @endif
                                            @if($video->location)
                                                | Luogo: {{ $video->location->name }}
                                            @endif
                                            @if($video->tags->count() > 0)
                                                <br>Tags: 
                                                @foreach($video->tags as $tag)
                                                    <span class="badge bg-secondary me-1">{{ $tag->nome }}</span>
                                                @endforeach
                                            @endif
                                        </small>
                                    </div>
                                    <span class="badge bg-outline-primary">Disponibile</span>
                                </label>
                            @endforeach
                        </div>
                        <small class="text-muted">Seleziona i video da includere nella serie</small>
                    @else
                        <div class="alert alert-warning">
                            <i class="fa-solid fa-exclamation-triangle"></i> 
                            @if(request()->hasAny(['titolo', 'anno', 'formato', 'famiglia', 'tag', 'autore', 'luogo']))
                                Nessun video trovato con i filtri applicati.
                            @else
                                Nessun video disponibile per la creazione della serie.
                            @endif
                        </div>
                    @endif
                </div>

                {{-- Azioni rapide --}}
                @if($availableVideos->count() > 0)
                    <div class="mb-3 d-flex gap-2">
                        <button type="button" class="btn btn-outline-success btn-sm" onclick="selectAllVideos()">
                            <i class="fa-solid fa-check-double"></i> Seleziona tutti
                        </button>
                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="deselectAllVideos()">
                            <i class="fa-solid fa-times"></i> Deseleziona tutti
                        </button>
                    </div>
                @endif

                {{-- Azioni principali --}}
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-success">
                        <i class="fa-solid fa-plus"></i> Crea Serie
                    </button>
                    <a href="{{ route('series.index') }}" class="btn btn-secondary">
                        <i class="fa-solid fa-arrow-left"></i> Torna all'elenco
                    </a>
                </div>
            </form>
        </div>

        {{-- Colonna filtri --}}
        <div class="col-md-4">
            <div class="border rounded p-3 bg-light sticky-top" style="top: 20px;">
                <h5 class="fw-bold mb-3">
                    <i class="fa-solid fa-filter"></i> Filtra video disponibili
                </h5>

                <form method="GET" action="{{ route('series.create') }}" class="row g-3" id="filterForm">
                    <input type="hidden" name="form_nome" value="" id="hidden_nome">
                    <input type="hidden" name="form_descrizione" value="" id="hidden_descrizione">
                    <div id="hidden_videos_container"></div>
                    
                    <div class="col-12">
                        <label class="form-label">Titolo</label>
                        <input type="text" name="titolo" class="form-control" 
                               placeholder="Cerca per titolo..." 
                               value="{{ request('titolo') }}">
                    </div>
                    
                    <div class="col-12">
                        <label class="form-label">Anno</label>
                        <select name="anno" class="form-select">
                            <option value="">Tutti gli anni</option>
                            @foreach($anni as $anno)
                                <option value="{{ $anno }}" {{ request('anno') == $anno ? 'selected' : '' }}>
                                    {{ $anno }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-12">
                        <label class="form-label">Formato</label>
                        <select name="formato" class="form-select">
                            <option value="">Tutti i formati</option>
                            @foreach($formati as $formato)
                                <option value="{{ $formato }}" {{ request('formato') == $formato ? 'selected' : '' }}>
                                    {{ $formato }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-12">
                        <label class="form-label">Famiglia</label>
                        <select name="famiglia" class="form-select">
                            <option value="">Tutte le famiglie</option>
                            @foreach($famiglie as $famiglia)
                                <option value="{{ $famiglia }}" {{ request('famiglia') == $famiglia ? 'selected' : '' }}>
                                    {{ $famiglia }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Autore</label>
                        <select name="autore" class="form-select">
                            <option value="">Tutti gli autori</option>
                            @foreach($autori as $autore)
                                <option value="{{ $autore }}" {{ request('autore') == $autore ? 'selected' : '' }}>
                                    {{ $autore }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Luogo</label>
                        <select name="luogo" class="form-select">
                            <option value="">Tutti i luoghi</option>
                            @foreach($luoghi as $luogo)
                                <option value="{{ $luogo }}" {{ request('luogo') == $luogo ? 'selected' : '' }}>
                                    {{ $luogo }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-12">
                        <label class="form-label">Tag</label>
                        <select name="tag" class="form-select">
                            <option value="">Tutti i tag</option>
                            @foreach($tags as $tag)
                                <option value="{{ $tag }}" {{ request('tag') == $tag ? 'selected' : '' }}>
                                    {{ $tag }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-12 d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-magnifying-glass"></i> Applica filtri
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="resetFilters()">
                            <i class="fa-solid fa-rotate-left"></i> Rimuovi filtri
                        </button>
                    </div>
                </form>

                {{-- Info filtri attivi --}}
                @if(request()->hasAny(['titolo', 'anno', 'formato', 'famiglia', 'tag', 'autore', 'luogo']))
                    <hr>
                    <div class="small text-muted">
                        <strong>Filtri attivi:</strong>
                        <ul class="list-unstyled mt-1">
                            @if(request('titolo'))
                                <li><i class="fa-solid fa-dot-circle text-primary"></i> Titolo: "{{ request('titolo') }}"</li>
                            @endif
                            @if(request('anno'))
                                <li><i class="fa-solid fa-dot-circle text-primary"></i> Anno: {{ request('anno') }}</li>
                            @endif
                            @if(request('formato'))
                                <li><i class="fa-solid fa-dot-circle text-primary"></i> Formato: {{ request('formato') }}</li>
                            @endif
                            @if(request('famiglia'))
                                <li><i class="fa-solid fa-dot-circle text-primary"></i> Famiglia: {{ request('famiglia') }}</li>
                            @endif
                            @if(request('autore'))
                                <li><i class="fa-solid fa-dot-circle text-primary"></i> Autore: {{ request('autore') }}</li>
                            @endif
                            @if(request('luogo'))
                                <li><i class="fa-solid fa-dot-circle text-primary"></i> Luogo: {{ request('luogo') }}</li>
                            @endif
                            @if(request('tag'))
                                <li><i class="fa-solid fa-dot-circle text-primary"></i> Tag: {{ request('tag') }}</li>
                            @endif
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
window.seriesCreateConfig = {
    baseUrl: @json(route('series.create')),
    hasVideos: {{ $availableVideos->count() > 0 ? 'true' : 'false' }}
};
</script>
<script src="{{ asset('js/series-create.js') }}"></script>
@endsection