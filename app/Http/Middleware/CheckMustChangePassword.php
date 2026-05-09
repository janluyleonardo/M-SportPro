<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMustChangePassword
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->must_change_password) {
            // No redirigir si ya está en la página de cambio de clave o cerrando sesión
            if (!$request->routeIs('password.change') && !$request->routeIs('logout') && !$request->routeIs('password.update.mandatory')) {
                return redirect()->route('password.change')
                    ->with('warning', 'Por seguridad, debes cambiar tu contraseña antes de continuar.');
            }
        }

        return $next($request);
    }
}
