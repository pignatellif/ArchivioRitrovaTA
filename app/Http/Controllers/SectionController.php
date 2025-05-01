<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Autore;
use App\Models\Seire;
use App\Models\Tag;
use App\Models\Video;
use Illuminate\Support\Facades\Log;

class SectionController extends Controller
{
    public function home()
    {
        return view('home');
    }

    public function video($id)
    {
        $video = Video::findOrFail($id);
        $video->youtube_id = $this->getYoutubeVideoId($video->yt_link);
        return view('sections.video', compact('video'));
    }

    private function getYoutubeVideoId($url) {
        preg_match('/(?:youtu\.be\/|youtube\.com\/(?:.*v=|.*\/v\/|embed\/|shorts\/|.*[?&]v=))([^"&?\/\s]{11})/', $url, $matches);
        return $matches[1] ?? null;
    } 

    public function archivio(Request $request)
    {
        // Recupera i valori dei filtri dal request
        $titolo = $request->input('title');
        $annoMin = $request->input('min_year');
        $annoMax = $request->input('max_year');
        $autore = $request->input('author');
        $durataMin = $request->input('min_duration');
        $durataMax = $request->input('max_duration');
        $formato = $request->input('format');
        $famiglia = $request->input('family');
        $luogo = $request->input('location');
        $view = $request->input('view', 'grid'); // Default "grid"
    
        // Avvio una query per recuperare i video
        $query = Video::query();
    
        // Applica i filtri se presenti
        if ($titolo) {
            $query->where('titolo', 'LIKE', '%' . $titolo . '%');
        }
    
        if ($annoMin && $annoMax) {
            $query->whereBetween('anno', [$annoMin, $annoMax]);
        }
    
        if ($autore) {
            $query->whereHas('autore', function ($q) use ($autore) {
                $q->where('nome', 'LIKE', '%' . $autore . '%');
            });
        }
    
        if ($durataMin && $durataMax) {
            $query->whereBetween('durata_secondi', [$durataMin, $durataMax]);
        }
    
        if ($formato) {
            $query->where('formato', $formato);
        }
    
        if ($famiglia) {
            $query->where('famiglia', $famiglia);
        }
    
        if ($luogo) {
            $query->where('luogo', 'LIKE', '%' . $luogo . '%');
        }
    
        // Recupera i video paginati
        $videos = $query->paginate(10);
    
        // Controlla se la richiesta è AJAX
        if ($request->ajax()) {
            // Se la vista è "mappa", passiamo i luoghi per la mappa
            if ($view === 'map') {
                $locations = $videos->pluck('luogo')->unique()->filter()->values();
                $html = view('partials.map', compact('locations'))->render();
            } else {
                // Default: vista "griglia"
                $html = view('partials.grid', compact('videos'))->render();
            }
    
            // Restituisci una risposta JSON
            return response()->json([
                'html' => $html,
            ]);
        }
    
        // Recupera i dati per i filtri (per la prima visualizzazione completa)
        $minYear = Video::min('anno');
        $maxYear = Video::max('anno');
        $minDuration = Video::min('durata_secondi');
        $maxDuration = Video::max('durata_secondi');
        $authors = Autore::pluck('nome');
        $formats = Video::distinct()->pluck('formato');
        $families = Video::distinct()->pluck('famiglia');
        $locations = Video::distinct()->pluck('luogo');
    
        // Restituisci la vista completa per richieste normali
        return view('sections.archivio', compact(
            'videos', 'minYear', 'maxYear', 'minDuration', 
            'maxDuration', 'authors', 'formats', 'families', 'locations'
        ));
    }

    public function serie(Request $request)
    {
        try {
            $series = Series::with('videos')->paginate(10);
            $series->each(function ($serie) {
                $serie->videos->each(function ($video) {
                    $video->youtube_id = $this->getYoutubeVideoId($video->yt_link); // Cambia url a yt_link
                    // Logga gli URL dei video e gli ID estratti per il debug
                    \Log::info('Video URL: ' . $video->yt_link . ' - YouTube ID: ' . $video->youtube_id);
                });
            });

            if ($request->ajax()) {
                return response()->json([
                    'html' => view('partials.series_list', compact('series'))->render()
                ]);
            }

            return view('sections.serie', compact('series'));

        } catch (\Exception $e) {
            \Log::error('Errore in serie(): ' . $e->getMessage());
            return response()->json(['error' => 'Errore interno del server'], 500);
        }
    }
   
