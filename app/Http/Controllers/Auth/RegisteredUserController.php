<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use App\Models\Club;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(Request $request): View
    {
        $selectedClubId = $request->query('club_id');

        if ($selectedClubId) {
            if (! $request->hasValidSignature()) {
                abort(403, 'Este enlace de registro es inválido o ha sido alterado.');
            }
        }

        $clubs = Club::where('is_active', true)->get();
        return view('auth.register', compact('clubs', 'selectedClubId'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->query('club_id')) {
            if (! $request->hasValidSignature() || $request->club_id != $request->query('club_id')) {
                abort(403, 'Este enlace de registro es inválido o ha sido alterado.');
            }
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'club_id' => ['required', 'exists:clubs,id'],
            'documento_deportista' => [
                'required',
                'string',
                function ($attribute, $value, $fail) use ($request) {
                    $studentExists = \App\Models\Student::where('numDocumento', $value)
                        ->where('club_id', $request->club_id)
                        ->exists();
                    if (!$studentExists) {
                        $fail('El documento ingresado no corresponde a ningún deportista registrado en este club.');
                    }
                }
            ],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'club_id' => $request->club_id,
            'documento_deportista' => $request->documento_deportista,
            'must_change_password' => false,
        ]);

        $user->assignRole('Padre');

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
