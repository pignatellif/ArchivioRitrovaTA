<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function index()
    {
        $videos = Video::all();
        return view('admin.videos.index', compact('videos'));
    }

    public function create()
    {
        return view('admin.videos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'year' => 'required|integer',
            'duration' => 'required|integer',
            'title' => 'required|string',
            'format' => 'required|string',
            'family' => 'required|string',
            'location' => 'required|string',
            'tags' => 'nullable|string',
            'yt_link' => 'required|url',
            'author' => 'required|string',
            'description' => 'required|string',
        ]);

        Video::create($request->all());

        return redirect()->route('videos.index')->with('success', 'Video aggiunto con successo!');
    }

    public function edit(Video $video)
    {
        return view('admin.videos.edit', compact('video'));
    }

    public function update(Request $request, Video $video)
    {
        $request->validate([
            'year' => 'required|integer',
            'duration' => 'required|integer',
            'title' => 'required|string',
            'format' => 'required|string',
            'family' => 'required|string',
            'location' => 'required|string',
            'tags' => 'nullable|string',
            'yt_link' => 'required|url',
            'author' => 'required|string',
            'description' => 'required|string',
        ]);

        $video->update($request->all());

        return redirect()->route('videos.index')->with('success', 'Video aggiornato con successo!');
    }

    public function destroy(Video $video)
    {
        $video->delete();
        return redirect()->route('videos.index')->with('success', 'Video eliminato con successo!');
    }
}
