<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Guardia del panel de solo revisión (/reservas/revision) — contraseña y
 * sesión propias, independientes de ReservasAdminAuth. Ver
 * ReservationReviewController para el porqué de este panel separado.
 */
class ReservasReviewAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->session()->get('reservas_review_authenticated')) {
            return redirect()->route('reservas.review.login');
        }

        return $next($request);
    }
}
