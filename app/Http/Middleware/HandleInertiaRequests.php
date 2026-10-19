<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HandleInertiaRequests
{
    /**
     * Middleware vacío: como usamos Blade (no Inertia),
     * solo pasa la petición sin hacer nada.
     */
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }
}