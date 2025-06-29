<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RequireRecentTwoFactor
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (!$user || !$user->two_factor_secret) {
            return $next($request);
        }

        $timeout = 5; 
        if (session('two_factor_passed_at') && now()->diffInMinutes(session('two_factor_passed_at')) < $timeout) {
            return $next($request);
        }

        // MODIFICA QUI: Salva l'URL della pagina precedente, non l'URL della richiesta POST
        session(['url.intended' => url()->previous()]);
        
        return redirect()->route('two-factor.login');
    }
}