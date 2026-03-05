<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TwoFactorMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->two_factor_enabled) {
            if (! $request->session()->get('two_factor_verified')) {
                if (! $request->is('two-factor*')) {
                    return redirect()->route('two-factor.challenge');
                }
            }
        }

        return $next($request);
    }
}
