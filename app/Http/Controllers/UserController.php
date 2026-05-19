<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Club;
use Illuminate\Http\Request;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $users = User::with(['roles', 'club'])
            ->when(!auth()->user()->is_super_admin, function($query) {
                // Si no es super admin, solo ve los de su mismo club
                return $query->where('club_id', auth()->user()->club_id);
            })
            ->when($search, function($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhereHas('roles', function($roleQuery) use ($search) {
                          $roleQuery->where('name', 'like', "%{$search}%");
                      });
                });
            })
            ->paginate(10)
            ->withQueryString();

        $roles = Role::all();
        $clubs = Club::all(); // Obtenemos todos los clubes para el selector si es superadmin
        return view('users.index', compact('users', 'roles', 'clubs', 'search'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();

        // Si es superadmin puede mandar club_id, de lo contrario toma el club del admin creador
        $clubId = auth()->user()->is_super_admin ? $request->club_id : auth()->user()->club_id;

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'documento_deportista' => $request->documento_deportista,
            'pay_per_session' => $request->pay_per_session ?? 0,
            'must_change_password' => true,
            'club_id' => $clubId,
        ]);

        $user->assignRole($validated['role']);

        return redirect()->route('users.index')->with('success', 'Usuario creado manualmente con éxito.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $rules = [
            'role' => 'required|exists:roles,name',
            'documento_deportista' => 'nullable|string',
            'pay_per_session' => 'nullable|numeric|min:0',
        ];

        // Solo superadmin puede cambiar o asociar clubes de forma directa
        if (auth()->user()->is_super_admin) {
            $rules['club_id'] = 'nullable|exists:clubs,id';
        }

        $request->validate($rules);

        $updateData = [
            'documento_deportista' => $request->documento_deportista,
            'pay_per_session' => $request->pay_per_session ?? 0
        ];

        if (auth()->user()->is_super_admin) {
            $updateData['club_id'] = $request->club_id;
        } else {
            // Si es un admin normal y el usuario modificado no tiene club, lo asociamos a su club
            if (is_null($user->club_id)) {
                $updateData['club_id'] = auth()->user()->club_id;
            }
        }

        $user->update($updateData);

        $user->syncRoles([$request->role]);

        return redirect()->route('users.index')->with('success', 'Usuario actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if (auth()->id() === $user->id) {
            return redirect()->route('users.index')->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Usuario eliminado correctamente.');
    }
}
