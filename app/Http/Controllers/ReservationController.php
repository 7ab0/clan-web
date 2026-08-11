<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReservationRequest;
use App\Models\EventSchedule;
use App\Models\Payment;
use App\Models\Reservation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    /**
     * Crea la reserva en estado "pending" y la manda al paso de pago.
     * El precio se toma siempre del evento en base de datos (nunca del formulario),
     * así que cambiar el precio en el admin más adelante no depende de tocar código.
     */
    public function store(StoreReservationRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $schedule = EventSchedule::with('event')
            ->where('is_active', true)
            ->findOrFail($validated['event_schedule_id']);

        return DB::transaction(function () use ($validated, $schedule) {
            // Bloqueamos la fila del horario para evitar overbooking con reservas simultáneas.
            $lockedSchedule = EventSchedule::whereKey($schedule->id)->lockForUpdate()->first();

            $tableId = $validated['event_table_id'] ?? null;

            if ($tableId) {
                // Eventos con mesas propias (ej. Fermento): el overbooking se controla
                // por mesa+fecha, no por cupo agregado del horario.
                $tableTaken = Reservation::where('event_schedule_id', $lockedSchedule->id)
                    ->where('event_table_id', $tableId)
                    ->where('status', '!=', 'cancelled')
                    ->lockForUpdate()
                    ->exists();

                if ($tableTaken) {
                    return back()
                        ->withInput()
                        ->withErrors(['event_table_id' => 'Esa mesa acaba de ser reservada para esta fecha. Elige otra, por favor.']);
                }
            } elseif ($lockedSchedule->is_full) {
                return back()
                    ->withInput()
                    ->withErrors(['event_schedule_id' => 'Ese horario acaba de quedar sin cupo. Elige otro, por favor.']);
            }

            // Eventos con mesas propias cobran por persona (Fermento); eventos sin
            // mesas mantienen el precio plano por experiencia (Íntimo = por pareja).
            $totalAmount = $tableId
                ? $lockedSchedule->event->price * $validated['party_size']
                : $lockedSchedule->event->price;

            $reservation = Reservation::create([
                'event_id' => $lockedSchedule->event_id,
                'event_schedule_id' => $lockedSchedule->id,
                'event_table_id' => $tableId,
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'],
                'customer_phone' => $validated['customer_phone'],
                'party_size' => $validated['party_size'],
                'relationship_type' => $validated['relationship_type'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'total_amount' => $totalAmount,
                'status' => 'pending',
            ]);

            Payment::create([
                'reservation_id' => $reservation->id,
                'amount' => $reservation->total_amount,
                'currency' => $lockedSchedule->event->currency,
                'provider' => 'simulated',
                'status' => 'pending',
            ]);

            return redirect()->route('reservas.pago', $reservation->code);
        });
    }
}
