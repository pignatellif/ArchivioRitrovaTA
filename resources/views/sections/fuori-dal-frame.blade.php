@extends('layouts.app')

@section('title', 'Fuori dal Frame')

@section('content')
<div class="max-w-5xl mx-auto p-6">
    <!-- HERO SECTION -->
    <section class="hero-section">
        <div class="hero-content">
            <h1>Fuori dal frame</h1>
            <hr class="border-light w-25 mx-auto my-3">
            <p>"Mini-interviste autentiche ai protagonisti dei filmati dell’archivio. <br>
            Storie personali, ricordi vivi, emozioni sussurrate che completano ciò che la pellicola non dice."</p>
        </div>
    </section>
    
    @if($videoList->isEmpty())
        <p class="text-gray-600">Nessun video disponibile con il tag "fuori dal frame".</p>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($videoList as $video)
                <div class="bg-white rounded-lg shadow p-4">
                    <h2 class="text-xl font-semibold mb-2">{{ $video->titolo }}</h2>
                    <p class="text-sm text-gray-500 mb-2">{{ $video->descrizione }}</p>
                    <div class="aspect-video mb-4">
                        <iframe class="w-full h-full rounded" src="{{ $video->url }}" frameborder="0" allowfullscreen></iframe>
                    </div>
                    <p class="text-sm text-gray-400">Tag: {{ implode(', ', $video->tags) }}</p>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
