<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Autore;
use App\Models\Serie;
use App\Models\Tag;
use App\Models\Video;
use App\Models\Location;
use App\Models\Evento;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Collection;

class SectionController extends Controller
{
    public function home()
    {
        // Recupera i tre eventi più recenti
        $recentEvents = Evento::orderBy('start_date', 'desc')->take(3)->get();
    
        return view('home', compact('recentEvents'));
    }

    public function index(Request $request)
    {
        $query = $request->input('query');

        // Esegui la ricerca (su video, autori, ecc. a seconda delle tue necessità)
        $risultati = Video::where('titolo', 'like', "%{$query}%")->get();

        return view('search.results', compact('query', 'risultati'));
    }

    public function video($id)
    {
        $video = Video::findOrFail($id);
        $video->youtube_id = $this->getYoutubeVideoId($video->yt_link);
        return view('sections.video', compact('video'));
    }

    private function getYoutubeVideoId($url)
    {
        preg_match('/(?:youtu\.be\/|youtube\.com\/(?:.*v=|.*\/v\/|embed\/|shorts\/|.*[?&]v=))([^"&?\/\s]{11})/', $url, $matches);
        return $matches[1] ?? null;
    }

    private function applyFilters($query, $filters)
    {
        if (!empty($filters['title'])) {
            $query->where('titolo', 'LIKE', '%' . $filters['title'] . '%');
        }

        if (!empty($filters['min_year']) && !empty($filters['max_year'])) {
            $query->whereBetween('anno', [$filters['min_year'], $filters['max_year']]);
        }

        if (!empty($filters['author'])) {
            $query->whereHas('autore', function ($q) use ($filters) {
                $q->where('nome', 'LIKE', '%' . $filters['author'] . '%');
            });
        }

        if (!empty($filters['min_duration']) && !empty($filters['max_duration'])) {
            $query->whereBetween('durata_secondi', [$filters['min_duration'], $filters['max_duration']]);
        }

        if (!empty($filters['format'])) {
            $query->where('formato', $filters['format']);
        }

        if (!empty($filters['family'])) {
            $query->where('famiglia', $filters['family']);
        }

        if (!empty($filters['location'])) {
            $query->whereHas('location', function ($q) use ($filters) {
                $q->where('name', 'LIKE', '%' . $filters['location'] . '%');
            });
        }        

        if (!empty($filters['tags'])) {
            $query->whereHas('tags', function ($q) use ($filters) {
                $q->whereIn('nome', $filters['tags']);
            });
        }

        return $query;
    }
    
    public function archivio(Request $request)
    {
        // Recupera i valori dei filtri dal request
        $filters = $request->only([
            'title', 'min_year', 'max_year', 'author',
            'min_duration', 'max_duration', 'format', 
            'family', 'location', 'tags', 'view'
        ]);
        
        $view = $filters['view'] ?? 'grid';

        // Query base: solo video "visibili" (senza i tag esclusi)
        $baseQuery = Video::whereDoesntHave('tags', function ($q) {
            $q->whereIn('nome', ['Fuori dal frame', 'Fuori dal Tacco']);
        });

        // Applica i filtri dell'utente
        $this->applyFilters($baseQuery, $filters);

        // Recupera i video paginati
        $videos = $baseQuery->with(['location', 'autore', 'tags'])->paginate(18);

        // Estrai gli ID di YouTube per ogni video
        foreach ($videos as $video) {
            $video->youtube_id = $this->getYoutubeVideoId($video->link_youtube);
        }

        // Controlla se la richiesta è AJAX
        if ($request->ajax()) {
            $html = ($view === 'map')
                ? view('partials.map', ['locations' => $videos->pluck('location')->unique()])->render()
                : view('partials.grid', compact('videos'))->render();
        
            return response()->json(['html' => $html]);
        }

        // Ricava gli ID dei video filtrati per i filtri
        $filteredVideoIds = (clone $baseQuery)->pluck('id');

        // Recupera i dati per i filtri SOLO sui video visibili
        $filtersData = [
            'minYear'      => (clone $baseQuery)->min('anno'),
            'maxYear'      => (clone $baseQuery)->max('anno'),
            'minDuration'  => (clone $baseQuery)->min('durata_secondi'),
            'maxDuration'  => (clone $baseQuery)->max('durata_secondi'),
            'authors'      => Autore::whereIn('id', (clone $baseQuery)->distinct()->pluck('autore_id'))->pluck('nome'),
            'formats'      => (clone $baseQuery)->distinct()->pluck('formato'),
            'families'     => (clone $baseQuery)->distinct()->pluck('famiglia'),
            'locations'    => Location::whereIn('id', (clone $baseQuery)->distinct()->pluck('location_id'))->pluck('name'),
            'tags'         => Tag::whereNotIn('nome', ['Fuori dal frame', 'Fuori dal Tacco'])
                                ->whereIn('id', \DB::table('tag_video')->whereIn('video_id', $filteredVideoIds)->pluck('tag_id'))
                                ->pluck('nome'),
        ];

        // Restituisce la vista
        return view('sections.archivio', array_merge(
            compact('videos'), $filtersData
        ));
    }
    
