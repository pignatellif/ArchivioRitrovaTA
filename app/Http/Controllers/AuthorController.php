<?php

namespace App\Http\Controllers;

use App\Models\Autore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AuthorController extends Controller
{
    public function index()
    {
        $authors = Autore::all();
        return view('admin.authors.index', compact('authors'));
    }

    public function create()
    {
        return view('admin.authors.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'anno_nascita' => 'nullable|integer',
            'biografia' => 'nullable|string',
            'immagine_profilo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $autore = new Autore();
        $autore->nome = $request->nome;
        $autore->anno_nascita = $request->anno_nascita;
        $autore->biografia = $request->biografia;

        if ($request->hasFile('immagine_profilo')) {
            $imageName = time() . '.' . $request->immagine_profilo->extension();
            $request->immagine_profilo->move(public_path('img/authors'), $imageName);
            $autore->immagine_profilo = 'img/authors/' . $imageName;
        }

        $autore->save();

        return redirect()->route('authors.index')->with('success', 'Autore creato con successo!');
    }

    public function edit(Autore $author)
    {
        return view('admin.authors.edit', compact('author'));
    }

    public function update(Request $request, Autore $author)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'anno_nascita' => 'nullable|integer',
            'biografia' => 'nullable|string',
            'immagine_profilo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $author->nome = $request->nome;
        $author->anno_nascita = $request->anno_nascita;
        $author->biografia = $request->biografia;

        if ($request->has('remove_immagine_profilo') && $request->remove_immagine_profilo) {
            if ($author->immagine_profilo && file_exists(public_path($author->immagine_profilo))) {
                unlink(public_path($author->immagine_profilo));
            }
            $author->immagine_profilo = null;
        }

        if ($request->hasFile('immagine_profilo')) {
            if ($author->immagine_profilo && file_exists(public_path($author->immagine_profilo))) {
                unlink(public_path($author->immagine_profilo));
            }

            $imageName = time() . '.' . $request->immagine_profilo->extension();
            $request->immagine_profilo->move(public_path('img/authors'), $imageName);
            $author->immagine_profilo = 'img/authors/' . $imageName;
        }

        $author->save();

        return redirect()->route('authors.index')->with('success', 'Autore aggiornato con successo!');
    }

    public function destroy(Autore $author)
    {
        if ($author->immagine_profilo && file_exists(public_path($author->immagine_profilo))) {
            unlink(public_path($author->immagine_profilo));
        }

        $author->delete();

        return redirect()->route('authors.index')->with('success', 'Autore eliminato con successo!');
    }
}
