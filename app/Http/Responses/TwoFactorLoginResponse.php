<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\TwoFactorLoginResponse as TwoFactorLoginResponseContract;

class TwoFactorLoginResponse implements TwoFactorLoginResponseContract
{
    public function toResponse($request)
    {
        // Marca la challenge 2FA come superata ORA
        session(['two_factor_passed_at' => now()]);

        // Questo codice è CORRETTO. Reindirizza all'URL salvato
        // dal tuo middleware, con fallback alla dashboard.
        return redirect()->intended(config('fortify.home'));
    }
}
