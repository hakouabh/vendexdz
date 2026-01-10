<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, string $roleId): Response
    {
        // 1. Ensure User is Logged In
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // 2. Super Admin Override (Optional but recommended)
        // If you have a specific 'super_admin' role, always let them through
        if ($user->roles()->where('roles.rid', 2)->exists()) {
              return $next($request);
        }
        // 3. Check if the user's role matches ANY of the allowed roles
        // This assumes you have a 'role' string column in your 'users' table.
        if (! $user->roles()->where('roles.rid', $roleId)->exists()) {
             abort(403, 'Unauthorized. You do not have the required permissions.');
        }
        return $next($request);
        // 4. Unauthorized - Return 403 Forbidden
       
    }
}