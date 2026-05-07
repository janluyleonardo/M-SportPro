<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index()
    {
        $locations = Location::orderBy('name')->get();
        return view('locations.index', compact('locations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:locations,name',
            'description' => 'nullable|string|max:255',
        ]);

        Location::create([
            'name'        => $request->name,
            'description' => $request->description,
            'active'      => true,
        ]);

        return back()->with('success', 'Cancha "' . $request->name . '" agregada correctamente.');
    }

    public function update(Request $request, Location $location)
    {
        if ($request->has('toggle_active')) {
            $location->update(['active' => !$location->active]);
            $estado = $location->active ? 'activada' : 'desactivada';
            return back()->with('success', "Cancha \"{$location->name}\" {$estado}.");
        }

        $request->validate([
            'name' => 'required|string|max:100|unique:locations,name,' . $location->id,
            'description' => 'nullable|string|max:255',
        ]);

        $location->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return back()->with('success', 'Cancha actualizada correctamente.');
    }

    public function destroy(Location $location)
    {
        $location->delete();
        return back()->with('success', 'Cancha eliminada correctamente.');
    }
}
