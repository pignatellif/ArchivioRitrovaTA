<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function index()
    {
        $events = Evento::all();
        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'titolo' => 'required|string|max:255',
            'descrizione' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'luogo' => 'required|string|max:255',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        $event = new Evento;
        $event->titolo = $request->titolo;
        $event->descrizione = $request->descrizione;
        $event->start_date = $request->start_date;
        $event->end_date = $request->end_date;
        $event->luogo = $request->luogo;
    
        if ($request->hasFile('cover_image')) {
            $imageName = time() . '.' . $request->cover_image->extension();
            $request->cover_image->move(public_path('img/events_cover'), $imageName);
            $event->cover_image = 'img/events_cover/' . $imageName;
        }
    
        $event->save();
    
        return redirect()->route('events.index')->with('success', 'Evento creato con successo!');
    }
    
    public function removeCoverImage(Evento $event)
    {
        if ($event->cover_image && file_exists(public_path($event->cover_image))) {
            unlink(public_path($event->cover_image));
            $event->cover_image = null;
            $event->save();
        }

        return response()->json(['success' => true, 'message' => 'Immagine di copertina rimossa con successo.']);
    }
    
    public function update(Request $request, Evento $event)
    {
        $request->validate([
            'titolo' => 'required|string|max:255',
            'descrizione' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'luogo' => 'required|string|max:255',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        $event->titolo = $request->titolo;
        $event->descrizione = $request->descrizione;
        $event->start_date = $request->start_date;
        $event->end_date = $request->end_date;
        $event->luogo = $request->luogo;
    
        // Rimozione immagine di copertina
        if ($request->has('remove_cover_image') && $request->remove_cover_image) {
            if ($event->cover_image && file_exists(public_path($event->cover_image))) {
                unlink(public_path($event->cover_image));
            }
            $event->cover_image = null;
        }
    
        // Aggiornamento immagine
        if ($request->hasFile('cover_image')) {
            // Elimina l'immagine esistente se presente
            if ($event->cover_image && file_exists(public_path($event->cover_image))) {
                unlink(public_path($event->cover_image));
            }
    
            $imageName = time() . '.' . $request->cover_image->extension();
            $request->cover_image->move(public_path('img/events_cover'), $imageName);
            $event->cover_image = 'img/events_cover/' . $imageName;
        }
    
        $event->save();
    
        return redirect()->route('events.index')->with('success', 'Evento aggiornato con successo!');
    }

    public function edit(Evento $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    public function destroy(Evento $event)
    {
        // Delete the image if it exists
        if ($event->cover_image) {
            Storage::disk('public')->delete($event->cover_image);
        }
        $event->delete();
        return redirect()->route('events.index')->with('success', 'Evento eliminato con successo!');
    }
}