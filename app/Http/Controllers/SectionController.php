<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Video;
use App\Models\Series;
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
        return view('sections.fuori-dal-frame');
    }

    public function fuoriDalTacco(Request $request)
    {
        try {
            $query = Video::where('tags', 'like', '%Fuori dal Tacco%');
            
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
    
            // Recupera i luoghi dei video con il tag Fuori dal Tacco
            $locations = Video::where('tags', 'like', '%Fuori dal Tacco%')->select('location')->distinct()->get();
            $locations = $locations->pluck('location')->toArray();
    
            // Log dei parametri ricevuti
            \Log::info('Filtri ricevuti:', $request->all());
            
            $videos = $query->paginate(10);
            $videos->each(function ($video) {
                $video->youtube_id = $this->getYoutubeVideoId($video->yt_link);
            });
            
            if ($request->ajax()) {
                return response()->json([
                    'html' => view('partials.fuori-dal-tacco_list', compact('videos'))->render()
                ]);
            }
            
            $minYear = Video::where('tags', 'like', '%Fuori dal Tacco%')->min('year');
            $maxYear = Video::where('tags', 'like', '%Fuori dal Tacco%')->max('year');
            $minDuration = Video::where('tags', 'like', '%Fuori dal Tacco%')->min('duration');
            $maxDuration = Video::where('tags', 'like', '%Fuori dal Tacco%')->max('duration');
            
            return view('sections.fuori-dal-tacco', compact('videos', 'minYear', 'maxYear', 'minDuration', 'maxDuration', 'locations'));
            
        } catch (\Exception $e) {
            \Log::error('Errore in fuoriDalTacco(): ' . $e->getMessage());
            return response()->json(['error' => 'Errore interno del server'], 500);
        }
    }
    
    public function eventi()
    {
        return view('sections.eventi');
    }

    public function sostienici()
    {
        return view('sections.sostienici');
    }
}