<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ReservasAdminAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->session()->get('reservas_admin_authenticated')) {
            return redirect()->route('reservas.admin.login');
        }

        return $next($request);
    }
}
