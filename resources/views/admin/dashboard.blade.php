@extends('layouts.admin')

@section('content')
<div class="container mt-5">
    <h2>Dashboard Admin</h2>
    <div class="row">
        <div class="col-md-4">
            <a href="{{ route('videos.index') }}" class="btn btn-primary btn-block">Gestisci Video</a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('series.index') }}" class="btn btn-secondary btn-block">Gestisci Serie</a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('events.index') }}" class="btn btn-success btn-block">Gestisci Eventi</a>
        </div>
    </div>
</div>
@endsection
