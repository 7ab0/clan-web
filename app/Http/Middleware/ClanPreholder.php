<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ClanPreholder
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! config('preholder.active')) {
            return $next($request);
        }

        // /showclinic (y sus sub-rutas) es de otro cliente, con su propia
        // identidad visual: nunca debe quedar tapado por este pre-holder.
        if ($request->is('showclinic*', 'mantenimiento', 'up')) {
            return $next($request);
        }

        return response()->view('preholder');
    }
}
