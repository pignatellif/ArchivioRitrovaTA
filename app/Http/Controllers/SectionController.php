<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Autore;
use App\Models\Formato;
use App\Models\Serie;
use App\Models\Tag;
use App\Models\Video;
use App\Models\Location;
use App\Models\Evento;
use App\Models\Riconoscimento;
use App\Models\Famiglia;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Collection;

class SectionController extends Controller
{
    public function home()
    {
        $recentEvents = Evento::orderBy('start_date', 'desc')->take(3)->get();
        return view('home', compact('recentEvents'));
    }

    public function index(Request $request)
    {
        $query = $request->input('query');
        // Ricerca sia su video che su autori (esempio)
        $risultati = [
            'video' => Video::where('titolo', 'like', "%{$query}%")->get(),
            'autori' => Autore::where('nome', 'like', "%{$query}%")->get()
        ];
        return view('search.results', compact('query', 'risultati'));
    }

    public function video($id)
    {
        $video = Video::with(['tags', 'location', 'famiglie', 'series'])->findOrFail($id);
    
        $similarVideos = Video::where('id', '!=', $video->id)
            ->where(function ($query) use ($video) {
                $query->whereHas('tags', function ($q) use ($video) {
                    $q->whereIn('tags.id', $video->tags->pluck('id'));
                });
    
                if ($video->location_id) {
                    $query->orWhere('location_id', $video->location_id);
                }
    
                if ($video->famiglie && $video->famiglie->count()) {
                    $query->orWhereHas('famiglie', function ($q) use ($video) {
                        $q->whereIn('famiglie.id', $video->famiglie->pluck('id'));
                    });
                }
    
                if ($video->series && $video->series->count()) {
                    $query->orWhereHas('series', function ($q) use ($video) {
                        $q->whereIn('series.id', $video->series->pluck('id'));
                    });
                }
            })
            ->with(['tags', 'location'])
            ->distinct()
            ->limit(6)
            ->get();
    
        return view('sections.video', compact('video', 'similarVideos'));
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
            // Supporta relazione con Formato
            $query->whereHas('formato', function ($q) use ($filters) {
                $q->where('nome', $filters['format']);
            });
        }

        if (!empty($filters['family'])) {
            $query->whereHas('famiglie', function ($q) use ($filters) {
                $q->where('famiglie.nome', $filters['family']); 
            });
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
        $filters = $request->only([
            'title', 'min_year', 'max_year', 'author',
            'min_duration', 'max_duration', 'format', 
            'family', 'location', 'tags', 'view'
        ]);
        $view = $filters['view'] ?? 'grid';

        $baseQuery = Video::whereDoesntHave('tags', function ($q) {
            $q->whereIn('nome', ['Fuori dal frame', 'Fuori dal Tacco']);
        });

        $this->applyFilters($baseQuery, $filters);

        $videos = $baseQuery->with(['location', 'autore', 'tags', 'formato'])->paginate(18);

        if ($request->ajax()) {
            $html = ($view === 'map')
                ? view('partials.map', ['locations' => $videos->pluck('location')->unique()])->render()
                : view('partials.grid', compact('videos'))->render();
            return response()->json(['html' => $html]);
        }

        $filteredVideoIds = (clone $baseQuery)->pluck('id');
        $filtersData = [
            'minYear'      => (clone $baseQuery)->min('anno'),
            'maxYear'      => (clone $baseQuery)->max('anno'),
            'minDuration'  => (clone $baseQuery)->min('durata_secondi'),
            'maxDuration'  => (clone $baseQuery)->max('durata_secondi'),
            'authors'      => Autore::whereIn('id', (clone $baseQuery)->distinct()->pluck('autore_id'))->pluck('nome'),
            'formats'      => Formato::whereIn('id', (clone $baseQuery)->distinct()->pluck('formato_id'))->pluck('nome'),
            'families' => Famiglia::whereIn(
                    'id',
                    \DB::table('video_famiglia')->whereIn('video_id', $filteredVideoIds)->pluck('famiglia_id')
                )->pluck('nome'),
            'locations'    => Location::whereIn('id', (clone $baseQuery)->distinct()->pluck('location_id'))->pluck('name'),
            'tags'         => Tag::whereNotIn('nome', ['Fuori dal frame', 'Fuori dal Tacco'])
                                ->whereIn('id', \DB::table('tag_video')->whereIn('video_id', $filteredVideoIds)->pluck('tag_id'))
                                ->pluck('nome'),
        ];

        return view('sections.archivio', array_merge(
            compact('videos'), $filtersData
        ));
    }
    