    public function serie(Request $request)
    {
        try {
            // Recupera le serie con i relativi video
            $series = Serie::with('videos')->paginate(10);
    
            // Itera sulle serie e sui video per elaborare i dati
            $series->each(function ($serie) {
                $serie->videos->each(function ($video) {
                    // Aggiorna l'ID di YouTube basandoti sul nuovo campo link_youtube
                    $video->youtube_id = $this->getYoutubeVideoId($video->link_youtube);
                    
                    // Logga gli URL dei video e gli ID estratti per debug
                    \Log::info('Video URL: ' . $video->link_youtube . ' - YouTube ID: ' . $video->youtube_id);
                });
            });
    
            // Ritorna una risposta JSON se la richiesta è AJAX
            if ($request->ajax()) {
                return response()->json([
                    'html' => view('partials.series_list', compact('series'))->render()
                ]);
            }
    
            // Ritorna la vista delle serie
            return view('sections.serie', compact('series'));
    
        } catch (\Exception $e) {
            // Logga l'errore e ritorna una risposta di errore
            \Log::error('Errore in serie(): ' . $e->getMessage());
            return response()->json(['error' => 'Errore interno del server'], 500);
        }
    }
   
    public function fuoriDalTacco(Request $request)
    {
        try {
            // Recupera i valori dei filtri dal request
            $filters = $request->only([
                'title', 'min_year', 'max_year', 'author',
                'min_duration', 'max_duration', 'format', 
                'family', 'location', 'tags', 'view'
            ]);
            
            $view = $filters['view'] ?? 'grid';

            // Solo video con "Fuori dal Tacco"
            $query = Video::whereHas('tags', function ($q) {
                $q->where('nome', 'Fuori dal Tacco');
            });
            $this->applyFilters($query, $filters); // <-- commentata

            // Recupera i video paginati
            $videos = $query->with(['location', 'autore', 'tags'])->paginate(18);

            // Estrai gli ID di YouTube per ogni video
            foreach ($videos as $video) {
                $video->youtube_id = $this->getYoutubeVideoId($video->link_youtube);
            }

            // Controlla se la richiesta è AJAX
            if ($request->ajax()) {
                $html = ($view === 'map')
                    ? view('partials.map', ['locations' => $videos->pluck('location')->unique()])->render()
                    : view('partials.grid', compact('videos'))->render();
            
                return response()->json(['html' => $html]);
            }

            // Recupera i dati per i filtri (facoltativo: puoi filtrare solo i tag presenti tra questi video)
            $filtersData = [
                'minYear' => Video::whereHas('tags', function ($q) {
                    $q->where('nome', 'Fuori dal Tacco');
                })->min('anno'),
                'maxYear' => Video::whereHas('tags', function ($q) {
                    $q->where('nome', 'Fuori dal Tacco');
                })->max('anno'),
                'minDuration' => Video::whereHas('tags', function ($q) {
                    $q->where('nome', 'Fuori dal Tacco');
                })->min('durata_secondi'),
                'maxDuration' => Video::whereHas('tags', function ($q) {
                    $q->where('nome', 'Fuori dal Tacco');
                })->max('durata_secondi'),
                'authors' => Autore::whereHas('videos.tags', function ($q) {
                    $q->where('nome', 'Fuori dal Tacco');
                })->pluck('nome'),
                'formats' => Video::whereHas('tags', function ($q) {
                    $q->where('nome', 'Fuori dal Tacco');
                })->distinct()->pluck('formato'),
                'families' => Video::whereHas('tags', function ($q) {
                    $q->where('nome', 'Fuori dal Tacco');
                })->distinct()->pluck('famiglia'),
                'locations' => Location::whereHas('videos.tags', function ($q) {
                    $q->where('nome', 'Fuori dal Tacco');
                })->pluck('name'),
                'tags' => Tag::whereHas('videos.tags', function ($q) {
                    $q->where('nome', 'Fuori dal Tacco');
                })
                ->whereNotIn('nome', ['Fuori dal frame', 'Fuori dal Tacco'])
                ->pluck('nome')
            ];

            // Restituisce la vista
            return view('sections.fuori-dal-tacco', array_merge(
                compact('videos'), $filtersData
            ));

        } catch (\Exception $e) {
            \Log::error('Errore in fuoriDalTacco(): ' . $e->getMessage());
            return response()->json(['error' => 'Errore interno del server'], 500);
        }
    }

