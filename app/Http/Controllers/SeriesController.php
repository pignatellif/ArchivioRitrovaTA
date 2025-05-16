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
        $years = Video::select('anno')->distinct()->pluck('anno');
        $formats = Video::select('formato')->distinct()->pluck('formato');
        $families = Video::select('famiglia')->distinct()->pluck('famiglia');
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

    public function edit(Request $request, $id)
    {
        $series = Serie::with('videos')->findOrFail($id);
    
        // Video già nella serie
        $selectedIds = $series->videos->pluck('id');
    
        // Query di base
        $query = Video::with(['autore', 'location', 'tags']);
    
        // Applica i filtri, se presenti
        if ($request->filled('title')) {
            $query->where('titolo', 'like', '%' . $request->title . '%');
        }
    
        if ($request->filled('year')) {
            $query->where('anno', $request->year);
        }
    
        if ($request->filled('format')) {
            $query->where('formato', $request->format);
        }
    
        if ($request->filled('author')) {
            $query->where('autore_id', $request->author);
        }
    
        if ($request->filled('location')) {
            $query->where('location_id', $request->location);
        }
    
        if ($request->filled('family')) {
            $query->where('famiglia', $request->family);
        }
    
        if ($request->filled('tags')) {
            $tagIds = explode(',', $request->tags);
            $query->whereHas('tags', function ($q) use ($tagIds) {
                $q->whereIn('tags.id', $tagIds);
            });
        }        
    
        // Escludi video già nella serie
        $availableVideos = $query->whereNotIn('id', $selectedIds)->get();
    
        // Tutti i dati per i filtri
        $formats = Video::select('formato')->distinct()->pluck('formato');
        $families = Video::select('famiglia')->distinct()->pluck('famiglia');
        $years = Video::select('anno')->distinct()->pluck('anno');
        $authors = Autore::pluck('nome', 'id');
        $locations = Location::pluck('name', 'id');
        $tags = Tag::pluck('nome', 'id');
    
        return view('admin.series.edit', compact(
            'series',
            'availableVideos',
            'formats',
            'families',
            'years',
            'authors',
            'locations',
            'tags'
        ));
    }       

    public function update(Request $request, $id)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'descrizione' => 'nullable|string',
            'selected_videos' => 'nullable|string',
        ]);

        $serie = Serie::findOrFail($id);

        $serie->update([
            'nome' => $request->nome,
            'descrizione' => $request->descrizione,
        ]);

        $videoIds = $request->selected_videos ? explode(',', $request->selected_videos) : [];

        $serie->videos()->sync($videoIds);

        return redirect()->route('series.index')->with('success', 'Serie aggiornata con successo!');
    }

    public function destroy(Serie $series)
    {
        $series->delete();
        return redirect()->route('series.index')->with('success', 'Serie eliminata con successo.');
    }
}