    public function fuoriDalTacco(Request $request)
    {
        try {
            // Query per recuperare i video con il tag "Fuori dal Tacco"
            $query = Video::where('tags', 'like', '%Fuori dal Tacco%');
            
            // Filtro per titolo
            if ($request->filled('title')) {
                $query->where('title', 'like', '%' . $request->title . '%');
            }
            
            // Filtro per autore
            if ($request->filled('author')) {
                $query->where('author', 'like', '%' . $request->author . '%');
            }
            
            // Filtro per anno
            if ($request->filled('min_year') && $request->filled('max_year')) {
                $query->whereBetween('year', [$request->min_year, $request->max_year]);
            }
            
            // Filtro per durata
            if ($request->filled('min_duration') && $request->filled('max_duration')) {
                $query->whereBetween('duration', [$request->min_duration, $request->max_duration]);
            }

            // Filtro per formato
            if ($request->filled('format')) {
                $query->where('format', $request->format);
            }

            // Filtro per famiglia
            if ($request->filled('family')) {
                $query->where('family', $request->family);
            }

            // Filtro per luogo
            if ($request->filled('location')) {
                $query->where('location', $request->location);
            }

            // Recupera i video paginati
            $videos = $query->paginate(18);

            // Assegna gli ID YouTube ai video
            $videos->each(function ($video) {
                $youtubeId = $this->getYoutubeVideoId($video->yt_link);
                $video->youtube_id = $youtubeId ?: null; // Fallback
            });
    
            // Recupera solo le location valide (filtrando i dati malformati)
            $locationList = $query->pluck('location')
            ->filter()
            ->unique()
            ->values()
            ->toArray();
                
            // Determina il tipo di vista (griglia o mappa)
            $viewType = $request->get('view', 'grid');
    
            // Se la richiesta è AJAX, restituisce la vista parziale appropriata
            if ($request->ajax()) {
                $html = match ($viewType) {
                    'grid' => view('partials.video_list', compact('videos'))->render(),
                    'map' => view('partials.mappa', compact('locationList'))->render(),
                    default => view('partials.video_list', compact('videos'))->render(),
                };
    
                return response()->json(['html' => $html]);
            }

            // Filtra i dati per i filtri basandosi sui video con tag "Fuori dal Tacco"
            $authors = Video::where('tags', 'like', '%Fuori dal Tacco%')->select('author')->distinct()->pluck('author');
            $formats = Video::where('tags', 'like', '%Fuori dal Tacco%')->select('format')->distinct()->pluck('format');
            $families = Video::where('tags', 'like', '%Fuori dal Tacco%')->select('family')->distinct()->pluck('family');
            $locations = Video::where('tags', 'like', '%Fuori dal Tacco%')->select('location')->distinct()->pluck('location');
            $minYear = Video::where('tags', 'like', '%Fuori dal Tacco%')->min('year');
            $maxYear = Video::where('tags', 'like', '%Fuori dal Tacco%')->max('year');
            $minDuration = Video::where('tags', 'like', '%Fuori dal Tacco%')->min('duration');
            $maxDuration = Video::where('tags', 'like', '%Fuori dal Tacco%')->max('duration');

            // Restituisce la vista completa
            return view('sections.fuori-dal-tacco', compact(
                'videos',
                'authors',
                'formats',
                'families',
                'locations',
                'minYear',
                'maxYear',
                'minDuration',
                'maxDuration',
                'viewType'
            ));
    
        } catch (\Exception $e) {
            // Logga l'errore e restituisce una risposta di errore
            \Log::error('Errore in fuoriDalTacco(): ' . $e->getMessage());
            return response()->json(['error' => 'Errore interno del server'], 500);
        }
    }

    public function fuoriDalFrame()
    {    
        return view('sections.fuori-dal-frame');
    }

    public function registi(Request $request)
    {
        $query = $request->input('query');
        $directors = Video::select('autore')->distinct();
    
        if ($query) {
            $directors = $directors->where('autore', 'like', '%' . $query . '%');
        }
    
        $directors = $directors->paginate(10);
    
        return view('sections.registi', compact('directors'));
    }

