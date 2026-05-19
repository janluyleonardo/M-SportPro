<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckModuleAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $moduleSlug): Response
    {
        if (auth()->check() && auth()->user()->is_super_admin) {
            return $next($request);
        }

        if (!auth()->check() || !auth()->user()->club || !auth()->user()->club->hasModule($moduleSlug)) {
            // Optional: redirect to a specific "upgrade" page or dashboard with error
            return redirect()->route('dashboard')->with('error', 'Tu club no tiene activo el módulo: ' . ucfirst($moduleSlug));
        }

        return $next($request);
    }
}
