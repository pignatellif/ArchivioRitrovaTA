@extends('layouts.admin')

@section('content')
<div class="container-fluid mt-4">
    <!-- Header della Dashboard -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1 fw-bold">Dashboard Admin</h2>
                    <p class="text-muted mb-0">Benvenuto nel pannello di controllo</p>
                </div>
                <div class="d-flex align-items-center">
                    <span class="text-muted">{{ now('Europe/Rome')->format('d/m/Y H:i') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiche Rapide -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm bg-gradient" style="background: linear-gradient(135deg, #EEF2F7 0%, #D6E0F0 100%); color: #212529;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1" style="color: #607D8B;">Totale Video</h6>
                            <h3 class="mb-0 fw-bold" style="color: #294772;">{{ $totalVideos ?? '0' }}</h3>
                        </div>
                        <div class="rounded-circle p-3" style="background: rgba(41,71,114,0.10);">
                            <i class="bi bi-film fs-4" style="color: #294772;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm bg-gradient" style="background: linear-gradient(135deg, #FFF6E0 0%, #FFD580 100%); color: #212529;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1" style="color: #B08904;">Totale Serie</h6>
                            <h3 class="mb-0 fw-bold" style="color: #B08904;">{{ $totalSeries ?? '0' }}</h3>
                        </div>
                        <div class="rounded-circle p-3" style="background: rgba(176,137,4,0.10);">
                            <i class="bi bi-collection-play fs-4" style="color: #B08904;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm bg-gradient" style="background: linear-gradient(135deg, #E3FDFD 0%, #CBF1F5 100%); color: #212529;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1" style="color: #11999E;">Eventi Programmati</h6>
                            <h3 class="mb-0 fw-bold" style="color: #11999E;">{{ $totalEvents ?? '0' }}</h3>
                        </div>
                        <div class="rounded-circle p-3" style="background: rgba(17,153,158,0.10);">
                            <i class="bi bi-calendar-event fs-4" style="color: #11999E;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm bg-gradient" style="background: linear-gradient(135deg, #F1FFE7 0%, #D9F8C4 100%); color: #212529;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1" style="color: #79AC78;">Autori Registrati</h6>
                            <h3 class="mb-0 fw-bold" style="color: #79AC78;">{{ $totalAuthors ?? '0' }}</h3>
                        </div>
                        <div class="rounded-circle p-3" style="background: rgba(121,172,120,0.10);">
                            <i class="bi bi-person-lines-fill fs-4" style="color: #79AC78;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Menu di Gestione -->
    <div class="row mb-4">
        <div class="col-12">
            <h4 class="mb-3 fw-semibold">Gestione Contenuti</h4>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-4 col-md-6">
            <div class="card border-0 shadow-sm h-100 management-card">
                <div class="card-body text-center p-4">
                    <div class="icon-wrapper mb-3">
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3 d-inline-flex">
                            <i class="bi bi-film fs-1 text-primary"></i>
                        </div>
                    </div>
                    <h5 class="card-title fw-semibold mb-2">Gestione Video</h5>
                    <p class="text-muted mb-3 small">Carica, modifica e organizza i tuoi video</p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('videos.index') }}" class="btn btn-primary">
                            <i class="bi bi-gear me-2"></i>Gestisci Video
                        </a>
                        <a href="{{ route('videos.create') }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-plus-circle me-2"></i>Nuovo Video
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="card border-0 shadow-sm h-100 management-card">
                <div class="card-body text-center p-4">
                    <div class="icon-wrapper mb-3">
                        <div class="bg-secondary bg-opacity-10 rounded-circle p-3 d-inline-flex">
                            <i class="bi bi-collection-play fs-1 text-secondary"></i>
                        </div>
                    </div>
                    <h5 class="card-title fw-semibold mb-2">Gestione Serie</h5>
                    <p class="text-muted mb-3 small">Crea e gestisci serie di contenuti</p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('series.index') }}" class="btn btn-secondary">
                            <i class="bi bi-gear me-2"></i>Gestisci Serie
                        </a>
                        <a href="{{ route('series.create') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-plus-circle me-2"></i>Nuova Serie
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="card border-0 shadow-sm h-100 management-card">
                <div class="card-body text-center p-4">
                    <div class="icon-wrapper mb-3">
                        <div class="bg-success bg-opacity-10 rounded-circle p-3 d-inline-flex">
                            <i class="bi bi-calendar-event fs-1 text-success"></i>
                        </div>
                    </div>
                    <h5 class="card-title fw-semibold mb-2">Gestione Eventi</h5>
                    <p class="text-muted mb-3 small">Pianifica e organizza eventi speciali</p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('events.index') }}" class="btn btn-success">
                            <i class="bi bi-gear me-2"></i>Gestisci Eventi
                        </a>
                        <a href="{{ route('events.create') }}" class="btn btn-outline-success btn-sm">
                            <i class="bi bi-plus-circle me-2"></i>Nuovo Evento
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="card border-0 shadow-sm h-100 management-card">
                <div class="card-body text-center p-4">
                    <div class="icon-wrapper mb-3">
                        <div class="bg-warning bg-opacity-10 rounded-circle p-3 d-inline-flex">
                            <i class="bi bi-person-lines-fill fs-1 text-warning"></i>
                        </div>
                    </div>
                    <h5 class="card-title fw-semibold mb-2">Gestione Autori</h5>
                    <p class="text-muted mb-3 small">Amministra profili e permessi autori</p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('authors.index') }}" class="btn btn-warning">
                            <i class="bi bi-gear me-2"></i>Gestisci Autori
                        </a>
                        <a href="{{ route('authors.create') }}" class="btn btn-outline-warning btn-sm">
                            <i class="bi bi-plus-circle me-2"></i>Nuovo Autore
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="card border-0 shadow-sm h-100 management-card">
                <div class="card-body text-center p-4">
                    <div class="icon-wrapper mb-3">
                        <div class="bg-info bg-opacity-10 rounded-circle p-3 d-inline-flex">
                            <i class="bi bi-award fs-1 text-info"></i>
                        </div>
                    </div>
                    <h5 class="card-title fw-semibold mb-2">Riconoscimenti</h5>
                    <p class="text-muted mb-3 small">Gestisci premi e riconoscimenti</p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('riconoscimenti.index') }}" class="btn btn-info">
                            <i class="bi bi-gear me-2"></i>Gestisci Riconoscimenti
                        </a>
                        <a href="{{ route('riconoscimenti.create') }}" class="btn btn-outline-info btn-sm">
                            <i class="bi bi-plus-circle me-2"></i>Nuovo Riconoscimento
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Nuove sezioni aggiuntive
        <div class="col-lg-4 col-md-6">
            <div class="card border-0 shadow-sm h-100 management-card">
                <div class="card-body text-center p-4">
                    <div class="icon-wrapper mb-3">
                        <div class="bg-danger bg-opacity-10 rounded-circle p-3 d-inline-flex">
                            <i class="bi bi-bar-chart fs-1 text-danger"></i>
                        </div>
                    </div>
                    <h5 class="card-title fw-semibold mb-2">Analytics</h5>
                    <p class="text-muted mb-3 small">Visualizza statistiche e reports</p>
                    <div class="d-grid gap-2">
                        <a href="#" class="btn btn-danger">
                            <i class="bi bi-graph-up me-2"></i>Visualizza Stats
                        </a>
                        <a href="#" class="btn btn-outline-danger btn-sm">
                            <i class="bi bi-download me-2"></i>Esporta Report
                        </a>
                    </div>
                </div>
            </div>
        </div> -->
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-semibold">Attività Recenti</h5>
                        <!-- <a href="#" class="btn btn-sm btn-outline-primary">Vedi Tutte</a> -->
                    </div>
                </div>
                <div class="card-body">
                    <div class="activity-feed">
                        @forelse ($recentActivities as $activity)
                            <div class="activity-item d-flex align-items-center py-3 border-bottom">
                                <div class="activity-icon bg-{{ $activity['color'] ?? 'primary' }} bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="bi {{ $activity['icon'] ?? 'bi-info-circle' }} text-{{ $activity['color'] ?? 'primary' }}"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="mb-1">{!! $activity['message'] !!}</p>
                                    <small class="text-muted">
                                        {{ $activity['author'] ?? '' }} •
                                        {{ \Carbon\Carbon::parse($activity['created_at'])->diffForHumans() }}
                                    </small>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted py-4">
                                Nessuna attività recente.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.management-card {
    transition: all 0.3s ease;
    border-radius: 12px;
}

.management-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.15) !important;
}

.icon-wrapper {
    transition: transform 0.3s ease;
}

.management-card:hover .icon-wrapper {
    transform: scale(1.1);
}

.activity-item:last-child {
    border-bottom: none !important;
}

.bg-gradient {
    position: relative;
    overflow: hidden;
}

.bg-gradient::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.1);
    transform: translateX(-100%);
    transition: transform 0.6s ease;
}

.bg-gradient:hover::before {
    transform: translateX(100%);
}
</style>
@endsection