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

    private function nominatimRequest($query, $additionalParams = [])
    {
        $params = array_merge([
            'q' => $query,
            'format' => 'json',
            'limit' => 1,
        ], $additionalParams);

        return Http::withHeaders([
            'User-Agent' => 'ArchivioRitrovata/1.0 (admin@archivioritrovata.it)'
        ])->get('https://nominatim.openstreetmap.org/search', $params);
    }
    
    private function getOrCreateLocation($locationName)
    {
        // Dizionario di traduzione da nomi italiani a nomi originali + paese per le città
        $cityTranslations = [
            // Spagna
            'barcellona' => 'Barcelona, Spain',
            'madrid' => 'Madrid, Spain',
            'siviglia' => 'Sevilla, Spain',
            'valencia' => 'Valencia, Spain',
            'bilbao' => 'Bilbao, Spain',
            'granada' => 'Granada, Spain',
            'cordova' => 'Córdoba, Spain',
            'salamanca' => 'Salamanca, Spain',
            'toledo' => 'Toledo, Spain',
            'san sebastian' => 'San Sebastián, Spain',
            'saragozza' => 'Zaragoza, Spain',
            'malaga' => 'Málaga, Spain',
            
            // Francia
            'parigi' => 'Paris, France',
            'marsiglia' => 'Marseille, France',
            'lione' => 'Lyon, France',
            'tolosa' => 'Toulouse, France',
            'nizza' => 'Nice, France',
            'strasburgo' => 'Strasbourg, France',
            'bordeaux' => 'Bordeaux, France',
            'lille' => 'Lille, France',
            'nantes' => 'Nantes, France',
            'cannes' => 'Cannes, France',
            'avignone' => 'Avignon, France',
            'montpellier' => 'Montpellier, France',
            
            // Regno Unito
            'londra' => 'London, UK',
            'edimburgo' => 'Edinburgh, UK',
            'manchester' => 'Manchester, UK',
            'liverpool' => 'Liverpool, UK',
            'birmingham' => 'Birmingham, UK',
            'glasgow' => 'Glasgow, UK',
            'bristol' => 'Bristol, UK',
            'cardiff' => 'Cardiff, UK',
            
            // Germania
            'berlino' => 'Berlin, Germany',
            'monaco' => 'Munich, Germany',
            'monaco di baviera' => 'Munich, Germany',
            'amburgo' => 'Hamburg, Germany',
            'colonia' => 'Cologne, Germany',
            'francoforte' => 'Frankfurt, Germany',
            'stoccarda' => 'Stuttgart, Germany',
            'dresda' => 'Dresden, Germany',
            'lipsia' => 'Leipzig, Germany',
            'norimberga' => 'Nuremberg, Germany',
            
            // Portogallo
            'lisbona' => 'Lisbon, Portugal',
            'porto' => 'Porto, Portugal',
            'coimbra' => 'Coimbra, Portugal',
            'braga' => 'Braga, Portugal',
            
            // Paesi Bassi  
            'amsterdam' => 'Amsterdam, Netherlands',
            'rotterdam' => 'Rotterdam, Netherlands',
            'l\'aia' => 'The Hague, Netherlands',
            'utrecht' => 'Utrecht, Netherlands',
            
            // Belgio
            'bruxelles' => 'Brussels, Belgium',
            'anversa' => 'Antwerp, Belgium',
            'gand' => 'Ghent, Belgium',
            'bruges' => 'Bruges, Belgium',
            
            // Austria
            'vienna' => 'Vienna, Austria',
            'salisburgo' => 'Salzburg, Austria',
            'innsbruck' => 'Innsbruck, Austria',
            'graz' => 'Graz, Austria',
            
            // Svizzera
            'zurigo' => 'Zurich, Switzerland',
            'ginevra' => 'Geneva, Switzerland',
            'berna' => 'Bern, Switzerland',
            'basilea' => 'Basel, Switzerland',
            'losanna' => 'Lausanne, Switzerland',
            
            // Grecia
            'atene' => 'Athens, Greece',
            'salonicco' => 'Thessaloniki, Greece',
            'patrasso' => 'Patras, Greece',
            
            // Repubblica Ceca
            'praga' => 'Prague, Czech Republic',
            'brno' => 'Brno, Czech Republic',
            
            // Polonia
            'varsavia' => 'Warsaw, Poland',
            'cracovia' => 'Krakow, Poland',
            'danzica' => 'Gdansk, Poland',
            'breslavia' => 'Wroclaw, Poland',
            
            // Ungheria
            'budapest' => 'Budapest, Hungary',
            
            // Croazia
            'zagabria' => 'Zagreb, Croatia',
            'dubrovnik' => 'Dubrovnik, Croatia',
            'spalato' => 'Split, Croatia',
            
            // Russia
            'mosca' => 'Moscow, Russia',
            'san pietroburgo' => 'Saint Petersburg, Russia',
            
            // Turchia
            'istanbul' => 'Istanbul, Turkey',
            'ankara' => 'Ankara, Turkey',
            
            // Stati Uniti
            'new york' => 'New York, USA',
            'los angeles' => 'Los Angeles, USA',
            'chicago' => 'Chicago, USA',
            'miami' => 'Miami, USA',
            'san francisco' => 'San Francisco, USA',
            'boston' => 'Boston, USA',
            'washington' => 'Washington DC, USA',
            
            // Altre città importanti
            'tokyo' => 'Tokyo, Japan',
            'pechino' => 'Beijing, China',
            'mumbai' => 'Mumbai, India',
            'sydney' => 'Sydney, Australia',
            'melbourne' => 'Melbourne, Australia',
            'città del capo' => 'Cape Town, South Africa',
            'rio de janeiro' => 'Rio de Janeiro, Brazil',
            'buenos aires' => 'Buenos Aires, Argentina',
            'città del messico' => 'Mexico City, Mexico',
        ];
        
        // Dizionario delle nazioni (nome italiano → nome inglese)
        $countryTranslations = [
            'italia' => 'Italy',
            'spagna' => 'Spain',
            'francia' => 'France',
            'germania' => 'Germany',
            'regno unito' => 'United Kingdom',
            'inghilterra' => 'United Kingdom',
            'portogallo' => 'Portugal',
            'paesi bassi' => 'Netherlands',
            'olanda' => 'Netherlands',
            'belgio' => 'Belgium',
            'austria' => 'Austria',
            'svizzera' => 'Switzerland',
            'grecia' => 'Greece',
            'repubblica ceca' => 'Czech Republic',
            'cechia' => 'Czech Republic',
            'polonia' => 'Poland',
            'ungheria' => 'Hungary',
            'croazia' => 'Croatia',
            'slovenia' => 'Slovenia',
            'serbia' => 'Serbia',
            'romania' => 'Romania',
            'bulgaria' => 'Bulgaria',
            'russia' => 'Russia',
            'turchia' => 'Turkey',
            'stati uniti' => 'United States',
            'usa' => 'United States',
            'america' => 'United States',
            'canada' => 'Canada',
            'messico' => 'Mexico',
            'brasile' => 'Brazil',
            'argentina' => 'Argentina',
            'cile' => 'Chile',
            'giappone' => 'Japan',
            'cina' => 'China',
            'india' => 'India',
            'australia' => 'Australia',
            'sudafrica' => 'South Africa',
            'egitto' => 'Egypt',
            'marocco' => 'Morocco',
            'algeria' => 'Algeria',
            'tunisia' => 'Tunisia',
            'norvegia' => 'Norway',
            'svezia' => 'Sweden',
            'finlandia' => 'Finland',
            'danimarca' => 'Denmark',
            'islanda' => 'Iceland',
            'irlanda' => 'Ireland',
            'lussemburgo' => 'Luxembourg',
            'monaco' => 'Monaco',
            'malta' => 'Malta',
            'cipro' => 'Cyprus'
        ];
        
        $lowerLocationName = strtolower(trim($locationName));

        // 1. Traduzione nazione
        if (isset($countryTranslations[$lowerLocationName])) {
            $query = $countryTranslations[$lowerLocationName];
            $response = $this->nominatimRequest($query, ['limit' => 5]);
            $results = $response->json();
            foreach ($results as $result) {
                if (
                    isset($result['type']) &&
                    ($result['type'] === 'country' || $result['class'] === 'boundary')
                ) {
                    return Location::firstOrCreate(
                        ['name' => $locationName],
                        ['lat' => $result['lat'], 'lon' => $result['lon']]
                    );
                }
            }
            if (!empty($results)) {
                return Location::firstOrCreate(
                    ['name' => $locationName],
                    ['lat' => $results[0]['lat'], 'lon' => $results[0]['lon']]
                );
            }
        }
    
        // 2. Traduzione città estera
        if (isset($cityTranslations[$lowerLocationName])) {
            $query = $cityTranslations[$lowerLocationName];
            $response = $this->nominatimRequest($query, ['limit' => 5]);
            $results = $response->json();
            foreach ($results as $result) {
                if (
                    isset($result['type']) &&
                    in_array($result['type'], ['city', 'town', 'village', 'hamlet'])
                ) {
                    return Location::firstOrCreate(
                        ['name' => $locationName],
                        ['lat' => $result['lat'], 'lon' => $result['lon']]
                    );
                }
            }
            if (!empty($results)) {
                return Location::firstOrCreate(
                    ['name' => $locationName],
                    ['lat' => $results[0]['lat'], 'lon' => $results[0]['lon']]
                );
            }
        }
    
        // 3. Prova città italiana con countrycode IT
        $response = $this->nominatimRequest($locationName, ['countrycodes' => 'IT', 'limit' => 5]);
        $results = $response->json();
        foreach ($results as $result) {
            if (
                isset($result['type']) &&
                in_array($result['type'], ['city', 'town', 'village', 'hamlet'])
            ) {
                return Location::firstOrCreate(
                    ['name' => $locationName],
                    ['lat' => $result['lat'], 'lon' => $result['lon']]
                );
            }
        }
        if (!empty($results)) {
            return Location::firstOrCreate(
                ['name' => $locationName],
                ['lat' => $results[0]['lat'], 'lon' => $results[0]['lon']]
            );
        }
    
        // 4. Fallback senza countrycode
        $response = $this->nominatimRequest($locationName, ['limit' => 5]);
        $results = $response->json();
        foreach ($results as $result) {
            if (
                isset($result['type']) &&
                in_array($result['type'], ['city', 'town', 'village', 'hamlet', 'country'])
            ) {
                return Location::firstOrCreate(
                    ['name' => $locationName],
                    ['lat' => $result['lat'], 'lon' => $result['lon']]
                );
            }
        }
        if (!empty($results)) {
            return Location::firstOrCreate(
                ['name' => $locationName],
                ['lat' => $results[0]['lat'], 'lon' => $results[0]['lon']]
            );
        }
    
        throw new \Exception("Impossibile trovare le coordinate per la località: $locationName. Verifica che il nome sia corretto.");
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

        // Trova o crea la location con lat/lon
        $location = $this->getOrCreateLocation(trim($request->input('location')));

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
            // Gli altri campi sono gestiti sopra
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

        // Trova o crea la location con lat/lon
        $location = $this->getOrCreateLocation(trim($request->input('location')));

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
            // Gli altri campi sono gestiti sopra
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
}