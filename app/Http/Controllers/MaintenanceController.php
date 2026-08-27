<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MaintenanceController extends Controller
{
    public function show(): View
    {
        return view('maintenance.index');
    }

    public function unlock(Request $request): RedirectResponse
    {
        $word = strtolower(trim((string) $request->input('word')));

        if ($word !== config('maintenance.access_word')) {
            return redirect()
                ->route('maintenance.show')
                ->with('error', 'Esa no es la palabra.');
        }

        return redirect('/')
            ->cookie(config('maintenance.access_cookie'), 'granted', 60 * 24 * 30);
    }
}
