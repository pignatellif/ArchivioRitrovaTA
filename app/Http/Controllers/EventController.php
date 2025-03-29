<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::all();
        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        $event = new Event;
        $event->title = $request->title;
        $event->description = $request->description;
        $event->date = $request->date;
    
        if ($request->hasFile('cover_image')) {
            $imageName = time() . '.' . $request->cover_image->extension();
            $request->cover_image->move(public_path('img/events_cover'), $imageName);
            $event->cover_image = 'img/events_cover/' . $imageName;
        }
    
        $event->save();
    
        return redirect()->route('events.index')->with('success', 'Evento creato con successo!');
    }
    
    public function update(Request $request, Event $event)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        $event->title = $request->title;
        $event->description = $request->description;
        $event->date = $request->date;
    
        if ($request->hasFile('cover_image')) {
            // Delete the old image if it exists
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

    public function edit(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    public function destroy(Event $event)
    {
        // Delete the image if it exists
        if ($event->cover_image) {
            Storage::disk('public')->delete($event->cover_image);
        }
        $event->delete();
        return redirect()->route('events.index')->with('success', 'Evento eliminato con successo!');
    }
}