    public function fuoriDalFrame()
    {    
        return view('sections.fuori-dal-frame');
    }
    
    public function autori()
    {
        // Recupera tutti i video con l'autore
        $videos = Video::with('autore')->get();
    
        // Raggruppa per formato
        $formati = $videos->groupBy('formato')->map(function ($videosDelFormato) {
            // Ottieni autori unici per questo formato
            $autori = $videosDelFormato->pluck('autore')->unique('id')->values();
    
            return $autori;
        });
    
        return view('sections.autori', compact('formati'));
    }

    public function showAutore($id)
    {
        try {
            $autore = Autore::findOrFail($id);
    
            // Paginazione dei video (12 per pagina)
            $videos = $autore->videos()->paginate(12);
    
            // Applichiamo l'id di YouTube ai video
            $videos->getCollection()->transform(function ($video) {
                $video->youtube_id = $this->getYoutubeVideoId($video->link_youtube);
                return $video;
            });
    
            return view('sections.autore-show', compact('autore', 'videos'));
        } catch (\Exception $e) {
            \Log::error('Errore in showAutore(): ' . $e->getMessage());
            return redirect()->back()->with('error', 'Errore nel recupero dei dati dell\'autore.');
        }
    }      
    
    public function personaggi(Request $request)
    {
        try {
            $filters = $request->only([
                'title', 'min_year', 'max_year', 'author',
                'min_duration', 'max_duration', 'format', 
                'family', 'location', 'tags', 'view'
            ]);

            // Solo video con "Fuori dal frame"
            $query = Video::whereHas('tags', function ($q) {
                $q->where('nome', 'Fuori dal frame');
            });

            // Applica filtri generali
            $this->applyFilters($query, $filters);

            // Paginazione
            $videos = $query->with(['location', 'autore', 'tags'])->paginate(18);

            // Estrai ID YouTube
            foreach ($videos as $video) {
                $video->youtube_id = $this->getYoutubeVideoId($video->link_youtube);
            }

            // Vista AJAX
            if ($request->ajax()) {
                $html = view('partials.grid', compact('videos'))->render();
                return response()->json(['html' => $html]);
            }

            // Dati filtri
            $filtersData = [
                'minDuration' => Video::whereHas('tags', fn($q) => $q->where('nome', 'Fuori dal frame'))->min('durata_secondi'),
                'maxDuration' => Video::whereHas('tags', fn($q) => $q->where('nome', 'Fuori dal frame'))->max('durata_secondi'),
                'authors' => Autore::whereHas('videos.tags', fn($q) => $q->where('nome', 'Fuori dal frame'))->pluck('nome'),
                'formats' => Video::whereHas('tags', fn($q) => $q->where('nome', 'Fuori dal frame'))->distinct()->pluck('formato'),
                'families' => Video::whereHas('tags', fn($q) => $q->where('nome', 'Fuori dal frame'))->distinct()->pluck('famiglia'),
                'locations' => Location::whereHas('videos.tags', fn($q) => $q->where('nome', 'Fuori dal frame'))->pluck('name'),
                'tags' => Tag::whereHas('videos.tags', fn($q) => $q->where('nome', 'Fuori dal frame'))
                            ->whereNotIn('nome', ['Fuori dal frame', 'Fuori dal Tacco'])
                            ->pluck('nome')
            ];

            return view('sections.personaggi', array_merge(compact('videos'), $filtersData));

        } catch (\Exception $e) {
            \Log::error('Errore in personaggi(): ' . $e->getMessage());
            return response()->json(['error' => 'Errore interno del server'], 500);
        }
    }
    
    public function eventi(Request $request)
    {
        $query = $request->input('query');
        $events = Evento::query(); // Utilizzo del modello aggiornato "Evento"
    
        if ($query) {
            $events = $events->where('titolo', 'like', '%' . $query . '%') // Ricerca nel campo "titolo"
                             ->orWhere('descrizione', 'like', '%' . $query . '%') // Ricerca nel campo "descrizione"
                             ->orWhere('start_date', 'like', '%' . $query . '%') // Ricerca nel campo "start_date"
                             ->orWhere('end_date', 'like', '%' . $query . '%') // Ricerca nel campo "end_date"
                             ->orWhere('luogo', 'like', '%' . $query . '%'); // Ricerca nel campo "luogo"
        }
    
        $events = $events->paginate(10);
    
        return view('sections.eventi', compact('events'));
    }

    public function info()
    {
        return view('sections.chi-siamo');
    }

    public function diconoDiNoi()
    {
        return view('sections.dicono-di-noi');
    }
}