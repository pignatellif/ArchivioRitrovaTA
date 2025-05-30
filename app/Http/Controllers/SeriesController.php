<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Serie;
use App\Models\Video;
use App\Models\Tag;
use App\Models\Autore;
use App\Models\Formato;
use App\Models\Famiglia;
use App\Models\Location;

class SeriesController extends Controller
{
    public function index()
    {
        // Carica anche i video per conteggio
        $series = Serie::with('videos')->get();
        return view('admin.series.index', compact('series'));
    }

    public function create(Request $request)
    {
        $query = Video::query();

        // Applica filtri se presenti
        if ($request->filled('titolo')) {
            $query->where('titolo', 'like', '%' . $request->titolo . '%');
        }
        if ($request->filled('anno')) {
            $query->where('anno', $request->anno);
        }
        if ($request->filled('formato')) {
            $query->whereHas('formato', function($q) use ($request) {
                $q->where('nome', $request->formato);
            });
        }
        if ($request->filled('famiglia')) {
            $query->whereHas('famiglie', function($q) use ($request) {
                $q->where('nome', $request->famiglia);
            });
        }
        if ($request->filled('autore')) {
            $query->whereHas('autore', function($q) use ($request) {
                $q->where('nome', $request->autore);
            });
        }
        if ($request->filled('luogo')) {
            $query->whereHas('location', function($q) use ($request) {
                $q->where('name', $request->luogo);
            });
        }
        if ($request->filled('tag')) {
            $query->whereHas('tags', function($q) use ($request) {
                $q->where('nome', $request->tag);
            });
        }

        return view('admin.series.create', [
            'availableVideos' => $query->with(['autore', 'formato', 'famiglie', 'location', 'tags'])->get(),
            'anni' => Video::distinct()->pluck('anno')->filter()->sort(),
            'formati' => Formato::pluck('nome')->sort(),
            'famiglie' => Famiglia::pluck('nome')->sort(),
            'autori' => Autore::pluck('nome')->sort(),
            'luoghi' => Location::pluck('name')->sort(),
            'tags' => Tag::pluck('nome')->sort(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'descrizione' => 'nullable|string',
            'videos' => 'array',
        ]);

        $serie = Serie::create([
            'nome' => $request->nome,
            'descrizione' => $request->descrizione,
        ]);

        if ($request->has('videos')) {
            $serie->videos()->sync($request->videos);
        }

        return redirect()->route('series.index')->with('success', 'Serie creata con successo.');
    }

    public function edit(Request $request, Serie $serie)
    {
        $serie->load('videos');
        $selectedVideos = $serie->videos->pluck('id')->toArray();
    
        // Query per filtrare i video mostrati
        $availableQuery = Video::whereNotIn('id', $selectedVideos);
    
        // Filtri
        if ($request->filled('titolo')) {
            $availableQuery->where('titolo', 'LIKE', '%' . $request->titolo . '%');
        }
        if ($request->filled('anno')) {
            $availableQuery->where('anno', $request->anno);
        }
        if ($request->filled('formato')) {
            $availableQuery->whereHas('formato', function($q) use ($request) {
                $q->where('nome', $request->formato);
            });
        }
        if ($request->filled('famiglia')) {
            $availableQuery->whereHas('famiglie', function($q) use ($request) {
                $q->where('nome', $request->famiglia);
            });
        }
        if ($request->filled('autore')) {
            $availableQuery->whereHas('autore', function($q) use ($request) {
                $q->where('nome', $request->autore);
            });
        }
        if ($request->filled('luogo')) {
            $availableQuery->whereHas('location', function($q) use ($request) {
                $q->where('name', $request->luogo);
            });
        }
        if ($request->filled('tag')) {
            $availableQuery->whereHas('tags', function($q) use ($request) {
                $q->where('nome', $request->tag);
            });
        }
    
        // Video filtrati (da mostrare all'utente)
        $availableVideos = $availableQuery
            ->with(['autore', 'formato', 'famiglie', 'location', 'tags'])
            ->orderBy('titolo')
            ->get();
    
        // Query base per dati filtri (NON filtrata, serve tutto l’insieme possibile)
        $filterBaseQuery = Video::whereNotIn('id', $selectedVideos);
    
        // Filtri disponibili
        $allWithTags = (clone $filterBaseQuery)->with('tags')->get();
        $tagIds = $allWithTags->pluck('tags')->flatten()->pluck('id')->unique();
        $tags = Tag::whereIn('id', $tagIds)->orderBy('nome')->pluck('nome');
    
        $anni = (clone $filterBaseQuery)
            ->select('anno')
            ->whereNotNull('anno')
            ->distinct()
            ->orderBy('anno', 'desc')
            ->pluck('anno');
    
        $formati = Formato::pluck('nome')->sort();
        $famiglie = Famiglia::pluck('nome')->sort();
        $autori = Autore::pluck('nome')->sort();
        $luoghi = Location::pluck('name')->sort();
    
        return view('admin.series.edit', compact(
            'serie',
            'availableVideos',
            'anni',
            'formati',
            'famiglie',
            'autori',
            'luoghi',
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

        $serie->update([
            'nome' => $validated['nome'],
            'descrizione' => $validated['descrizione'] ?? null,
        ]);

        $serie->videos()->sync($validated['videos'] ?? []);

        return redirect()
            ->route('series.edit', $serie)
            ->with('success', 'Serie aggiornata con successo!');
    }

    public function destroy(Serie $serie)
    {
        try {
            $serie->videos()->detach();
            $deleted = $serie->delete();

            if ($deleted) {
                return redirect()->route('series.index')->with('success', 'Serie eliminata con successo.');
            } else {
                return redirect()->route('series.index')->with('error', 'Errore durante l\'eliminazione della serie.');
            }
        } catch (\Exception $e) {
            \Log::error('Errore eliminazione serie: ' . $e->getMessage());
            return redirect()->route('series.index')->with('error', 'Errore durante l\'eliminazione: ' . $e->getMessage());
        }
    }
}