<?php

namespace App\Http\Controllers;

use App\Models\Riconoscimento;
use Illuminate\Http\Request;

class RiconoscimentoController extends Controller
{
    // Lista di tutti i riconoscimenti
    public function index()
    {
        $riconoscimenti = Riconoscimento::orderBy('data_pubblicazione', 'desc')->paginate(15);
        return view('admin.riconoscimenti.index', compact('riconoscimenti'));
    }

    // Mostra il form per creare un nuovo riconoscimento
    public function create()
    {
        return view('admin.riconoscimenti.create');
    }

    // Salva un nuovo riconoscimento
    public function store(Request $request)
    {
        $request->validate([
            'titolo' => 'required|string|max:255',
            'fonte' => 'nullable|string|max:255',
            'url' => 'required|url|max:255',
            'data_pubblicazione' => 'nullable|date',
            'estratto' => 'nullable|string',
        ]);

        Riconoscimento::create($request->only([
            'titolo', 'fonte', 'url', 'data_pubblicazione', 'estratto'
        ]));

        return redirect()->route('riconoscimenti.index')
            ->with('success', 'Riconoscimento aggiunto con successo.');
    }

    // Mostra il form per modificare un riconoscimento
    public function edit(Riconoscimento $riconoscimento)
    {
        return view('admin.riconoscimenti.edit', compact('riconoscimento'));
    }

    // Aggiorna un riconoscimento esistente
    public function update(Request $request, Riconoscimento $riconoscimento)
    {
        $request->validate([
            'titolo' => 'required|string|max:255',
            'fonte' => 'nullable|string|max:255',
            'url' => 'required|url|max:255',
            'data_pubblicazione' => 'nullable|date',
            'estratto' => 'nullable|string',
        ]);

        $riconoscimento->update($request->only([
            'titolo', 'fonte', 'url', 'data_pubblicazione', 'estratto'
        ]));

        return redirect()->route('riconoscimenti.index')
            ->with('success', 'Riconoscimento aggiornato con successo.');
    }

    // Elimina un riconoscimento
    public function destroy(Riconoscimento $riconoscimento)
    {
        $riconoscimento->delete();

        return redirect()->route('riconoscimenti.index')
            ->with('success', 'Riconoscimento eliminato con successo.');
    }
}