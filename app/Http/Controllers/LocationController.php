<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LocationController extends Controller
{
    public function index()
    {
        $locations = Location::orderBy('name')->paginate(20);
        return view('admin.locations.index', compact('locations'));
    }

    public function edit(Location $location)
    {
        return view('admin.locations.edit', compact('location'));
    }

    public function update(Request $request, Location $location)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'lat' => 'nullable|numeric',
            'lon' => 'nullable|numeric',
        ]);

        $location->update($request->only('name', 'lat', 'lon'));

        return redirect()->route('locations.index')->with('success', 'Località aggiornata con successo.');
    }

    public function destroy(Location $location)
    {
        $location->delete();
        return redirect()->route('locations.index')->with('success', 'Località eliminata con successo.');
    }
}