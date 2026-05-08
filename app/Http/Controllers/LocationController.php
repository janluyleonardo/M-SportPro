<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use App\Http\Requests\StoreLocationRequest;

class LocationController extends Controller
{
    public function index()
    {
        $locations = Location::orderBy('name')->get();
        return view('locations.index', compact('locations'));
    }

    public function store(StoreLocationRequest $request)
    {
        $validated = $request->validated();

        Location::create([
            'name'        => $validated['name'],
            'description' => $validated['description'] ?? null,
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

        $validated = $request->validated();

        $location->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        return back()->with('success', 'Cancha actualizada correctamente.');
    }

    public function destroy(Location $location)
    {
        $location->delete();
        return back()->with('success', 'Cancha eliminada correctamente.');
    }
}
