<?php

namespace App\Http\Controllers;

use App\Models\EventSchedule;
use App\Models\FermentoWaitlistEntry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FermentoWaitlistController extends Controller
{
    /**
     * Manifestación de interés cuando una fecha de Fermento ya no tiene
     * mesas — no asigna mesa ni confirma nada, el encargado la revisa a mano
     * desde /reservas/admin/lista-espera. Solo para fechas activas y con la
     * lista de espera abierta (el sábado 5, cerrado del todo, nunca la tiene).
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'event_schedule_id' => [
                'required',
                // Ojo: Rule::exists()->where() con `false` genera un binding
                // vacío en vez de 0 (quirk conocido de Laravel) — se usa 0
                // explícito para que el filtro realmente compare contra la
                // columna boolean guardada como 0/1.
                Rule::exists('event_schedules', 'id')
                    ->where('is_active', 1)
                    ->where('waitlist_closed', 0),
            ],
            'name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:30'],
            'party_size' => ['required', 'integer', 'min:1', 'max:20'],
        ], [
            'event_schedule_id.exists' => 'Esa fecha ya no admite lista de espera.',
        ]);

        $schedule = EventSchedule::whereHas('event', fn ($q) => $q->where('slug', 'fermento'))
            ->findOrFail($validated['event_schedule_id']);

        FermentoWaitlistEntry::create([
            'event_schedule_id' => $schedule->id,
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'party_size' => $validated['party_size'],
        ]);

        return redirect(route('fermento') . '#reservar')
            ->with('waitlistStatus', 'Listo, ' . $validated['name'] . ' — te anotamos en la lista de espera. Te contactamos por WhatsApp si se libera una mesa.');
    }
}
