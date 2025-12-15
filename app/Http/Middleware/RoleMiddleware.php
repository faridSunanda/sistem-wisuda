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
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            abort(403, 'ANDA TIDAK PUNYA AKSES.');
        }

        // Check acting_role from session first (for role switching), then fall back to user's actual role
        $actingRole = session('acting_role', Auth::user()->role);

        // Allow access if acting role matches, OR if user's real role is admin (admins can access any dashboard via role switching)
        if ($actingRole !== $role && Auth::user()->role !== 'admin') {
            abort(403, 'ANDA TIDAK PUNYA AKSES.');
        }

        // If admin is acting as another role, still allow access
        if ($actingRole !== $role && Auth::user()->role === 'admin' && !session('allow_role_switch')) {
            abort(403, 'ANDA TIDAK PUNYA AKSES.');
        }

        return $next($request);
    }
}
