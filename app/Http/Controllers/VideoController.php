<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\Autore;
use App\Models\Tag;
use App\Models\Location;
use App\Models\Famiglia;
use App\Models\Formato;
use App\Models\Serie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class VideoController extends Controller
{
    public function index()
    {
        $videos = Video::with(['autore', 'tags', 'location', 'famiglie', 'formato', 'series'])->get();
        return view('admin.videos.index', compact('videos'));
    }

    public function create()
    {
        return view('admin.videos.create', [
            'autori' => Autore::all(),
            'famiglie' => Famiglia::all(),
            'formati' => Formato::all(),
            'series' => Serie::all(),
            'tags' => Tag::all(),
        ]);
    }

    /**
     * API endpoint per cercare location su OSM/Nominatim
     */
    public function searchLocations(Request $request)
    {
        $query = $request->input('query');
        if (empty($query) || strlen($query) < 2) {
            return response()->json([
                'success' => false,
                'results' => [],
                'message' => 'Query troppo corta'
            ]);
        }

        try {
            // Chiamata diretta a Nominatim
            $response = Http::withHeaders([
                'User-Agent' => 'ArchivioRitrovata/1.0 (admin@archivioritrovata.it)'
            ])->get('https://nominatim.openstreetmap.org/search', [
                'q' => $query,
                'format' => 'json',
                'addressdetails' => 1,
                'extratags' => 1,
                'limit' => 12,
                'accept-language' => 'it,en'
            ]);

            $results = $response->json();

            // Prepara i dati da restituire al frontend (tutti i risultati)
            $formatted = [];
            foreach ($results as $item) {
                $formatted[] = [
                    'place_id' => $item['place_id'],
                    'osm_type' => $item['osm_type'] ?? null,
                    'osm_id' => $item['osm_id'] ?? null,
                    'display_name' => $item['display_name'],
                    'name' => $this->getBestDisplayName($item, $query),
                    'type' => $item['type'] ?? null,
                    'lat' => $item['lat'],
                    'lon' => $item['lon'],
                    'country' => $item['address']['country'] ?? '',
                    'state' => $item['address']['state'] ?? null,
                    'county' => $item['address']['county'] ?? null,
                    'importance' => $item['importance'] ?? 0,
                ];
            }

            return response()->json([
                'success' => true,
                'results' => $formatted
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'results' => [],
                'message' => 'Errore nella ricerca: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Sceglie il miglior nome da mostrare dalla risposta Nominatim
     */
    private function getBestDisplayName($result, $originalQuery)
    {
        // Preferisci il campo name, altrimenti la prima parte del display_name
        if (isset($result['name']) && !empty($result['name'])) {
            return $result['name'];
        }
        $parts = explode(',', $result['display_name']);
        return trim($parts[0]);
    }

    private function getOrCreateLocationById($name, $lat, $lon)
    {
        return Location::firstOrCreate(
            ['name' => $name, 'lat' => $lat, 'lon' => $lon]
        );
    }

    /**
     * Estrae l'ID YouTube da un URL YouTube
     * Supporta vari formati di URL YouTube
     */
    private function extractYoutubeId($url)
    {
        if (empty($url)) {
            return null;
        }

        // Se è già solo un ID (11 caratteri alfanumerici), restituiscilo
        if (preg_match('/^[a-zA-Z0-9_-]{11}$/', $url)) {
            return $url;
        }

        // Pattern migliorato per catturare vari formati di URL YouTube
        $patterns = [
            // Standard watch URL
            '/(?:youtube\.com\/watch\?v=)([a-zA-Z0-9_-]{11})/',
            // Embed URL
            '/(?:youtube\.com\/embed\/)([a-zA-Z0-9_-]{11})/',
            // Short URL
            '/(?:youtu\.be\/)([a-zA-Z0-9_-]{11})/',
            // Shorts URL
            '/(?:youtube\.com\/shorts\/)([a-zA-Z0-9_-]{11})/',
            // Mobile URL
            '/(?:m\.youtube\.com\/watch\?v=)([a-zA-Z0-9_-]{11})/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                return $matches[1];
            }
        }

        return null;
    }

    /**
     * Valida che l'input sia un URL YouTube valido o un ID YouTube valido
     */
    private function validateYouTubeInput($input)
    {
        if (empty($input)) {
            return true; // Campo opzionale
        }

        // Se è un ID valido
        if (preg_match('/^[a-zA-Z0-9_-]{11}$/', $input)) {
            return true;
        }

        // Se è un URL valido da cui si può estrarre un ID
        return $this->extractYoutubeId($input) !== null;
    }

    public function store(Request $request)
    {
        // Converte i nomi separati da virgola in array di nomi
        $famiglieNomi = array_filter(array_map('trim', explode(',', $request->input('famiglie', ''))));
        $tagsNomi = array_filter(array_map('trim', explode(',', $request->input('tags', ''))));

        // Trova o crea i Tag e ottieni gli ID
        $tagsIds = [];
        foreach ($tagsNomi as $nome) {
            if ($nome !== '') {
                $tag = \App\Models\Tag::firstOrCreate(['nome' => $nome]);
                $tagsIds[] = $tag->id;
            }
        }
        // Trova o crea le Famiglie e ottieni gli ID
        $famiglieIds = [];
        foreach ($famiglieNomi as $nome) {
            if ($nome !== '') {
                $famiglia = \App\Models\Famiglia::firstOrCreate(['nome' => $nome]);
                $famiglieIds[] = $famiglia->id;
            }
        }

        // Trova o crea l'autore
        $autoreId = null;
        if($request->input('autore')) {
            $autore = \App\Models\Autore::firstOrCreate(['nome' => trim($request->input('autore'))]);
            $autoreId = $autore->id;
        }

        // Trova o crea il formato
        $formatoId = null;
        if($request->input('formato')) {
            $formato = \App\Models\Formato::firstOrCreate(['nome' => trim($request->input('formato'))]);
            $formatoId = $formato->id;
        }

        // Gestione della location con coordinate specifiche
        if (!$request->input('location_lat') || !$request->input('location_lon')) {
            return back()->withErrors(['location' => 'Devi selezionare una location dalla lista dei suggerimenti.']);
        }

        $location = $this->getOrCreateLocationById(
            trim($request->input('location')),
            $request->input('location_lat'),
            $request->input('location_lon')
        );

        // Prepara dati per la validazione
        $validated = $request->validate([
            'titolo' => 'required|string|max:255',
            'anno' => 'nullable|integer',
            'durata_secondi' => 'required|integer',
            'descrizione' => 'nullable|string',
            'youtube_id' => [
                'nullable',
                'string',
                function ($attribute, $value, $fail) {
                    if (!$this->validateYouTubeInput($value)) {
                        $fail('Il campo deve contenere un URL YouTube valido o un ID YouTube valido.');
                    }
                },
            ],
            'location' => 'required|string',
            'location_lat' => 'required|numeric',
            'location_lon' => 'required|numeric',
        ]);

        // Estrai l'ID YouTube (se è un URL) o usa l'ID se è già un ID
        $youtubeId = $this->extractYoutubeId($request->input('youtube_id', ''));

        $video = \App\Models\Video::create([
            'titolo' => $validated['titolo'],
            'anno' => $validated['anno'],
            'durata_secondi' => $validated['durata_secondi'],
            'descrizione' => $validated['descrizione'],
            'youtube_id' => $youtubeId,
            'autore_id' => $autoreId,
            'formato_id' => $formatoId,
            'location_id' => $location->id,
        ]);

        $video->tags()->sync($tagsIds);
        $video->famiglie()->sync($famiglieIds);

        return redirect()->route('videos.index')->with('success', 'Video creato con successo.');
    }

    public function edit(Video $video)
    {
        $autori = Autore::all();
        $tags = Tag::all();
        $famiglie = Famiglia::all();
        $formati = Formato::all();
        $series = Serie::all();
        $locations = Location::all();

        return view('admin.videos.edit', compact('video', 'autori', 'tags', 'famiglie', 'formati', 'series', 'locations'));
    }

    public function update(Request $request, Video $video)
    {
        // Converte i nomi separati da virgola in array di nomi
        $famiglieNomi = array_filter(array_map('trim', explode(',', $request->input('famiglie', ''))));
        $tagsNomi = array_filter(array_map('trim', explode(',', $request->input('tags', ''))));

        // Trova o crea i Tag e ottieni gli ID
        $tagsIds = [];
        foreach ($tagsNomi as $nome) {
            if ($nome !== '') {
                $tag = \App\Models\Tag::firstOrCreate(['nome' => $nome]);
                $tagsIds[] = $tag->id;
            }
        }
        // Trova o crea le Famiglie e ottieni gli ID
        $famiglieIds = [];
        foreach ($famiglieNomi as $nome) {
            if ($nome !== '') {
                $famiglia = \App\Models\Famiglia::firstOrCreate(['nome' => $nome]);
                $famiglieIds[] = $famiglia->id;
            }
        }

        // Trova o crea l'autore
        $autoreId = null;
        if($request->input('autore')) {
            $autore = \App\Models\Autore::firstOrCreate(['nome' => trim($request->input('autore'))]);
            $autoreId = $autore->id;
        }

        // Trova o crea il formato
        $formatoId = null;
        if($request->input('formato')) {
            $formato = \App\Models\Formato::firstOrCreate(['nome' => trim($request->input('formato'))]);
            $formatoId = $formato->id;
        }

        // Gestione della location con coordinate specifiche
        if (!$request->input('location_lat') || !$request->input('location_lon')) {
            return back()->withErrors(['location' => 'Devi selezionare una location dalla lista dei suggerimenti.']);
        }

        $location = $this->getOrCreateLocationById(
            trim($request->input('location')),
            $request->input('location_lat'),
            $request->input('location_lon')
        );

        // Prepara dati per la validazione
        $validated = $request->validate([
            'titolo' => 'required|string|max:255',
            'anno' => 'nullable|integer',
            'durata_secondi' => 'required|integer',
            'descrizione' => 'nullable|string',
            'youtube_id' => [
                'nullable',
                'string',
                function ($attribute, $value, $fail) {
                    if (!$this->validateYouTubeInput($value)) {
                        $fail('Il campo deve contenere un URL YouTube valido o un ID YouTube valido.');
                    }
                },
            ],
            'location' => 'required|string',
            'location_lat' => 'required|numeric',
            'location_lon' => 'required|numeric',
        ]);

        // Estrai l'ID YouTube (se è un URL) o usa l'ID se è già un ID
        $youtubeId = $this->extractYoutubeId($request->input('youtube_id', ''));

        $video->update([
            'titolo' => $validated['titolo'],
            'anno' => $validated['anno'],
            'durata_secondi' => $validated['durata_secondi'],
            'descrizione' => $validated['descrizione'],
            'youtube_id' => $youtubeId,
            'autore_id' => $autoreId,
            'formato_id' => $formatoId,
            'location_id' => $location->id,
        ]);

        $video->tags()->sync($tagsIds);
        $video->famiglie()->sync($famiglieIds);

        return redirect()->route('videos.index')->with('success', 'Video aggiornato con successo.');
    }

    public function destroy(Video $video)
    {
        $video->tags()->detach();
        $video->famiglie()->detach();
        $video->series()->detach();
        $video->delete();

        return redirect()->route('videos.index')->with('success', 'Video eliminato con successo!');
    }

    public function details($id)
    {
        $video = \App\Models\Video::with(['autore', 'formato', 'location', 'series', 'famiglie', 'tags'])->findOrFail($id);
    
        return response()->json([
            'titolo' => $video->titolo,
            'anno' => $video->anno,
            'durata' => sprintf('%d:%02d', intdiv($video->durata_secondi, 60), $video->durata_secondi % 60),
            'autore' => $video->autore?->nome,
            'formato' => $video->formato?->nome,
            'location' => $video->location?->name,
            'series' => $video->series->pluck('nome')->implode(', '),
            'famiglie' => $video->famiglie->pluck('nome')->implode(', '),
            'tags' => $video->tags->pluck('nome')->implode(', '),
            'descrizione' => $video->descrizione,
            'thumbnail_url' => $video->thumbnail_url, // <-- AGGIUNGI QUESTA RIGA
        ]);
    }

}