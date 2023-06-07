<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\ExternalApiService;

class TwoFactorAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->user()) {
            return redirect('/login');
        }
        if (auth()->user()->tfa_verified) {
            return $next($request);
        }
        // Send 2FA code
        auth()->user()->generateTFA();
        $externalApiService = new ExternalApiService();
        $externalApiService->sendTFA(auth()->user()->phone, auth()->user()->tfa_code);

        return redirect('/tfa');
    }
}
