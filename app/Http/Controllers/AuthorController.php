<?php

namespace App\Http\Controllers;

use App\Models\Autore;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function index()
    {
        $authors = Autore::all();
        return view('admin.authors.index', compact('authors'));
    }

    public function create()
    {
        // Se vuoi visualizzare anche i formati disponibili per l'autore
        $formati = \App\Models\Formato::all();
        return view('admin.authors.create', compact('formati'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'anno_nascita' => 'nullable|integer',
            'biografia' => 'nullable|string',
            'immagine_profilo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'formati' => 'nullable|array',
            'formati.*' => 'exists:formati,id',
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

        // Gestione relazione con formati
        if ($request->has('formati')) {
            $autore->formati()->sync($request->formati);
        }

        return redirect()->route('authors.index')->with('success', 'Autore creato con successo!');
    }

    public function edit(Autore $author)
    {
        $formati = \App\Models\Formato::all();
        $author->load('formati');
        return view('admin.authors.edit', compact('author', 'formati'));
    }

    public function update(Request $request, Autore $author)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'anno_nascita' => 'nullable|integer',
            'biografia' => 'nullable|string',
            'immagine_profilo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'remove_immagine_profilo' => 'nullable|boolean',
            'formati' => 'nullable|array',
            'formati.*' => 'exists:formati,id',
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

        // Gestione relazione con formati
        if ($request->has('formati')) {
            $author->formati()->sync($request->formati);
        } else {
            $author->formati()->detach();
        }

        return redirect()->route('authors.index')->with('success', 'Autore aggiornato con successo!');
    }

    public function destroy(Autore $author)
    {
        if ($author->immagine_profilo && file_exists(public_path($author->immagine_profilo))) {
            unlink(public_path($author->immagine_profilo));
        }

        // Rimuovi relazioni con formati
        $author->formati()->detach();

        $author->delete();

        return redirect()->route('authors.index')->with('success', 'Autore eliminato con successo!');
    }
}