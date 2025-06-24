<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Evento;
use App\Models\EventContent;
use Illuminate\Support\Facades\Storage;

class EventContentController extends Controller
{
    public function create(Evento $event)
    {
        // Recupera tutti i contenuti esistenti per questo evento
        $contents = EventContent::where('event_id', $event->id)->orderBy('created_at', 'desc')->get();
        
        return view('admin.events.contents.create', compact('event', 'contents'));
    }

    public function store(Request $request, Evento $event)
    {
        // Validazione base
        $rules = [
            'template_type' => 'required|string|in:testo,galleria,video',
            'content' => 'array',
            'immagini.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:8192',
            'video' => 'nullable|file|mimes:mp4,mov,avi,wmv,flv,webm|max:102400',
        ];
    
        // Validazione condizionale
        if ($request->template_type === 'testo') {
            $rules['content.testo'] = 'required|string|max:10000';
        } elseif ($request->template_type === 'galleria') {
            $rules['immagini'] = 'required|array|min:1';
        } elseif ($request->template_type === 'video') {
            $rules['content.url'] = 'nullable|url';
            // Almeno uno tra video file o URL deve essere presente
            $request->validate($rules);
            if (!$request->hasFile('video') && empty($request->input('content.url'))) {
                return back()->withErrors(['video' => 'Devi caricare un video o inserire un URL.']);
            }
        }
    
        $request->validate($rules);
    
        $data = $request->input('content', []);
    
        try {
            // Gestione galleria immagini
            if ($request->template_type === 'galleria' && $request->hasFile('immagini')) {
                $immagini_paths = [];
                foreach ($request->file('immagini') as $image) {
                    $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $path = $image->storeAs('eventi/immagini', $filename, 'public');
                    $immagini_paths[] = $path;
                }
                $data['immagini'] = $immagini_paths;
            }
    
            // Gestione video
            if ($request->template_type === 'video' && $request->hasFile('video')) {
                $video = $request->file('video');
                $filename = time() . '_' . uniqid() . '.' . $video->getClientOriginalExtension();
                $path = $video->storeAs('eventi/video', $filename, 'public');
                $data['url'] = $path;
            }
    
            // Calcola il prossimo ordine
            $nextOrder = $event->contents()->max('order');
            $nextOrder = (is_null($nextOrder) || $nextOrder == 0) ? 1 : $nextOrder + 1;
    
            // Creazione del record
            EventContent::create([
                'event_id'      => $event->id,
                'template_type' => $request->template_type,
                'content'       => $data,
                'order'         => $nextOrder,
            ]);
    
            return redirect()->route('events.contents.create', $event)
                ->with('success', 'Contenuto aggiunto con successo!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Errore durante il caricamento: ' . $e->getMessage()]);
        }
    }

    public function update(Request $request, Evento $event, EventContent $content)
    {
        $rules = [
            'template_type' => 'required|string|in:testo,galleria,video',
            'content' => 'required|array',
            'immagini.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:8192',
            'video' => 'nullable|file|mimes:mp4,mov,avi,wmv,flv,webm|max:102400',
        ];

        $request->validate($rules);

        try {
            $data = $request->input('content', []);
            
            // Mantieni i dati esistenti
            $existingContent = $content->content ?? [];
            
            // Gestione galleria - aggiungi nuove immagini a quelle esistenti
            if ($request->template_type === 'galleria' && $request->hasFile('immagini')) {
                $existingImages = $existingContent['immagini'] ?? [];
                $newImages = [];
                
                foreach ($request->file('immagini') as $image) {
                    $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $path = $image->storeAs('eventi/immagini', $filename, 'public');
                    $newImages[] = $path;
                }
                
                $data['immagini'] = array_merge($existingImages, $newImages);
            } elseif ($request->template_type === 'galleria') {
                // Mantieni le immagini esistenti se non ne sono state caricate di nuove
                $data['immagini'] = $existingContent['immagini'] ?? [];
            }

            // Gestione video
            if ($request->template_type === 'video' && $request->hasFile('video')) {
                // Elimina il video precedente se esisteva
                if (isset($existingContent['url']) && \Illuminate\Support\Str::startsWith($existingContent['url'], 'eventi/video')) {
                    Storage::disk('public')->delete($existingContent['url']);
                }
                
                $video = $request->file('video');
                $filename = time() . '_' . uniqid() . '.' . $video->getClientOriginalExtension();
                $path = $video->storeAs('eventi/video', $filename, 'public');
                $data['url'] = $path;
            }

            $content->update([
                'template_type' => $request->template_type,
                'content' => $data,
            ]);

            return redirect()->route('events.contents.create', $event)
                ->with('success', 'Contenuto aggiornato con successo!');
                
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Errore durante l\'aggiornamento: ' . $e->getMessage()]);
        }
    }

    public function destroy(Evento $event, EventContent $content)
    {
        try {
            // Elimina i file associati
            if ($content->content) {
                $contentData = $content->content;
                
                // Elimina immagini della galleria
                if (isset($contentData['immagini']) && is_array($contentData['immagini'])) {
                    foreach ($contentData['immagini'] as $imagePath) {
                        Storage::disk('public')->delete($imagePath);
                    }
                }
                
                // Elimina video se è un file locale
                if (isset($contentData['url']) && \Illuminate\Support\Str::startsWith($contentData['url'], 'eventi/video')) {
                    Storage::disk('public')->delete($contentData['url']);
                }
            }
            
            $content->delete();
            
            return redirect()->route('events.contents.create', $event)
                ->with('success', 'Contenuto eliminato con successo!');
                
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Errore durante l\'eliminazione: ' . $e->getMessage()]);
        }
    }

    public function reorder(Request $request, Evento $event)
    {
        try {
            $contentIds = $request->input('content_ids', []);
            
            if (empty($contentIds)) {
                return response()->json(['success' => false, 'message' => 'Nessun contenuto da riordinare']);
            }
            
            // Aggiorna l'ordine per ogni contenuto
            foreach ($contentIds as $index => $contentId) {
                $event->contents()
                    ->where('id', $contentId)
                    ->update(['order' => $index + 1]); // Inizia da 1, non da 0
            }
            
            return response()->json(['success' => true, 'message' => 'Ordine aggiornato con successo']);
            
        } catch (\Exception $e) {
            \Log::error('Errore nel riordinamento contenuti: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Errore durante il salvataggio']);
        }
    }

    private function processContentData(Request $request)
    {
        $contentData = [];
        
        switch ($request->template_type) {
            case 'testo':
                $contentData['testo'] = $request->input('content.testo');
                break;
                
            case 'galleria':
                // Gestisci upload immagini
                if ($request->hasFile('immagini')) {
                    $immagini = [];
                    foreach ($request->file('immagini') as $file) {
                        $path = $file->store('eventi/immagini', 'public');
                        $immagini[] = $path;
                    }
                    $contentData['immagini'] = $immagini;
                }
                break;
                
            case 'video':
                if ($request->hasFile('video')) {
                    $path = $request->file('video')->store('eventi/video', 'public');
                    $contentData['url'] = $path;
                } elseif ($request->input('content.url')) {
                    $contentData['url'] = $request->input('content.url');
                }
                break;
        }
        
        return $contentData;
    }

}