<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Serie;
use App\Models\Video;
use App\Models\Tag;
use App\Models\Autore;
use App\Models\Location;

class SeriesController extends Controller
{
    public function index()
    {
        $series = Serie::all();
        return view('admin.series.index', compact('series'));
    }

    public function create()
    {
        $videos = Video::with('tags')->get();
    
        // Filtri distinti
        $years = Video::select('anno')->distinct()->orderBy('anno', 'desc')->pluck('anno');
        $formats = Video::select('formato')->distinct()->whereNotNull('formato')->pluck('formato');
        $families = Video::select('famiglia')->distinct()->whereNotNull('famiglia')->pluck('famiglia');
        $authors = Autore::pluck('nome', 'id');
        $locations = Location::pluck('name', 'id');
        $tags = Tag::pluck('nome', 'id');
    
        return view('admin.series.create', compact('videos', 'years', 'formats', 'authors', 'locations', 'families', 'tags'));
    }
       
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'descrizione' => 'nullable|string',
            'videos' => 'array',
        ]);

        $series = Serie::create([
            'nome' => $request->nome,
            'descrizione' => $request->descrizione,
        ]);

        if ($request->has('videos')) {
            $series->videos()->sync($request->videos);
        }

        return redirect()->route('series.index')->with('success', 'Serie creata con successo.');
    }

    public function edit(Request $request, Serie $serie)
    {
        $serie->load('videos');
    
        // Video già associati alla serie
        $selectedVideos = $serie->videos->pluck('id')->toArray();
        
        // Query base per i video disponibili (non ancora associati)
        $availableQuery = Video::whereNotIn('id', $selectedVideos);
        
        // Applica i filtri se presenti
        if ($request->filled('titolo')) {
            $availableQuery->where('titolo', 'LIKE', '%' . $request->titolo . '%');
        }
        
        if ($request->filled('anno')) {
            $availableQuery->where('anno', $request->anno);
        }
        
        if ($request->filled('formato')) {
            $availableQuery->where('formato', $request->formato);
        }
        
        if ($request->filled('famiglia')) {
            $availableQuery->where('famiglia', $request->famiglia);
        }
        
        if ($request->filled('tag')) {
            $availableQuery->whereHas('tags', function($query) use ($request) {
                $query->where('nome', $request->tag);
            });
        }
        
        // Ottieni i video filtrati con i loro tag
        $availableVideos = $availableQuery->with('tags')->orderBy('titolo')->get();
    
        // Dati per i filtri (solo dai video non ancora associati)
        $allAvailableVideos = Video::whereNotIn('id', $selectedVideos);
        
        $anni = $allAvailableVideos->select('anno')
                    ->distinct()
                    ->whereNotNull('anno')
                    ->orderBy('anno', 'desc')
                    ->pluck('anno');
                    
        $formati = $allAvailableVideos->select('formato')
                      ->distinct()
                      ->whereNotNull('formato')
                      ->orderBy('formato')
                      ->pluck('formato');
                      
        $famiglie = $allAvailableVideos->select('famiglia')
                       ->distinct()
                       ->whereNotNull('famiglia')
                       ->orderBy('famiglia')
                       ->pluck('famiglia');
        
        // Per i tag, dobbiamo fare una query più complessa
        $tags = Tag::whereHas('videos', function($query) use ($selectedVideos) {
                    $query->whereNotIn('videos.id', $selectedVideos);
                })
                ->distinct()
                ->orderBy('nome')
                ->pluck('nome');
    
        return view('admin.series.edit', compact(
            'serie',
            'availableVideos',
            'anni',
            'formati',
            'famiglie',
            'tags'
        ));
    }

    public function update(Request $request, Serie $serie)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'descrizione' => 'nullable|string',
            'videos' => 'array',
            'videos.*' => 'exists:videos,id',
        ]);

        // Aggiorna i dati della serie
        $serie->update([
            'nome' => $validated['nome'],
            'descrizione' => $validated['descrizione'] ?? null,
        ]);

        // Aggiorna la relazione many-to-many con i video
        $serie->videos()->sync($validated['videos'] ?? []);

        return redirect()
            ->route('series.edit', $serie)
            ->with('success', 'Serie aggiornata con successo!');
    }

    public function destroy(Serie $series)
    {
        // Rimuovi prima le relazioni con i video
        $series->videos()->detach();
        
        // Elimina la serie
        $series->delete();
        
        return redirect()->route('series.index')->with('success', 'Serie eliminata con successo.');
    }
}