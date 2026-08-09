<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ShowClinicAdminAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->session()->get('showclinic_admin_authenticated')) {
            return redirect()->route('showclinic.admin.login');
        }

        return $next($request);
    }
}