    public function serie(Request $request)
    {
        try {
            $series = Serie::with(['videos.formato', 'videos.autore'])->paginate(10);

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
            $filters = $request->only([
                'title', 'min_year', 'max_year', 'author',
                'min_duration', 'max_duration', 'format', 
                'family', 'location', 'tags', 'view'
            ]);
            $view = $filters['view'] ?? 'grid';

            $query = Video::whereHas('tags', function ($q) {
                $q->where('nome', 'Fuori dal Tacco');
            });
            $this->applyFilters($query, $filters);

            $videos = $query->with(['location', 'autore', 'tags', 'formato'])->paginate(18);

            if ($request->ajax()) {
                $html = ($view === 'map')
                    ? view('partials.map', ['locations' => $videos->pluck('location')->unique()])->render()
                    : view('partials.grid', compact('videos'))->render();
                return response()->json(['html' => $html]);
            }

            $filteredVideoIds = (clone $query)->pluck('id');

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
                'formats' => Formato::whereIn('id', Video::whereHas('tags', function ($q) {
                    $q->where('nome', 'Fuori dal Tacco');
                })->distinct()->pluck('formato_id'))->pluck('nome'),
                'families' => Famiglia::whereIn(
                        'id',
                        \DB::table('video_famiglia')->whereIn('video_id', $filteredVideoIds)->pluck('famiglia_id')
                    )->pluck('nome'),
                'locations' => Location::whereHas('videos.tags', function ($q) {
                    $q->where('nome', 'Fuori dal Tacco');
                })->pluck('name'),
                'tags' => Tag::whereHas('videos.tags', function ($q) {
                    $q->where('nome', 'Fuori dal Tacco');
                })
                ->whereNotIn('nome', ['Fuori dal frame', 'Fuori dal Tacco'])
                ->pluck('nome')
            ];

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
        // Recupera tutti i formati
        $formati = Formato::with(['autori.videos'])->get();
    
        $formatiAutori = $formati->mapWithKeys(function ($formato) {
            $autori = $formato->autori->filter(function ($autore) use ($formato) {
                // Cerca almeno un video di quell'autore con quel formato
                return $autore->videos()->where('formato_id', $formato->id)->exists();
            })->unique('id')->values();
    
            return [$formato->nome => $autori];
        });
    
        return view('sections.autori', ['formati' => $formatiAutori]);
    }

    public function showAutore($id)
    {
        try {
            $autore = Autore::with(['formati'])->findOrFail($id);
            $videos = $autore->videos()->with(['formato', 'tags', 'location'])->paginate(12);

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

            $query = Video::whereHas('tags', function ($q) {
                $q->where('nome', 'Fuori dal frame');
            });

            $this->applyFilters($query, $filters);

            $videos = $query->with(['location', 'autore', 'tags', 'formato'])->paginate(18);

            if ($request->ajax()) {
                $html = view('partials.grid', compact('videos'))->render();
                return response()->json(['html' => $html]);
            }

            $filteredVideoIds = (clone $query)->pluck('id');

            $filtersData = [
                'minDuration' => Video::whereHas('tags', fn($q) => $q->where('nome', 'Fuori dal frame'))->min('durata_secondi'),
                'maxDuration' => Video::whereHas('tags', fn($q) => $q->where('nome', 'Fuori dal frame'))->max('durata_secondi'),
                'authors' => Autore::whereHas('videos.tags', fn($q) => $q->where('nome', 'Fuori dal frame'))->pluck('nome'),
                'formats' => Formato::whereIn('id', Video::whereHas('tags', fn($q) => $q->where('nome', 'Fuori dal frame'))->distinct()->pluck('formato_id'))->pluck('nome'),
                'families' => Famiglia::whereIn(
                        'id',
                        \DB::table('video_famiglia')->whereIn('video_id', $filteredVideoIds)->pluck('famiglia_id')
                    )->pluck('nome'),
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
        $events = Evento::query();

        if ($query) {
            $events = $events->where('titolo', 'like', '%' . $query . '%')
                             ->orWhere('descrizione', 'like', '%' . $query . '%')
                             ->orWhere('start_date', 'like', '%' . $query . '%')
                             ->orWhere('end_date', 'like', '%' . $query . '%')
                             ->orWhere('luogo', 'like', '%' . $query . '%');
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
        $riconoscimenti = Riconoscimento::orderBy('data_pubblicazione', 'desc')
                                      ->orderBy('created_at', 'desc')
                                      ->get();
        return view('sections.dicono-di-noi', compact('riconoscimenti'));
    }
}