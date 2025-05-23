@extends('layouts.admin')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Dashboard Admin</h2>
    <div class="row g-4">

        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="bi bi-film fs-1 text-primary mb-2"></i>
                    <h5 class="card-title">Video</h5>
                    <a href="{{ route('videos.index') }}" class="btn btn-primary w-100">Gestisci Video</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="bi bi-collection-play fs-1 text-secondary mb-2"></i>
                    <h5 class="card-title">Serie</h5>
                    <a href="{{ route('series.index') }}" class="btn btn-secondary w-100">Gestisci Serie</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="bi bi-calendar-event fs-1 text-success mb-2"></i>
                    <h5 class="card-title">Eventi</h5>
                    <a href="{{ route('events.index') }}" class="btn btn-success w-100">Gestisci Eventi</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="bi bi-person-lines-fill fs-1 text-warning mb-2"></i>
                    <h5 class="card-title">Autori</h5>
                    <a href="{{ route('authors.index') }}" class="btn btn-warning w-100">Gestisci Autori</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="bi bi-award fs-1 text-info mb-2"></i>
                    <h5 class="card-title">Riconoscimenti</h5>
                    <a href="{{ route('riconoscimenti.index') }}" class="btn btn-info w-100">Gestisci Riconoscimenti</a>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection