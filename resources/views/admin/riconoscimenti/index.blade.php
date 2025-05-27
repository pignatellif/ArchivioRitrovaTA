@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Gestione Riconoscimenti</h2>

    <!-- Messaggi di successo -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Pulsanti di navigazione -->
    <div class="mb-3 d-flex justify-content-between">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Torna alla Dashboard</a>
        <a href="{{ route('riconoscimenti.create') }}" class="btn btn-primary">Aggiungi Riconoscimento</a>
    </div>

    <!-- Tabella con i riconoscimenti -->
    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Titolo</th>
                    <th>Fonte</th>
                    <th>Data di Pubblicazione</th>
                    <th>Link</th>
                    <th>Estratto</th>
                    <th>Azioni</th>
                </tr>
            </thead>
            <tbody>
                @forelse($riconoscimenti as $riconoscimento)
                    <tr>
                        <td>{{ $riconoscimento->titolo }}</td>
                        <td>{{ $riconoscimento->fonte ?? '-' }}</td>
                        <td>{{ $riconoscimento->data_pubblicazione ? \Carbon\Carbon::parse($riconoscimento->data_pubblicazione)->format('d/m/Y') : '-' }}</td>
                        <td>
                            <a href="{{ $riconoscimento->url }}" target="_blank" rel="noopener" class="btn btn-outline-info btn-sm">
                                Vai all'articolo
                            </a>
                        </td>
                        <td>
                            @if($riconoscimento->estratto)
                                {{ \Illuminate\Support\Str::limit($riconoscimento->estratto, 60) }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-nowrap">
                            <a href="{{ route('riconoscimenti.edit', $riconoscimento->id) }}" class="btn btn-warning btn-sm" title="Modifica">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <form action="{{ route('riconoscimenti.destroy', $riconoscimento->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Eliminare questo riconoscimento?')" title="Elimina">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">Nessun riconoscimento presente.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(method_exists($riconoscimenti, 'links'))
        <div class="mt-3">
            {{ $riconoscimenti->links() }}
        </div>
    @endif
</div>
@endsection