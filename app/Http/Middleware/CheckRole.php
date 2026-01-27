<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            abort(403);
        }

        if (!in_array(Auth::user()->role, $roles)) {
            abort(403, 'Unauthorized Access');
        }

        if (Auth::user()->status !== 'active') {
            Auth::logout();
            abort(403, 'Account inactive');
        }

        return $next($request);
    }
}

