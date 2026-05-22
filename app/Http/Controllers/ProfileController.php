<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Update the user's club information (Logo and Name).
     */
    public function updateClub(Request $request): RedirectResponse
    {
        $user = $request->user();

        // 1. Verificar que el usuario tenga rol de Admin y pertenezca a un club
        if (!$user->hasRole('Admin') || !$user->club_id) {
            abort(403, 'Acción no autorizada.');
        }

        $club = $user->club;

        // 2. Validar los datos recibidos
        $request->validate([
            'name' => 'required|string|max:255|unique:clubs,name,' . $club->id,
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $logoPath = $club->logo;
        if ($request->hasFile('logo')) {
            // Eliminar logo viejo si existe y no es el original
            if ($club->logo && $club->logo !== 'images/logo/LOGO.png' && file_exists(public_path($club->logo))) {
                @unlink(public_path($club->logo));
            }

            $file = $request->file('logo');
            $pathUrl = 'images/logos/';
            $fileName = time() . "-" . $file->getClientOriginalName();
            $file->move(public_path($pathUrl), $fileName);
            $logoPath = $pathUrl . $fileName;
        }

        // 3. Actualizar datos del Club
        $club->update([
            'name' => $request->name,
            'logo' => $logoPath,
        ]);

        return Redirect::route('profile.edit')->with('status', 'club-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
