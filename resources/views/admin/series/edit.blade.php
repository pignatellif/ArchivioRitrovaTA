@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Modifica Serie</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        {{-- Colonna principale --}}
        <div class="col-md-8">
            <form action="{{ route('series.update', $serie) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Dati Serie --}}
                <div class="mb-4">
                    <label class="form-label fw-bold">Nome della serie</label>
                    <input type="text" class="form-control" name="nome" id="nome_input" 
                           value="{{ request('form_nome', old('nome', $serie->nome)) }}" required>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Descrizione</label>
                    <textarea class="form-control" name="descrizione" id="descrizione_input" rows="4">{{ request('form_descrizione', old('descrizione', $serie->descrizione)) }}</textarea>
                </div>

                {{-- Video già nella serie --}}
                <div class="mb-4">
                    <h5 class="fw-bold mb-3 text-success">
                        <i class="fa-solid fa-check-circle"></i> Video nella serie ({{ $serie->videos->count() }})
                    </h5>
                    
                    @if($serie->videos->count() > 0)
                        <div class="border rounded p-2 bg-light" style="max-height: 300px; overflow-y: auto;">
                            @foreach($serie->videos as $video)
                                @php
                                    // Controlla se il video è stato selezionato nei filtri
                                    $selectedVideos = request('form_videos', []);
                                    $isSelected = in_array($video->id, $selectedVideos) || (empty($selectedVideos) && !request()->hasAny(['titolo', 'anno', 'formato', 'famiglia', 'tag', 'form_videos']));
                                @endphp
                                <label class="list-group-item d-flex align-items-center gap-2 mb-1 bg-success bg-opacity-10 border-success">
                                    <input type="checkbox" name="videos[]" value="{{ $video->id }}" {{ $isSelected ? 'checked' : '' }}>
                                    <div class="flex-grow-1">
                                        <strong>{{ $video->titolo }}</strong>
                                        <small class="text-muted d-block">
                                            Anno: {{ $video->anno }} | 
                                            Formato: {{ $video->formato ?? 'N/D' }} | 
                                            Famiglia: {{ $video->famiglia ?? 'N/D' }}
                                        </small>
                                    </div>
                                    <span class="badge bg-success">Incluso</span>
                                </label>
                            @endforeach
                        </div>
                        <small class="text-muted">Deseleziona per rimuovere dalla serie</small>
                    @else
                        <div class="alert alert-info">
                            <i class="fa-solid fa-info-circle"></i> Nessun video associato a questa serie.
                        </div>
                    @endif
                </div>

                {{-- Video disponibili --}}
                <div class="mb-4">
                    <h5 class="fw-bold mb-3 text-primary">
                        <i class="fa-solid fa-plus-circle"></i> Video disponibili ({{ $availableVideos->count() }})
                    </h5>
                    
                    @if($availableVideos->count() > 0)
                        <div class="border rounded p-2" style="max-height: 400px; overflow-y: auto;">
                            @foreach($availableVideos as $video)
                                @php
                                    // Controlla se il video è stato selezionato nei filtri
                                    $selectedVideos = request('form_videos', []);
                                    $isSelected = in_array($video->id, $selectedVideos);
                                @endphp
                                <label class="list-group-item d-flex align-items-center gap-2 mb-1 border-primary border-opacity-50">
                                    <input type="checkbox" name="videos[]" value="{{ $video->id }}" {{ $isSelected ? 'checked' : '' }}>
                                    <div class="flex-grow-1">
                                        <strong>{{ $video->titolo }}</strong>
                                        <small class="text-muted d-block">
                                            Anno: {{ $video->anno }} | 
                                            Formato: {{ $video->formato ?? 'N/D' }} | 
                                            Famiglia: {{ $video->famiglia ?? 'N/D' }}
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
                        <small class="text-muted">Seleziona per aggiungere alla serie</small>
                    @else
                        <div class="alert alert-warning">
                            <i class="fa-solid fa-exclamation-triangle"></i> 
                            @if(request()->hasAny(['titolo', 'anno', 'formato', 'famiglia', 'tag']))
                                Nessun video trovato con i filtri applicati.
                            @else
                                Tutti i video sono già inclusi in questa serie.
                            @endif
                        </div>
                    @endif
                </div>

                {{-- Azioni rapide --}}
                <div class="mb-3 d-flex gap-2">
                    <button type="button" class="btn btn-outline-success btn-sm" onclick="selectAllAvailable()">
                        <i class="fa-solid fa-check-double"></i> Seleziona tutti disponibili
                    </button>
                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="deselectAllCurrent()">
                        <i class="fa-solid fa-times"></i> Deseleziona tutti correnti
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="resetSelection()">
                        <i class="fa-solid fa-rotate-left"></i> Reset selezione
                    </button>
                </div>

                {{-- Azioni principali --}}
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-success">
                        <i class="fa-solid fa-floppy-disk"></i> Salva modifiche
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

                <form method="GET" action="{{ route('series.edit', $serie) }}" class="row g-3" id="filterForm">
                    <!-- Campi nascosti per mantenere i dati del form principale -->
                    <input type="hidden" name="form_nome" value="" id="hidden_nome">
                    <input type="hidden" name="form_descrizione" value="" id="hidden_descrizione">
                    <!-- Campo nascosto per mantenere le selezioni video -->
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
                @if(request()->hasAny(['titolo', 'anno', 'formato', 'famiglia', 'tag']))
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
// Funzione per raccogliere tutti i video selezionati
function getSelectedVideos() {
    const checkboxes = document.querySelectorAll('input[name="videos[]"]:checked');
    return Array.from(checkboxes).map(cb => cb.value);
}

