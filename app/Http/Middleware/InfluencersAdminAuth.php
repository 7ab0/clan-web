<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Guardia del panel de influencers (/influencers/admin) — contraseña y
 * sesión propias, independientes de ReservasAdminAuth/ReservasReviewAuth.
 */
class InfluencersAdminAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->session()->get('influencers_admin_authenticated')) {
            return redirect()->route('influencers.admin.login');
        }

        return $next($request);
    }
}
