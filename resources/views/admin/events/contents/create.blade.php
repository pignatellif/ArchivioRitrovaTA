@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h1>Gestisci contenuti per: {{ $event->titolo ?? $event->nome ?? $event->id }}</h1>
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>

    <div class="row">
        <!-- Form aggiunta contenuto -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3>Aggiungi nuovo contenuto</h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('events.contents.store', $event) }}" enctype="multipart/form-data" id="add-content-form">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="template_type">Template</label>
                            <select name="template_type" id="template_type" class="form-control" onchange="showFields()">
                                <option value="testo">Testo</option>
                                <option value="galleria">Galleria</option>
                                <option value="video">Video</option>
                            </select>
                        </div>

                        <div id="fields_testo" class="template_fields">
                            <div class="form-group mb-3">
                                <label for="content_testo">Testo</label>
                                <!-- Hidden textarea per Quill -->
                                <textarea name="content[testo]" id="content_testo_hidden" style="display:none;"></textarea>
                                <!-- Div per Quill editor -->
                                <div id="content_testo" style="height: 200px;"></div>
                            </div>
                        </div>

                        <div id="fields_galleria" class="template_fields" style="display:none;">
                            <div class="form-group mb-3">
                                <label for="content_immagini">Immagini (puoi selezionare più file)</label>
                                <input type="file" name="immagini[]" id="content_immagini" class="form-control" multiple accept="image/*">
                                <small class="text-muted">Puoi caricare più immagini contemporaneamente.</small>
                            </div>
                        </div>

                        <div id="fields_video" class="template_fields" style="display:none;">
                            <div class="form-group mb-3">
                                <label for="content_video">Carica Video</label>
                                <input type="file" name="video" id="content_video" class="form-control" accept="video/*">
                                <small class="text-muted">Oppure inserisci un URL video qui sotto.</small>
                            </div>
                            <div class="form-group mb-3">
                                <label for="content_url">URL Video</label>
                                <input type="text" name="content[url]" id="content_url" class="form-control">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Salva nuovo contenuto</button>
                    </form>
                    <div class="mt-3">
                        <a href="{{ route('events.index') }}" class="btn btn-secondary">Torna indietro</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sezione per modificare contenuti esistenti -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>Contenuti esistenti</h3>
                    @if($contents->count() > 1)
                        <div>
                            <span class="badge badge-info">
                                <i class="fas fa-arrows-alt"></i> Trascina per riordinare
                            </span>
                        </div>
                    @endif
                </div>
                <div class="card-body">
                    @if($contents->count() > 0)
                        <div id="sortable-contents" data-reorder-url="{{ route('events.contents.reorder', $event) }}">
                            @foreach($contents->sortBy('order') as $content)
                                <div class="card mb-3 sortable-item" data-content-id="{{ $content->id }}">
                                    <div class="card-header d-flex justify-content-between align-items-center drag-handle">
                                        <span>
                                            <i class="fas fa-grip-vertical text-muted me-2 drag-icon" title="Trascina per riordinare"></i>
                                            <strong>{{ ucfirst($content->template_type) }}</strong>
                                            <small class="text-muted">(Posizione: <span class="position-number">{{ $content->order ?? $loop->iteration }}</span>)</small>
                                        </span>
                                        <div>
                                            <button class="btn btn-sm btn-warning" onclick="editContent({{ $content->id }})">
                                                <i class="fas fa-edit"></i> Modifica
                                            </button>
                                            <form method="POST" action="{{ route('events.contents.destroy', [$event, $content]) }}" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Sei sicuro di voler eliminare questo contenuto?')">
                                                    <i class="fas fa-trash"></i> Elimina
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <!-- Mostra anteprima del contenuto -->
                                        @if($content->template_type === 'testo')
                                            <div class="content-preview">{!! \Illuminate\Support\Str::limit(strip_tags($content->content['testo'] ?? ''), 100) !!}</div>
                                        @elseif($content->template_type === 'galleria')
                                            <strong>Immagini caricate:</strong>
                                            <div class="row">
                                                @foreach((array) ($content->content['immagini'] ?? []) as $img)
                                                    <div class="col-4 mb-2">
                                                        <img src="{{ asset('storage/'.$img) }}" class="img-fluid" style="max-height:100px;">
                                                    </div>
                                                @endforeach
                                            </div>
                                        @elseif($content->template_type === 'video')
                                            @php $url = $content->content['url'] ?? ''; @endphp
                                            @if(\Illuminate\Support\Str::startsWith($url, 'eventi/video'))
                                                <video controls width="100%">
                                                    <source src="{{ asset('storage/'.$url) }}">
                                                    Il tuo browser non supporta il tag video.
                                                </video>
                                            @elseif($url)
                                                <a href="{{ $url }}" target="_blank">{{ $url }}</a>
                                            @endif
                                        @endif

                                        <!-- Form di modifica (nascosto inizialmente) -->
                                        <div id="edit-form-{{ $content->id }}" style="display: none;">
                                            <form method="POST" action="{{ route('events.contents.update', [$event, $content]) }}" enctype="multipart/form-data" class="edit-content-form" data-content-id="{{ $content->id }}">
                                                @csrf
                                                @method('PUT')

                                                <div class="form-group mb-3">
                                                    <label>Template</label>
                                                    <select name="template_type" class="form-control" onchange="showEditFields({{ $content->id }}, this.value)">
                                                        <option value="testo" {{ $content->template_type === 'testo' ? 'selected' : '' }}>Testo</option>
                                                        <option value="galleria" {{ $content->template_type === 'galleria' ? 'selected' : '' }}>Galleria</option>
                                                        <option value="video" {{ $content->template_type === 'video' ? 'selected' : '' }}>Video</option>
                                                    </select>
                                                </div>

                                                <div id="edit-fields-testo-{{ $content->id }}" class="edit-template-fields" style="{{ $content->template_type === 'testo' ? '' : 'display:none;' }}">
                                                    <div class="form-group mb-3">
                                                        <label>Testo</label>
                                                        <!-- Hidden textarea per Quill -->
                                                        <textarea name="content[testo]" id="edit_content_testo_hidden_{{ $content->id }}" style="display:none;">{{ $content->content['testo'] ?? '' }}</textarea>
                                                        <!-- Div per Quill editor -->
                                                        <div id="edit_content_testo_{{ $content->id }}" style="height: 200px;">{!! $content->content['testo'] ?? '' !!}</div>
                                                    </div>
                                                </div>

                                                <div id="edit-fields-galleria-{{ $content->id }}" class="edit-template-fields" style="{{ $content->template_type === 'galleria' ? '' : 'display:none;' }}">
                                                    <div class="form-group mb-3">
                                                        <label>Immagini (aggiungi altre immagini)</label>
                                                        <input type="file" name="immagini[]" class="form-control" multiple accept="image/*">
                                                        <small class="text-muted">Seleziona nuove immagini per aggiungerle alla galleria.</small>
                                                    </div>
                                                </div>

                                                <div id="edit-fields-video-{{ $content->id }}" class="edit-template-fields" style="{{ $content->template_type === 'video' ? '' : 'display:none;' }}">
                                                    <div class="form-group mb-3">
                                                        <label>Carica nuovo Video</label>
                                                        <input type="file" name="video" class="form-control" accept="video/*">
                                                        <small class="text-muted">Lascia vuoto se non vuoi cambiare video.</small>
                                                    </div>
                                                    <div class="form-group mb-3">
                                                        <label>URL Video</label>
                                                        <input type="text" name="content[url]" class="form-control" value="{{ $content->content['url'] ?? '' }}">
                                                    </div>
                                                </div>

                                                <div class="mt-3">
                                                    <button type="submit" class="btn btn-success">
                                                        <i class="fas fa-save"></i> Salva modifiche
                                                    </button>
                                                    <button type="button" class="btn btn-secondary" onclick="cancelEdit({{ $content->id }})">
                                                        <i class="fas fa-times"></i> Annulla
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Nessun contenuto presente per questo evento.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quill CSS -->
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<!-- SortableJS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
<!-- Font Awesome per le icone -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<!-- Quill JS -->
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>

<!-- Custom JS e CSS -->
<link rel="stylesheet" href="{{ asset('css/admin-event-contents.css') }}">
<script src="{{ asset('js/admin-event-contents.js') }}"></script>
@endsection