@extends('layouts.admin')

@section('content')
    <h2>Gestione Riconoscimenti</h2>
    <a href="{{ route('riconoscimenti.create') }}" class="btn btn-primary">Aggiungi Riconoscimento</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table mt-3">
        <thead>
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
            @foreach($riconoscimenti as $riconoscimento)
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
                    <td>
                        <a href="{{ route('riconoscimenti.edit', $riconoscimento->id) }}" class="btn btn-warning btn-sm">Modifica</a>
                        <form action="{{ route('riconoscimenti.destroy', $riconoscimento->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Eliminare questo riconoscimento?')">Elimina</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if(method_exists($riconoscimenti, 'links'))
        <div class="mt-3">
            {{ $riconoscimenti->links() }}
        </div>
    @endif
@endsection