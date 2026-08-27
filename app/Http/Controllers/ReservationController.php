<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReservationRequest;
use App\Models\Customer;
use App\Models\EventSchedule;
use App\Models\FermentoGuest;
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

            // Invitado especial "Pruebas" (ver FermentoSeeder::seedGuests): deja
            // avanzar todo el flujo real de reserva/pago/confirmación, pero la
            // reserva queda marcada is_test — no ocupa mesa real ni entra a la
            // base de clientes. Se identifica por el token oculto del form, no
            // por nada que el cliente pueda manipular a mano.
            $isTest = false;

            if (! empty($validated['fermento_guest_token']) && $schedule->event->slug === 'fermento') {
                $guest = FermentoGuest::where('event_id', $schedule->event_id)
                    ->where('token', $validated['fermento_guest_token'])
                    ->first();

                $isTest = (bool) ($guest->is_test ?? false);
            }

            if ($tableId) {
                // Eventos con mesas propias (ej. Fermento): el overbooking se controla
                // por mesa+fecha, no por cupo agregado del horario. Las reservas de
                // prueba (is_test) no cuentan como ocupación real de la mesa.
                $tableTaken = Reservation::where('event_schedule_id', $lockedSchedule->id)
                    ->where('event_table_id', $tableId)
                    ->where('status', '!=', 'cancelled')
                    ->where('is_test', false)
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

            // Seña que el cliente elige coordinar por WhatsApp (Fermento). Si el
            // formulario no la manda (ej. Íntimo, que aún no pide seña variable),
            // se sigue depositando el total completo, como hasta ahora.
            $depositAmount = $validated['deposit_amount'] ?? $totalAmount;

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
                'is_test' => $isTest,
            ]);

            Payment::create([
                'reservation_id' => $reservation->id,
                'amount' => $depositAmount,
                'currency' => $lockedSchedule->event->currency,
                'provider' => 'whatsapp',
                'status' => 'pending',
            ]);

            // Base de clientes: por ahora solo para Fermento (ver plan). Para
            // extenderla a otros eventos más adelante, basta con quitar este
            // chequeo de slug. Las reservas de prueba nunca tocan la base de
            // clientes real.
            if ($lockedSchedule->event->slug === 'fermento' && ! $isTest) {
                $this->upsertCustomer($validated);
            }

            return redirect()->route('reservas.pago', $reservation->code);
        });
    }

    /**
     * Crea o actualiza el registro del cliente en la base simple de
     * clientes (deduplicada por teléfono). Nunca pisa "frequency", "vip"
     * ni "notes" de un cliente existente — esos los cura el staff a mano
     * desde /reservas/admin/clientes. Solo agrega la marca "Molto" si
     * todavía no la tenía, y completa el email si no tenía uno.
     */
    private function upsertCustomer(array $validated): void
    {
        $phone = Customer::normalizePhone($validated['customer_phone']);

        if ($phone === null) {
            return;
        }

        $customer = Customer::where('phone', $phone)->first();

        if (! $customer) {
            Customer::create([
                'name' => $validated['customer_name'],
                'phone' => $phone,
                'email' => $validated['customer_email'] ?? null,
                'brands' => ['Molto'],
                'frequency' => 'nueva',
                'vip' => false,
            ]);

            return;
        }

        $updates = [];

        if (! in_array('Molto', $customer->brands ?? [], true)) {
            $updates['brands'] = array_values(array_unique([...($customer->brands ?? []), 'Molto']));
        }

        if (! $customer->email && ! empty($validated['customer_email'])) {
            $updates['email'] = $validated['customer_email'];
        }

        if ($updates !== []) {
            $customer->update($updates);
        }
    }
}
