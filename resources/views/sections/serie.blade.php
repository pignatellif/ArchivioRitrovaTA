@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/serie.css') }}">
@endpush

@section('content')
    <!-- HERO SECTION -->
    <section class="hero-section">
        <div class="hero-content">
            <h2>Le nostre serie</h2>
            <hr class="border-light w-25 mx-auto my-3">
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. <br> Architecto amet magnam fugiat repellat rerum quis vero earum, sed optio modi saepe expedita culpa? Fuga quaerat fugit repellat, dolor illum cumque?</p>
        </div>
    </section>

    <div class="series-section">
        @include('partials.series_list', ['series' => $series])
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/serie.js') }}"></script>
@endpush