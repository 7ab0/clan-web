<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ShowClinicGuestController extends Controller
{
    public function show(Request $request): View
    {
        $guest = null;
        $code = $request->query('inv');

        if ($code) {
            $guest = Guest::where('code', $code)->first();
        }

        return view('showclinic', [
            'guest' => $guest,
            // El evento ya pasó (ver config('services.showclinic.closed'))
            // — la vista muestra un mensaje simple en vez del countdown/RSVP,
            // sin afectar /showclinic/admin, que sigue mostrando el
            // historial completo de invitados.
            'closed' => (bool) config('services.showclinic.closed'),
        ]);
    }

    public function confirm(Request $request): RedirectResponse
    {
        // Evento cerrado: la vista ya no muestra el formulario de RSVP, pero
        // esto bloquea también un POST directo (link viejo guardado, etc.).
        if (config('services.showclinic.closed')) {
            abort(404);
        }

        $validated = $request->validate([
            'code' => ['required', 'string'],
            'response' => ['required', 'in:confirmado,rechazado'],
            'plus_one' => ['nullable', 'boolean'],
            'companion_name' => ['nullable', 'string', 'max:255'],
            'preferences' => ['nullable', 'string', 'max:1000'],
        ]);

        $guest = Guest::where('code', $validated['code'])->firstOrFail();

        $isConfirmed = $validated['response'] === 'confirmado';
        $plusOne = $isConfirmed && $request->boolean('plus_one');

        $guest->update([
            'status' => $validated['response'],
            'plus_one' => $plusOne,
            'companion_name' => $plusOne ? ($validated['companion_name'] ?? null) : null,
            'preferences' => $isConfirmed ? ($validated['preferences'] ?? null) : null,
            'confirmed_at' => now(),
        ]);

        return redirect()->route('showclinic', ['inv' => $guest->code]);
    }
}
