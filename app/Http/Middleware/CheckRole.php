<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! $request->user()) {
            return redirect('/login');
        }

        $userRole = strtolower($request->user()->rol);
        $allowedRoles = array_map('strtolower', $roles);

        if (! in_array($userRole, $allowedRoles)) {
            // Redirect or show an unauthorized page/message
            return redirect('/')->with('error', 'No tienes permiso para acceder a esta página.');
        }

        return $next($request);
    }
}
