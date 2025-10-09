<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Usage: ->middleware('role:admin')  or  ->middleware('role:admin,coach')
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userRole = $request->user()->role ?? null;

        if ($userRole && (empty($roles) || in_array($userRole, $roles, true))) {
            return $next($request);
        }

        abort(403, 'Unauthorized.');
    }
}
