@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">

            <div class="mb-4 d-flex justify-content-between align-items-center">
                <h2 class="mb-0"><i class="fas fa-trophy me-2 text-warning"></i>Gestione Riconoscimenti</h2>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Dashboard
                    </a>
                    <a href="{{ route('riconoscimenti.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Aggiungi Riconoscimento
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-list me-2"></i>Elenco Riconoscimenti</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
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
                                        <td class="fw-bold">{{ $riconoscimento->titolo }}</td>
                                        <td>{{ $riconoscimento->fonte ?? '-' }}</td>
                                        <td>{{ $riconoscimento->data_pubblicazione ? \Carbon\Carbon::parse($riconoscimento->data_pubblicazione)->format('d/m/Y') : '-' }}</td>
                                        <td>
                                            @if($riconoscimento->url)
                                            <a href="{{ $riconoscimento->url }}" target="_blank" rel="noopener" class="btn btn-outline-info btn-sm" title="Vai all'articolo">
                                                <i class="fas fa-external-link-alt me-1"></i>Articolo
                                            </a>
                                            @else
                                            <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($riconoscimento->estratto)
                                                {{ \Illuminate\Support\Str::limit($riconoscimento->estratto, 60) }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="text-nowrap">
                                            <a href="{{ route('riconoscimenti.edit', $riconoscimento->id) }}" class="btn btn-outline-warning btn-sm" title="Modifica">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('riconoscimenti.destroy', $riconoscimento->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Eliminare questo riconoscimento?')" title="Elimina">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="fas fa-trophy fa-3x mb-3"></i>
                                            <h5>Nessun riconoscimento presente.</h5>
                                            <a href="{{ route('riconoscimenti.create') }}" class="btn btn-primary mt-2">
                                                <i class="fas fa-plus me-2"></i>Aggiungi Riconoscimento
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if(method_exists($riconoscimenti, 'links'))
                    <div class="card-footer">
                        <div class="d-flex justify-content-end">
                            {{ $riconoscimenti->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card { border-radius: 0.75rem; }
    .table th { font-weight: 600; font-size: 0.92rem; text-transform: uppercase; letter-spacing: 0.5px; }
    .table-hover tbody tr:hover { background-color: rgba(255, 221, 77, 0.09); }
    .btn { border-radius: 0.5rem; }
    .alert { border-radius: 0.6rem; border: none; }
</style>
@endpush