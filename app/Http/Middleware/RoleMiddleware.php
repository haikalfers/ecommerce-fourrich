<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
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
        if (! $request->user() || ! $request->user()->is_active) {
            abort(403, 'Unauthorized access.');
        }

        if ($request->user()->role !== $role) {
            // Redirect user to their respective dashboard
            return redirect()->route($request->user()->getDashboardRoute());
        }

        return $next($request);
    }
}
