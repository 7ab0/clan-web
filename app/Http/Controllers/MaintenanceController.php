<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\View\View;

class MaintenanceController extends Controller
{
    public function show(): View
    {
        return view('maintenance');
    }

    public function unlock(Request $request): RedirectResponse
    {
        $request->validate(['keyword' => ['required', 'string']]);

        if (! hash_equals('clandestino', (string) $request->input('keyword'))) {
            return back()->withErrors(['keyword' => 'Palabra clave incorrecta.']);
        }

        return redirect()->route('index')->withCookie(
            Cookie::forever('clan_access', 'clandestino')
        );
    }
}
