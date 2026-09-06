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

        // /showclinic (otro cliente) e /intimo, /fermento + /reservas/* (su
        // flujo de reserva/pago) + /influencers/* (panel de staff aparte)
        // siempre deben verse, sin pasar por el pre-holder.
        if ($request->is('showclinic*', 'intimo*', 'fermento*', 'reservas/*', 'influencers/*', 'mantenimiento', 'up')) {
            return $next($request);
        }

        return response()->view('preholder');
    }
}
