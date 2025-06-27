<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Video;
use App\Models\Serie;
use App\Models\Evento;
use App\Models\Autore;
use App\Models\Riconoscimento;
use Carbon\Carbon;

class AdminController extends Controller
{

    public function index()
    {
        // Statistiche rapide
        $totalVideos = Video::count();
        $totalSeries = Serie::count();
        $totalEvents = Evento::count();
        $totalAuthors = Autore::count();
        $totalRiconoscimenti = Riconoscimento::count();

        // Statistiche aggiuntive
        $videosThisMonth = Video::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        $eventsUpcoming = Evento::whereDate('start_date', '>=', Carbon::today())->count();

        // Attività recenti (ultimi 8 elementi tra video, eventi, autori, serie)
        $recentVideos = Video::with('autore')
            ->latest()
            ->limit(3)
            ->get()
            ->map(function ($video) {
                return [
                    'type' => 'video',
                    'icon' => 'bi-film',
                    'color' => 'primary',
                    'message' => 'Nuovo video caricato: "' . $video->titolo . '"',
                    'author' => $video->autore->nome ?? 'Admin',
                    'created_at' => $video->created_at
                ];
            });

        $recentEvents = Evento::latest()
            ->limit(3)
            ->get()
            ->map(function ($event) {
                return [
                    'type' => 'event',
                    'icon' => 'bi-calendar-event',
                    'color' => 'success',
                    'message' => 'Evento programmato: "' . $event->titolo . '"',
                    'author' => 'Admin',
                    'created_at' => $event->created_at
                ];
            });

        $recentAuthors = Autore::latest()
            ->limit(2)
            ->get()
            ->map(function ($author) {
                return [
                    'type' => 'author',
                    'icon' => 'bi-person-plus',
                    'color' => 'warning',
                    'message' => 'Nuovo autore registrato: "' . $author->nome . '"',
                    'author' => 'Sistema',
                    'created_at' => $author->created_at
                ];
            });

        $recentSeries = Serie::latest()
            ->limit(2)
            ->get()
            ->map(function ($series) {
                return [
                    'type' => 'series',
                    'icon' => 'bi-collection-play',
                    'color' => 'secondary',
                    'message' => 'Nuova serie creata: "' . $series->nome . '"',
                    'author' => 'Admin',
                    'created_at' => $series->created_at
                ];
            });

        $recentActivities = $recentVideos
            ->concat($recentEvents)
            ->concat($recentAuthors)
            ->concat($recentSeries)
            ->sortByDesc('created_at')
            ->take(8);

        // Statistiche per grafici (ultimi 6 mesi)
        $monthlyStats = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthlyStats[] = [
                'month' => $date->format('M Y'),
                'videos' => Video::whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->count(),
                'events' => Evento::whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->count(),
                'authors' => Autore::whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->count()
            ];
        }

        // Top autori per numero di video
        $topAuthors = Autore::withCount('videos')
            ->orderByDesc('videos_count')
            ->limit(5)
            ->get();

        // Prossimi eventi (5 più vicini in ordine di data)
        $upcomingEvents = Evento::whereDate('start_date', '>=', Carbon::today())
            ->orderBy('start_date', 'asc')
            ->limit(5)
            ->get();

        // Statistiche di sistema
        $systemStats = [
            'disk_usage' => $this->getDiskUsage(),
            'avg_video_duration' => Video::avg('durata_secondi'),
            'last_backup' => Carbon::now()->subDays(1)->format('d/m/Y H:i')
        ];

        return view('admin.dashboard', compact(
            'totalVideos',
            'totalSeries',
            'totalEvents',
            'totalAuthors',
            'totalRiconoscimenti',
            'videosThisMonth',
            'eventsUpcoming',
            'recentActivities',
            'monthlyStats',
            'topAuthors',
            'upcomingEvents',
            'systemStats'
        ));
    }

    /**
     * Calcola l'utilizzo dello spazio disco (in MB)
     */
    private function getDiskUsage()
    {
        $path = storage_path();
        $freeBytes = disk_free_space($path);
        $totalBytes = disk_total_space($path);

        if ($freeBytes !== false && $totalBytes !== false) {
            $usedBytes = $totalBytes - $freeBytes;
            $usedMB = round($usedBytes / 1024 / 1024, 2);
            $totalMB = round($totalBytes / 1024 / 1024, 2);
            $percentage = round(($usedBytes / $totalBytes) * 100, 2);

            return [
                'used' => $usedMB,
                'total' => $totalMB,
                'percentage' => $percentage,
                'free' => round($freeBytes / 1024 / 1024, 2)
            ];
        }

        return null;
    }

    /**
     * API endpoint per ottenere statistiche in tempo reale
     */
    public function getStats()
    {
        return response()->json([
            'total_videos' => Video::count(),
            'total_series' => Serie::count(),
            'total_events' => Evento::count(),
            'total_authors' => Autore::count(),
            'videos_this_month' => Video::whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->count(),
            'events_upcoming' => Evento::whereDate('start_date', '>=', Carbon::today())->count()
        ]);
    }

    /**
     * API endpoint per ottenere le attività recenti
     */
    public function getRecentActivities()
    {
        $recentVideos = Video::with('autore')
            ->latest()
            ->limit(5)
            ->get()
            ->map(function($video) {
                return [
                    'type' => 'video',
                    'icon' => 'bi-film',
                    'color' => 'primary',
                    'message' => 'Nuovo video: "' . $video->titolo . '"',
                    'time' => $video->created_at->diffForHumans()
                ];
            });

        $recentEvents = Evento::latest()
            ->limit(3)
            ->get()
            ->map(function($event) {
                return [
                    'type' => 'event',
                    'icon' => 'bi-calendar-event',
                    'color' => 'success',
                    'message' => 'Evento programmato: "' . $event->titolo . '"',
                    'time' => $event->created_at->diffForHumans()
                ];
            });

        $recentActivities = collect()
            ->concat($recentVideos)
            ->concat($recentEvents)
            ->sortByDesc(function($item) {
                // Usa la data per ordinare
                return isset($item['created_at']) ? $item['created_at'] : now();
            })
            ->take(8)
            ->values();

        return response()->json($recentActivities);
    }
}