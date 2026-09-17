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

        // /showclinic (otro cliente) e /intimo, /fermento + /reservas (landing
        // pública de Clan) + /reservas/* (pago/confirmación de Fermento-Íntimo,
        // disponibilidad y panel admin/revisión) + /influencers/* (panel de
        // staff aparte) siempre deben verse, sin pasar por el pre-holder.
        // Nota: 'reservas/*' NO matchea la ruta exacta "reservas" (sin barra
        // ni segmento después) — por eso hace falta el patrón 'reservas'
        // aparte, si no /reservas (GET y POST) caía en el pre-holder.
        if ($request->is('showclinic*', 'intimo*', 'fermento*', 'reservas', 'reservas/*', 'influencers/*', 'mantenimiento', 'up')) {
            return $next($request);
        }

        return response()->view('preholder');
    }
}