// Funzione per aggiornare i campi nascosti con le selezioni video
function updateHiddenVideoFields() {
    const container = document.getElementById('hidden_videos_container');
    if (!container) return;
    
    // Rimuovi tutti i campi nascosti esistenti per i video
    container.innerHTML = '';
    
    // Aggiungi un campo nascosto per ogni video selezionato
    const selectedVideos = getSelectedVideos();
    selectedVideos.forEach(videoId => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'form_videos[]';
        input.value = videoId;
        container.appendChild(input);
    });
}

// Funzione per resettare solo i filtri mantenendo le modifiche del form e le selezioni
function resetFilters() {
    const nomeInput = document.getElementById('nome_input');
    const descrizioneInput = document.getElementById('descrizione_input');
    
    // Valori originali dal database
    const originalNome = @json($serie->nome);
    const originalDescrizione = @json($serie->descrizione ?? '');
    
    // Costruisci l'URL mantenendo i dati del form e le selezioni video
    const params = new URLSearchParams();
    
    if (nomeInput.value !== originalNome) {
        params.append('form_nome', nomeInput.value);
    }
    if (descrizioneInput.value !== originalDescrizione) {
        params.append('form_descrizione', descrizioneInput.value);
    }
    
    // Aggiungi le selezioni video correnti
    const selectedVideos = getSelectedVideos();
    selectedVideos.forEach(videoId => {
        params.append('form_videos[]', videoId);
    });
    
    // Naviga alla pagina senza filtri ma con i dati del form e le selezioni
    const baseUrl = @json(route('series.edit', $serie));
    const finalUrl = params.toString() ? `${baseUrl}?${params.toString()}` : baseUrl;
    window.location.href = finalUrl;
}

// Funzioni JavaScript per gestire le selezioni
function selectAllAvailable() {
    const availableCheckboxes = document.querySelectorAll('input[name="videos[]"]:not(:checked)');
    availableCheckboxes.forEach(checkbox => {
        checkbox.checked = true;
    });
    updateHiddenVideoFields();
}

function deselectAllCurrent() {
    const currentCheckboxes = document.querySelectorAll('.bg-success input[name="videos[]"]');
    currentCheckboxes.forEach(checkbox => {
        checkbox.checked = false;
    });
    updateHiddenVideoFields();
}

function resetSelection() {
    // Ripristina lo stato originale: seleziona solo quelli già nella serie
    const allCheckboxes = document.querySelectorAll('input[name="videos[]"]');
    const currentVideoIds = @json($serie->videos->pluck('id'));
    
    allCheckboxes.forEach(checkbox => {
        checkbox.checked = currentVideoIds.includes(parseInt(checkbox.value));
    });
    updateHiddenVideoFields();
}

// Mantieni i dati del form quando applichi i filtri
document.addEventListener('DOMContentLoaded', function() {
    const nomeInput = document.getElementById('nome_input');
    const descrizioneInput = document.getElementById('descrizione_input');
    const filterForm = document.getElementById('filterForm');
    const hiddenNome = document.getElementById('hidden_nome');
    const hiddenDescrizione = document.getElementById('hidden_descrizione');
    
    // Aggiorna i campi nascosti quando l'utente modifica il form
    function updateHiddenFields() {
        if (nomeInput && hiddenNome) {
            hiddenNome.value = nomeInput.value;
        }
        if (descrizioneInput && hiddenDescrizione) {
            hiddenDescrizione.value = descrizioneInput.value;
        }
        updateHiddenVideoFields();
    }
    
    // Inizializza i campi nascosti con i valori correnti al caricamento
    if (nomeInput && hiddenNome) {
        hiddenNome.value = nomeInput.value;
        nomeInput.addEventListener('input', updateHiddenFields);
    }
    
    if (descrizioneInput && hiddenDescrizione) {
        hiddenDescrizione.value = descrizioneInput.value;
        descrizioneInput.addEventListener('input', updateHiddenFields);
    }
    
    // Inizializza i campi nascosti per i video
    updateHiddenVideoFields();
    
    // Aggiorna i campi nascosti prima di inviare il form dei filtri
    filterForm.addEventListener('submit', function(e) {
        updateHiddenFields();
    });
    
    const checkboxes = document.querySelectorAll('input[name="videos[]"]');
    const originalSelection = @json($serie->videos->pluck('id'));
    
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const videoId = parseInt(this.value);
            const wasOriginallySelected = originalSelection.includes(videoId);
            const isCurrentlySelected = this.checked;
            
            // Evidenzia se c'è stata una modifica
            const listItem = this.closest('.list-group-item');
            if (wasOriginallySelected !== isCurrentlySelected) {
                listItem.classList.add('border-warning', 'bg-warning-subtle');
            } else {
                listItem.classList.remove('border-warning', 'bg-warning-subtle');
            }
            
            // Aggiorna i campi nascosti quando cambia una selezione
            updateHiddenVideoFields();
        });
    });
});
</script>
@endsection