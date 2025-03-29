@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/events.css') }}">
@endpush

@section('content')
<div class="container-fluid mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Cerca Eventi</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('eventi') }}" method="GET">
                        <div class="input-group">
                            <input type="text" class="form-control" name="query" placeholder="Cerca per nome, data o luogo" value="{{ request('query') }}">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">Cerca</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4 view-list">
        @foreach($events as $event)
            <div class="col-md-4">
                <div class="card mb-4">
                    @if($event->cover_image)
                        <img src="{{ asset($event->cover_image) }}" class="card-img-top" alt="Copertina di {{ $event->title }}">
                    @else
                        <img src="{{ asset('images/default-cover.jpg') }}" class="card-img-top" alt="Copertina di default">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $event->title }}</h5>
                        <p class="card-text">{{ $event->description }}</p>
                        <p class="card-text"><small class="text-muted">{{ $event->date }}</small></p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row justify-content-center view-list">
        {{ $events->appends(request()->input())->links() }}
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/events.js') }}"></script>
@endpush