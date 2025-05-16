<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\Autore;
use App\Models\Tag;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class VideoController extends Controller
{
    public function index()
    {
        $videos = Video::with('autore', 'tags')->get(); // Carica autore e tag per ottimizzare le query
        return view('admin.videos.index', compact('videos'));
    }

    public function create()
    {
        return view('admin.videos.create');
    }      

    private function nominatimRequest($query)
    {
        return Http::withHeaders([
            'User-Agent' => 'ArchivioRitrovata/1.0 (admin@archivioritrovata.it)' // Usa una tua email reale
        ])->get('https://nominatim.openstreetmap.org/search', [
            'q' => $query,
            'format' => 'json',
            'limit' => 1,
        ]);
    }
    
    private function getOrCreateLocation($locationName)
    {
        // Primo tentativo: con ", Italia"
        $response = $this->nominatimRequest($locationName . ', Italia');
        $results = $response->json();
    
        // Secondo tentativo: solo il nome
        if (empty($results)) {
            $response = $this->nominatimRequest($locationName);
            $results = $response->json();
        }
    
        if (!empty($results)) {
            $data = $results[0];
            return Location::firstOrCreate(
                ['name' => $locationName],
                ['lat' => $data['lat'], 'lon' => $data['lon']]
            );
        }
    
        throw new \Exception("Impossibile trovare le coordinate per la location: $locationName. Verifica che il nome sia corretto.");
    }        

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titolo' => 'required|string',
            'descrizione' => 'required|string',
            'anno' => 'nullable|integer',
            'durata_secondi' => 'required|integer',
            'formato' => 'nullable|string',
            'famiglia' => 'required|string',
            'luogo' => 'required|string',
            'autore_nome' => 'required|string',
            'link_youtube' => 'required|url',
            'tags' => 'nullable|string',
        ]);

        $autore = Autore::firstOrCreate(['nome' => $validated['autore_nome']]);
        $location = $this->getOrCreateLocation($validated['luogo']);

        $video = Video::create([
            'titolo' => $validated['titolo'],
            'descrizione' => $validated['descrizione'],
            'anno' => $validated['anno'],
            'durata_secondi' => $validated['durata_secondi'],
            'formato' => $validated['formato'],
            'famiglia' => $validated['famiglia'],
            'link_youtube' => $validated['link_youtube'],
            'location_id' => $location->id,
            'autore_id' => $autore->id,
        ]);

        if (!empty($validated['tags'])) {
            $tagNames = array_map('trim', explode(',', $validated['tags']));
            foreach ($tagNames as $tagName) {
                if ($tagName !== '') {
                    $tagName = ucfirst(strtolower($tagName)); // oppure ucwords() se preferisci tutte le iniziali maiuscole
                    $tag = Tag::firstOrCreate(['nome' => $tagName]);
                    $video->tags()->attach($tag->id);
                }
            }
        }

        return redirect()->route('videos.index')->with('success', 'Video aggiunto con successo!');
    }    

    public function edit(Video $video)
    {
        $authors = Autore::all(); // Recupera tutti gli autori per il form
        $tags = Tag::all(); // Recupera tutti i tag per il form
        return view('admin.videos.edit', compact('video', 'authors', 'tags'));
    }

    public function update(Request $request, Video $video)
    {
        $request->validate([
            'anno' => 'nullable|integer',
            'durata_secondi' => 'required|integer',
            'titolo' => 'required|string',
            'formato' => 'nullable|string',
            'famiglia' => 'required|string',
            'luogo' => 'required|string',
            'tags' => 'nullable|string', // Permetti una stringa di tag separati da virgole
            'link_youtube' => 'required|url',
            'autore_nome' => 'required|string', // Nome dell'autore da creare o aggiornare
            'descrizione' => 'required|string',
        ]);

        // Aggiorna o crea l'autore
        $autore = Autore::firstOrCreate(['nome' => $request->autore_nome]);

        // Creazione o recupero della location
        $location = $this->getOrCreateLocation($request->luogo);

        // Aggiorna il video
        $video->update([
            'anno' => $request->anno,
            'durata_secondi' => $request->durata_secondi,
            'titolo' => $request->titolo,
            'formato' => $request->formato,
            'famiglia' => $request->famiglia,
            'location_id' => $location->id, // Associa la location aggiornata
            'link_youtube' => $request->link_youtube,
            'autore_id' => $autore->id,
            'descrizione' => $request->descrizione,
        ]);

        // Gestione dei tag
        if ($request->has('tags')) {
            $tagNames = explode(',', $request->tags);
            $tagIds = [];

            foreach ($tagNames as $tagName) {
                $tagName = trim($tagName);
                if (!empty($tagName)) {
                    $tagName = ucfirst(strtolower($tagName)); // o ucwords() se vuoi ogni parola con iniziale maiuscola
                    $tag = Tag::firstOrCreate(['nome' => $tagName]);
                    $tagIds[] = $tag->id;
                }
            }

            $video->tags()->sync($tagIds);
        }

        return redirect()->route('videos.index')->with('success', 'Video aggiornato con successo!');
    }

    public function destroy(Video $video)
    {
        try {
            // Rimuovi le associazioni con i tag
            if ($video->tags()->exists()) {
                $video->tags()->detach();
            }
    
            // Controlla se esiste un autore associato e gestisci eventuali dipendenze
            if ($video->autore()->exists()) {
                // Logica opzionale per gestire l'autore, se necessario
                // Ad esempio, potresti decidere di rimuovere l'autore se non è associato ad altri video
            }
    
            // Elimina il video
            $video->delete();
    
            return redirect()->route('videos.index')->with('success', 'Video eliminato con successo!');
        } catch (\Exception $e) {
            // Gestione degli errori
            return redirect()->route('videos.index')->with('error', 'Si è verificato un errore durante l\'eliminazione del video.');
        }
    }
}