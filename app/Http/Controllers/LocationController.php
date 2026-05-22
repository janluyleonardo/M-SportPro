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
        $clubs = auth()->user()->is_super_admin ? \App\Models\Club::all() : collect();
        return view('locations.index', compact('locations', 'clubs'));
    }

    public function store(StoreLocationRequest $request)
    {
        $validated = $request->validated();

        $data = [
            'name'        => $validated['name'],
            'description' => $validated['description'] ?? null,
            'active'      => true,
        ];

        if (auth()->user()->is_super_admin && $request->has('club_id')) {
            $data['club_id'] = $request->club_id;
        }

        Location::create($data);

        return back()->with('success', 'Cancha "' . $request->name . '" agregada correctamente.');
    }

    public function update(StoreLocationRequest $request, Location $location)
    {
        if ($request->has('toggle_active')) {
            $location->update(['active' => !$location->active]);
            $estado = $location->active ? 'activada' : 'desactivada';
            return back()->with('success', "Cancha \"{$location->name}\" {$estado}.");
        }

        $validated = $request->validated();

        $data = [
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ];

        if (auth()->user()->is_super_admin && $request->has('club_id')) {
            $data['club_id'] = $request->club_id;
        }

        $location->update($data);

        return back()->with('success', 'Cancha actualizada correctamente.');
    }

    public function destroy(Location $location)
    {
        $location->delete();
        return back()->with('success', 'Cancha eliminada correctamente.');
    }
}
