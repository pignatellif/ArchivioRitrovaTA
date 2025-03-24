<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Series;
use App\Models\Video;

class SeriesController extends Controller
{
    public function index()
    {
        $series = Series::all();
        return view('admin.series.index', compact('series'));
    }

    public function create()
    {
        $videos = Video::all();
    
        // Recupero valori distinti per i filtri
        $years = Video::select('year')->distinct()->pluck('year');
        $formats = Video::select('format')->distinct()->pluck('format');
        $authors = Video::select('author')->distinct()->pluck('author');
        $locations = Video::select('location')->distinct()->pluck('location');
    
        return view('admin.series.create', compact('videos', 'years', 'formats', 'authors', 'locations'));
    }    

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'videos' => 'array',
        ]);

        $series = Series::create([
            'name' => $request->name,
            'description' => $request->description,
            'duration' => 0, 
        ]);

        if ($request->has('videos')) {
            $series->videos()->sync($request->videos);
            $series->update(['duration' => Video::whereIn('id', $request->videos)->sum('duration')]);
        }

        return redirect()->route('series.index')->with('success', 'Serie creata con successo.');
    }
    
    public function edit($id)
    {
        $series = Series::with('videos')->findOrFail($id);
        $allVideos = Video::all(); 
        
        // Escludi i video già associati alla serie
        $availableVideos = $allVideos->whereNotIn('id', $series->videos->pluck('id'));
    
        return view('admin.series.edit', compact('series', 'availableVideos', 'allVideos'));
    }
    
    public function update(Request $request, $id)
    {
        // Validazione
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'selected_videos' => 'nullable|string', // Deve essere una stringa con ID separati da virgola
        ]);
    
        // Trova la serie
        $serie = Series::findOrFail($id);
    
        // Aggiorna i dati della serie
        $serie->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);
    
        // Recupera gli ID dei video
        $videoIds = $request->selected_videos ? explode(',', $request->selected_videos) : [];
    
        // Sincronizza i video
        $serie->videos()->sync($videoIds);
    
        return redirect()->route('series.index')->with('success', 'Serie aggiornata con successo!');
    }
    
    public function destroy(Series $series)
    {
        $series->delete();
        return redirect()->route('series.index')->with('success', 'Serie eliminata con successo.');
    }
}
