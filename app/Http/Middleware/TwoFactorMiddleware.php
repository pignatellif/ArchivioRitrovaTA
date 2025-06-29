<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TwoFactorMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user && $user->two_factor_secret && !$request->session()->get('two-factor.confirmed')) {
            return redirect()->route('two-factor.login');
        }

        return $next($request);
    }
}