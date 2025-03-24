@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/serie.css') }}">
@endpush

@section('content')
    <div class="series-section">
        @include('partials.series_list', ['series' => $series])
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/serie.js') }}"></script>
@endpush