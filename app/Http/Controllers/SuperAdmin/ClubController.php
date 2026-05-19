<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Club;
use App\Models\Module;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ClubController extends Controller
{
    public function index()
    {
        $clubs = Club::with('modules')->get();
        $allModules = Module::where('is_active', true)->get();
        return view('superadmin.clubs.index', compact('clubs', 'allModules'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:clubs,name',
            'logo' => 'nullable|image|max:2048',
            'admin_name' => 'nullable|required_with:admin_email|string|max:255',
            'admin_email' => 'nullable|required_with:admin_name|string|email|max:255|unique:users,email',
            'admin_password' => 'nullable|required_with:admin_name|string|min:8',
        ]);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $pathUrl = 'images/logos/';
            $fileName = time() . "-" . $file->getClientOriginalName();
            $file->move(public_path($pathUrl), $fileName);
            $logoPath = $pathUrl . $fileName;
        }

        // 1. Crear el Club
        $club = Club::create([
            'name' => $request->name,
            'logo' => $logoPath,
            'is_active' => true,
        ]);

        // 2. Crear el Usuario Administrador/Director del Club
        if ($request->filled('admin_name')) {
            $user = User::create([
                'name' => $request->admin_name,
                'email' => $request->admin_email,
                'password' => Hash::make($request->admin_password),
                'club_id' => $club->id,
                'must_change_password' => true,
            ]);

            // Asignar el rol de Admin
            $user->assignRole('Admin');
        }

        return back()->with('success', 'Club "' . $club->name . '" creado con éxito' . ($request->filled('admin_name') ? ' junto a su administrador principal.' : '.'));
    }

    public function toggleModule(Request $request, Club $club)
    {
        $request->validate([
            'module_id' => 'required|exists:modules,id',
        ]);

        $club->modules()->toggle($request->module_id);

        return back()->with('success', 'Módulos actualizados para el club ' . $club->name);
    }
}