    public function personaggi(Request $request)
    {
        try {
            // Query per recuperare i video con il tag "Fuori dal Frame"
            $query = Video::where('tags', 'like', '%Fuori dal Frame%');
            
            // Filtro per titolo
            if ($request->filled('title')) {
                $query->where('title', 'like', '%' . $request->title . '%');
            }
            
            // Filtro per autore
            if ($request->filled('author')) {
                $query->where('author', 'like', '%' . $request->author . '%');
            }
            
            // Filtro per anno
            if ($request->filled('min_year') && $request->filled('max_year')) {
                $query->whereBetween('year', [$request->min_year, $request->max_year]);
            }
            
            // Filtro per durata
            if ($request->filled('min_duration') && $request->filled('max_duration')) {
                $query->whereBetween('duration', [$request->min_duration, $request->max_duration]);
            }

            // Filtro per formato
            if ($request->filled('format')) {
                $query->where('format', $request->format);
            }

            // Filtro per famiglia
            if ($request->filled('family')) {
                $query->where('family', $request->family);
            }

            // Filtro per luogo
            if ($request->filled('location')) {
                $query->where('location', $request->location);
            }

            // Recupera i video paginati
            $videos = $query->paginate(18);

            // Assegna gli ID YouTube ai video
            $videos->each(function ($video) {
                $youtubeId = $this->getYoutubeVideoId($video->yt_link);
                $video->youtube_id = $youtubeId ?: null; // Fallback
            });

            // Recupera solo le location valide (filtrando i dati malformati)
            $locationList = $query->pluck('location')
                ->filter()
                ->unique()
                ->values()
                ->toArray();
                
            // Recupera tutti i formati dei video, anche senza il tag "Fuori dal Frame"
            $allFormats = Video::select('format')->distinct()->pluck('format')->toArray();

            // Determina il tipo di vista (griglia o mappa)
            $viewType = $request->get('view', 'grid');

            // Se la richiesta è AJAX, restituisce la vista parziale appropriata
            if ($request->ajax()) {
                $html = match ($viewType) {
                    'grid' => view('partials.video_list', compact('videos'))->render(),
                    'map' => view('partials.mappa', compact('locationList'))->render(),
                    default => view('partials.video_list', compact('videos'))->render(),
                };

                return response()->json(['html' => $html]);
            }

            // Filtra i dati per i filtri basandosi sui video con tag "Fuori dal Frame"
            $authors = Video::where('tags', 'like', '%Fuori dal Frame%')->select('author')->distinct()->pluck('author');
            $formats = Video::where('tags', 'like', '%Fuori dal Frame%')->select('format')->distinct()->pluck('format');
            $families = Video::where('tags', 'like', '%Fuori dal Frame%')->select('family')->distinct()->pluck('family');
            $locations = Video::where('tags', 'like', '%Fuori dal Frame%')->select('location')->distinct()->pluck('location');
            $minYear = Video::where('tags', 'like', '%Fuori dal Frame%')->min('year');
            $maxYear = Video::where('tags', 'like', '%Fuori dal Frame%')->max('year');
            $minDuration = Video::where('tags', 'like', '%Fuori dal Frame%')->min('duration');
            $maxDuration = Video::where('tags', 'like', '%Fuori dal Frame%')->max('duration');

            // Restituisce la vista completa
            return view('sections.personaggi', compact(
                'videos',
                'authors',
                'formats',
                'families',
                'locations',
                'minYear',
                'maxYear',
                'minDuration',
                'maxDuration',
                'viewType',
                'allFormats'
            ));

        } catch (\Exception $e) {
            // Logga l'errore e restituisce una risposta di errore
            \Log::error('Errore in fuoriDalFrame(): ' . $e->getMessage());
            return response()->json(['error' => 'Errore interno del server'], 500);
        }
    }
    
    public function eventi(Request $request)
    {
        $query = $request->input('query');
        $events = Event::query();
    
        if ($query) {
            $events = $events->where('title', 'like', '%' . $query . '%')
                             ->orWhere('description', 'like', '%' . $query . '%')
                             ->orWhere('date', 'like', '%' . $query . '%');
        }
    
        $events = $events->paginate(10);
    
        return view('sections.eventi', compact('events'));
    }

    public function sostienici()
    {
        return view('sections.sostienici');
    }

    public function info()
    {
        return view('sections.chi-siamo');
    }
}