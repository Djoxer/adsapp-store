<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckBanned
{
    public function handle(Request $request, Closure $next): mixed
    {
        if ($request->user() && $request->user()->is_banned) {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')
                ->withErrors(['email' => 'Dein Account wurde gesperrt. Kontaktiere den Support.']);
        }

        return $next($request);
    }
}
