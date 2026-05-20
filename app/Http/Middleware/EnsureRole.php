<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureRole
{
    public function handle(Request $request, Closure $next, string ...$roles): mixed
    {
        // Nicht eingeloggt → zum Login
        if (!$request->user()) {
            return redirect()->route('login');
        }

        // Rolle nicht in erlaubten Rollen → 403
        if (!in_array($request->user()->role, $roles)) {
            abort(403, 'ACCESS_DENIED: INSUFFICIENT_CLEARANCE');
        }

        return $next($request);
    }
}
