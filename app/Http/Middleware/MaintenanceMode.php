<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MaintenanceMode
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! config('maintenance.enabled')) {
            return $next($request);
        }

        if ($request->routeIs('maintenance.*') || $request->is('up')) {
            return $next($request);
        }

        // /showclinic (otro cliente) e /intimo, /fermento + /reservas/* (su
        // flujo de reserva/pago) + /influencers/* (panel de staff aparte)
        // siempre deben verse, sin pasar por el muro de mantenimiento
        // (mismo criterio que ClanPreholder::handle).
        if ($request->is('showclinic*', 'intimo*', 'fermento*', 'reservas/*', 'influencers/*')) {
            return $next($request);
        }

        if ($request->cookie(config('maintenance.access_cookie')) === 'granted') {
            return $next($request);
        }

        return redirect()->route('maintenance.show');
    }
}
