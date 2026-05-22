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
        $clubs = Club::with(['modules', 'users'])->get();
        foreach ($clubs as $club) {
            $club->admin = $club->users->first(function ($user) {
                return $user->hasRole('Admin');
            });
        }
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

    public function update(Request $request, Club $club)
    {
        $adminUser = $club->users()->whereHas('roles', function($q) {
            $q->where('name', 'Admin');
        })->first();

        $request->validate([
            'name' => 'required|string|max:255|unique:clubs,name,' . $club->id,
            'logo' => 'nullable|image|max:2048',
            'admin_name' => 'nullable|required_with:admin_email|string|max:255',
            'admin_email' => 'nullable|required_with:admin_name|string|email|max:255|unique:users,email,' . ($adminUser ? $adminUser->id : 'NULL'),
            'admin_password' => 'nullable|string|min:8',
        ]);

        $logoPath = $club->logo;
        if ($request->hasFile('logo')) {
            if ($club->logo && $club->logo !== 'images/logo/LOGO.png' && file_exists(public_path($club->logo))) {
                @unlink(public_path($club->logo));
            }

            $file = $request->file('logo');
            $pathUrl = 'images/logos/';
            $fileName = time() . "-" . $file->getClientOriginalName();
            $file->move(public_path($pathUrl), $fileName);
            $logoPath = $pathUrl . $fileName;
        }

        $club->update([
            'name' => $request->name,
            'logo' => $logoPath,
            'is_active' => $request->boolean('is_active'),
        ]);

        if ($request->filled('admin_name') && $request->filled('admin_email')) {
            if ($adminUser) {
                $adminUser->name = $request->admin_name;
                $adminUser->email = $request->admin_email;
                if ($request->filled('admin_password')) {
                    $adminUser->password = Hash::make($request->admin_password);
                }
                $adminUser->save();
            } else {
                $adminUser = User::create([
                    'name' => $request->admin_name,
                    'email' => $request->admin_email,
                    'password' => Hash::make($request->admin_password ?? 'password123'),
                    'club_id' => $club->id,
                    'must_change_password' => true,
                ]);
                $adminUser->assignRole('Admin');
            }
        }

        return back()->with('success', 'Club "' . $club->name . '" actualizado con éxito.');
    }

    public function destroy(Club $club)
    {
        if ($club->logo && $club->logo !== 'images/logo/LOGO.png' && file_exists(public_path($club->logo))) {
            @unlink(public_path($club->logo));
        }

        $club->delete();

        return back()->with('success', 'Club y todos sus datos relacionados han sido eliminados.');
    }
}
