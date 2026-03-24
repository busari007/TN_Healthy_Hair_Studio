<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
public function handle($request, Closure $next, ...$roles)
{
    // Check if user is logged in AND their role is in the allowed list
    if (Auth::check() && in_array(Auth::user()->role, $roles)) {
        return $next($request);
    }

    // Redirect or abort if they don't have the right role
    abort(403, 'Unauthorized action.');
}

}
