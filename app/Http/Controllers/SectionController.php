<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Video;
use App\Models\Series;
use App\Models\Event;
use Illuminate\Support\Facades\Log;

class SectionController extends Controller
{
    public function home()
    {
        // Recupera i luoghi dei video
        $locations = Video::select('location')->distinct()->get();
        $locations = $locations->pluck('location')->toArray();
        return view('home', compact('locations'));
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
        try {
            $query = Video::query();
            
            if ($request->filled('title')) {
                $query->where('title', 'like', '%' . $request->title . '%');
            }
            
            if ($request->filled('author')) {
                $query->where('author', 'like', '%' . $request->author . '%');
            }
            
            if ($request->filled('min_year') && $request->filled('max_year')) {
                $query->whereBetween('year', [$request->min_year, $request->max_year]);
            }
            
            if ($request->filled('min_duration') && $request->filled('max_duration')) {
                $query->whereBetween('duration', [$request->min_duration, $request->max_duration]);
            }
            
            // Log dei parametri ricevuti
            \Log::info('Filtri ricevuti:', $request->all());
            
            $videos = $query->paginate(10);
            $videos->each(function ($video) {
                $video->youtube_id = $this->getYoutubeVideoId($video->yt_link);
            });
            
            if ($request->ajax()) {
                return response()->json([
                    'html' => view('partials.video_list', compact('videos'))->render()
                ]);
            }
            
            $minYear = Video::min('year');
            $maxYear = Video::max('year');
            $minDuration = Video::min('duration');
            $maxDuration = Video::max('duration');
            
            return view('sections.archivio', compact('videos', 'minYear', 'maxYear', 'minDuration', 'maxDuration'));
            
        } catch (\Exception $e) {
            \Log::error('Errore in archivio(): ' . $e->getMessage());
            return response()->json(['error' => 'Errore interno del server'], 500);
        }
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
   
    public function fuoriDalFrame()
    {
        $videoList = Video::where('tags', 'like', '%fuori dal frame%')->get();
        return view('sections.fuori-dal-frame', compact('videoList'));
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
    
            // Recupera i video paginati
            $videos = $query->paginate(10);
    
            // Assegna gli ID YouTube ai video
            $videos->each(function ($video) {
                $youtubeId = $this->getYoutubeVideoId($video->yt_link);
                $video->youtube_id = $youtubeId ?: null; // Fallback
            });
    
            // Recupera solo le location valide (filtrando i dati malformati)
            $locationList = $query->pluck('location')
                ->filter()
                ->map(function ($loc) {
                    if (!is_string($loc)) return null;
                    $parts = explode(',', $loc);
                    if (count($parts) === 2) {
                        $lat = floatval(trim($parts[0]));
                        $lng = floatval(trim($parts[1]));
                        if (is_numeric($lat) && is_numeric($lng)) {
                            return ['lat' => $lat, 'lng' => $lng];
                        }
                    }
                    return null;
                })
                ->filter()
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
    
            // Calcola i valori minimi e massimi per i filtri
            $minYear = Video::where('tags', 'like', '%Fuori dal Tacco%')->min('year');
            $maxYear = Video::where('tags', 'like', '%Fuori dal Tacco%')->max('year');
            $minDuration = Video::where('tags', 'like', '%Fuori dal Tacco%')->min('duration');
            $maxDuration = Video::where('tags', 'like', '%Fuori dal Tacco%')->max('duration');
    
            // Restituisce la vista completa
            return view('sections.fuori-dal-tacco', compact(
                'videos',
                'minYear', 'maxYear',
                'minDuration', 'maxDuration',
                'locationList'
            ));
    
        } catch (\Exception $e) {
            // Logga l'errore e restituisce una risposta di errore
            \Log::error('Errore in fuoriDalTacco(): ' . $e->getMessage());
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
}