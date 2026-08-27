<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Event;
use App\Models\EventSchedule;
use App\Models\EventTable;
use App\Models\FermentoGuest;
use App\Models\Payment;
use App\Models\Reservation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReservationAdminController extends Controller
{
    public function loginForm(Request $request): View|RedirectResponse
    {
        if ($request->session()->get('reservas_admin_authenticated')) {
            return redirect()->route('reservas.admin.index');
        }

        return view('reservas.admin-login');
    }

    public function login(Request $request): RedirectResponse
    {
        $request->validate(['password' => ['required', 'string']]);

        $expected = (string) config('services.reservas.admin_password');

        if ($expected === '' || ! hash_equals($expected, (string) $request->input('password'))) {
            return back()->withErrors(['password' => 'Contraseña incorrecta.']);
        }

        $request->session()->put('reservas_admin_authenticated', true);
        $request->session()->regenerate();

        return redirect()->route('reservas.admin.index');
    }

    public function logout(Request $request): RedirectResponse
    {
        $request->session()->forget('reservas_admin_authenticated');

        return redirect()->route('reservas.admin.login');
    }

    public function index(Request $request): View
    {
        $eventSlug = (string) $request->query('event', 'todos');
        $status = (string) $request->query('status', 'todos');

        $sortable = ['customer_name', 'code', 'status', 'created_at'];
        $sort = in_array($request->query('sort'), $sortable, true) ? $request->query('sort') : 'created_at';
        $dir = $request->query('dir') === 'asc' ? 'asc' : 'desc';

        $query = Reservation::with(['event', 'schedule', 'table', 'payment']);

        if ($eventSlug !== 'todos') {
            $query->whereHas('event', fn ($q) => $q->where('slug', $eventSlug));
        }

        if (in_array($status, ['pending', 'confirmed', 'cancelled', 'completed'], true)) {
            $query->where('status', $status);
        }

        $reservations = $query->orderBy($sort, $dir)->get();

        $summary = [
            'total' => Reservation::count(),
            'pending' => Reservation::where('status', 'pending')->count(),
            'confirmed' => Reservation::where('status', 'confirmed')->count(),
            'cancelled' => Reservation::where('status', 'cancelled')->count(),
        ];

        return view('reservas.admin', array_merge([
            'reservations' => $reservations,
            'summary' => $summary,
            'events' => Event::orderBy('name')->get(['slug', 'name']),
            'eventSlug' => $eventSlug,
            'status' => $status,
            'sort' => $sort,
            'dir' => $dir,
        ], $this->manualCreateFormData()));
    }

    /**
     * Datos que alimentan el <script> del modal "Agregar reserva": eventos,
     * horarios y mesas ya cargados en un solo viaje, para que el cascadeo
     * evento → fecha → mesa se resuelva en JS sin pegarle al server por
     * cada selección. La verificación real de disponibilidad (evitar
     * doble-reservar una mesa) igual se repite en storeReservation().
     */
    private function manualCreateFormData(): array
    {
        $eventsForForm = Event::withCount('tables')->orderBy('name')->get(['id', 'slug', 'name'])
            ->map(fn (Event $event) => [
                'id' => $event->id,
                'slug' => $event->slug,
                'name' => $event->name,
                'has_tables' => $event->tables_count > 0,
            ])
            ->values();

        $schedulesForForm = EventSchedule::orderBy('date')->orderBy('start_time')
            ->get(['id', 'event_id', 'date', 'start_time'])
            ->map(fn (EventSchedule $schedule) => [
                'id' => $schedule->id,
                'event_id' => $schedule->event_id,
                'label' => $schedule->date->format('d/m/Y') . ' · ' . \Illuminate\Support\Str::of($schedule->start_time)->substr(0, 5),
            ])
            ->values();

        $tablesForForm = EventTable::orderBy('table_number')
            ->get(['id', 'event_id', 'table_number', 'capacity_min', 'capacity_max'])
            ->values();

        $takenForForm = Reservation::where('status', '!=', 'cancelled')
            ->where('is_test', false)
            ->whereNotNull('event_table_id')
            ->get(['event_schedule_id', 'event_table_id'])
            ->groupBy('event_schedule_id')
            ->map(fn ($rows) => $rows->pluck('event_table_id')->values());

        return [
            'eventsForForm' => $eventsForForm,
            'schedulesForForm' => $schedulesForForm,
            'tablesForForm' => $tablesForForm,
            'takenForForm' => $takenForForm,
        ];
    }

    /**
     * Alta manual de una reserva desde el panel (ej. cliente que coordinó
     * todo por teléfono/en persona, sin pasar por el link público). Repite
     * el mismo candado de mesa+fecha que ReservationController::store para
     * no poder duplicar una mesa ya ocupada; a diferencia del flujo público,
     * el staff elige el estado directamente (normalmente "confirmed", ya
     * que si la está cargando a mano es porque ya coordinó el pago).
     */
    public function storeReservation(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'event_id' => ['required', 'exists:events,id'],
            'event_schedule_id' => ['required', 'exists:event_schedules,id'],
            'event_table_id' => ['nullable', 'exists:event_tables,id'],
            'customer_name' => ['required', 'string', 'max:120'],
            'customer_email' => ['required', 'email', 'max:150'],
            'customer_phone' => ['nullable', 'string', 'max:30'],
            'party_size' => ['required', 'integer', 'min:1', 'max:20'],
            'deposit_amount' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:500'],
            'status' => ['required', 'in:pending,confirmed,cancelled,completed'],
        ]);

        $schedule = EventSchedule::with('event.tables')->findOrFail($validated['event_schedule_id']);

        if ($schedule->event_id !== (int) $validated['event_id']) {
            return back()->withInput()->withErrors(['event_schedule_id' => 'Esa fecha no pertenece al evento elegido.']);
        }

        return DB::transaction(function () use ($validated, $schedule) {
            $lockedSchedule = EventSchedule::whereKey($schedule->id)->lockForUpdate()->first();
            $tableId = $validated['event_table_id'] ?? null;
            $hasTables = $lockedSchedule->event->tables->isNotEmpty();

            if ($hasTables) {
                if (! $tableId) {
                    return back()->withInput()->withErrors(['event_table_id' => 'Elige una mesa para este evento.']);
                }

                $table = $lockedSchedule->event->tables->firstWhere('id', (int) $tableId);

                if (! $table) {
                    return back()->withInput()->withErrors(['event_table_id' => 'Esa mesa no pertenece a este evento.']);
                }

                $tableTaken = Reservation::where('event_schedule_id', $lockedSchedule->id)
                    ->where('event_table_id', $tableId)
                    ->where('status', '!=', 'cancelled')
                    ->where('is_test', false)
                    ->lockForUpdate()
                    ->exists();

                if ($tableTaken) {
                    return back()->withInput()->withErrors(['event_table_id' => 'Esa mesa ya está ocupada para esta fecha.']);
                }
            } elseif ($lockedSchedule->is_full) {
                return back()->withInput()->withErrors(['event_schedule_id' => 'Ese horario ya no tiene cupo disponible. Si necesitas forzarlo, sube la capacidad del turno primero.']);
            }

            $totalAmount = $tableId
                ? $lockedSchedule->event->price * $validated['party_size']
                : $lockedSchedule->event->price;

            $depositAmount = $validated['deposit_amount'] ?? $totalAmount;
            $status = $validated['status'];

            $reservation = Reservation::create([
                'event_id' => $lockedSchedule->event_id,
                'event_schedule_id' => $lockedSchedule->id,
                'event_table_id' => $tableId,
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'],
                'customer_phone' => $validated['customer_phone'] ?? null,
                'party_size' => $validated['party_size'],
                'notes' => $validated['notes'] ?? null,
                'total_amount' => $totalAmount,
                'status' => $status,
                'is_test' => false,
            ]);

            $paid = in_array($status, ['confirmed', 'completed'], true);

            Payment::create([
                'reservation_id' => $reservation->id,
                'amount' => $depositAmount,
                'currency' => $lockedSchedule->event->currency,
                'provider' => 'manual',
                'status' => $paid ? 'paid' : 'pending',
                'provider_reference' => $paid ? 'MANUAL-' . strtoupper(uniqid()) : null,
                'paid_at' => $paid ? now() : null,
            ]);

            if ($lockedSchedule->event->slug === 'fermento') {
                $this->upsertCustomerManual($validated);
            }

            return back()->with('status', "Reserva {$reservation->code} creada.");
        });
    }

    /**
     * Misma lógica de alta/actualización de cliente que
     * ReservationController::upsertCustomer(), pero sin depender de esa
     * clase (customer_phone puede venir vacío en una carga manual — en ese
     * caso, igual que el flujo público, no se toca la base de clientes).
     */
    private function upsertCustomerManual(array $validated): void
    {
        $phone = Customer::normalizePhone((string) ($validated['customer_phone'] ?? ''));

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

    /**
     * Confirma a mano el pago de la seña, coordinado por WhatsApp fuera del
     * sistema (Yape/Plin/transferencia). Reemplaza la vieja confirmación
     * automática que hacía el propio cliente en PaymentController::process().
     */
    public function confirmPayment(Reservation $reservation): RedirectResponse
    {
        if ($reservation->status !== 'confirmed') {
            $payment = $reservation->payment;

            if ($payment) {
                $payment->update([
                    'status' => 'paid',
                    'provider_reference' => 'WA-' . strtoupper(uniqid()),
                    'paid_at' => now(),
                ]);
            }

            $reservation->update(['status' => 'confirmed']);
        }

        return back()->with('status', "Reserva {$reservation->code} confirmada.");
    }

    /**
     * Edita a mano los datos de contacto, personas, notas y estado de una
     * reserva (ej. corregir un typo, o revertir una confirmación hecha por
     * error). No permite cambiar mesa/horario — eso implica re-chequear
     * disponibilidad y se maneja mejor cancelando y creando una reserva nueva.
     */
    public function updateReservation(Request $request, Reservation $reservation): RedirectResponse
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:120'],
            'customer_phone' => ['nullable', 'string', 'max:30'],
            'customer_email' => ['required', 'email', 'max:150'],
            'party_size' => ['required', 'integer', 'min:1', 'max:20'],
            'notes' => ['nullable', 'string', 'max:500'],
            'status' => ['required', 'in:pending,confirmed,cancelled,completed'],
        ]);

        $reservation->update($validated);

        return back()->with('status', "Reserva {$reservation->code} actualizada.");
    }

    /**
     * Elimina una reserva (y su pago asociado) — pensado sobre todo para
     * limpiar reservas de prueba (is_test) sin tener que entrar a la base
     * de datos a mano.
     */
    public function destroy(Reservation $reservation): RedirectResponse
    {
        $code = $reservation->code;

        $reservation->payment?->delete();
        $reservation->delete();

        return back()->with('status', "Reserva {$code} eliminada.");
    }

    /**
     * Lista la base de clientes simple (por ahora, solo se llena desde
     * Fermento — ver ReservationController::upsertCustomer).
     */
    public function customers(Request $request): View
    {
        $frequency = (string) $request->query('frequency', 'todas');
        $vipOnly = $request->boolean('vip');

        $query = Customer::query();

        if (in_array($frequency, ['nueva', 'ocasional', 'frecuente'], true)) {
            $query->where('frequency', $frequency);
        }

        if ($vipOnly) {
            $query->where('vip', true);
        }

        $customers = $query->orderBy('name')->get();

        return view('reservas.clientes', [
            'customers' => $customers,
            'frequency' => $frequency,
            'vipOnly' => $vipOnly,
        ]);
    }

    /**
     * Edita a mano los campos que el staff cura: nombre/teléfono/email
     * (por si hubo un typo), frecuencia, vip, cumpleaños y notas. Las
     * "brands" no se editan acá — las gestiona la reserva automáticamente.
     */
    public function updateCustomer(Request $request, Customer $customer): RedirectResponse
    {
        // Normalizamos el teléfono antes de validar unicidad: si el staff lo
        // escribe con o sin prefijo de país, debe comparar contra el mismo
        // formato que ya vive en la base (ver Customer::normalizePhone).
        $normalizedPhone = Customer::normalizePhone((string) $request->input('phone'));
        $request->merge(['phone' => $normalizedPhone]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:30', 'unique:customers,phone,' . $customer->id],
            'email' => ['nullable', 'email', 'max:150'],
            'frequency' => ['required', 'in:nueva,ocasional,frecuente'],
            'vip' => ['nullable', 'boolean'],
            'birth_month' => ['nullable', 'integer', 'min:1', 'max:12'],
            'birth_day' => ['nullable', 'integer', 'min:1', 'max:31'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ], [
            'phone.unique' => 'Ya existe otro cliente con ese teléfono.',
        ]);

        $validated['vip'] = $request->boolean('vip');

        $customer->update($validated);

        return back()->with('status', "Cliente {$customer->name} actualizado.");
    }

    /**
     * Lista los invitados de Fermento y su avance en el envío de WhatsApp
     * por etapas, cruzando por teléfono normalizado con las reservas reales
     * (sin tocar el modelo Reservation ni el flujo de reserva).
     */
    public function guests(): View
    {
        $event = Event::where('slug', 'fermento')->firstOrFail();

        $guests = FermentoGuest::where('event_id', $event->id)->orderBy('name')->get();

        // Ascendente a propósito: si un mismo teléfono tiene varias reservas,
        // keyBy() se queda con la última procesada — con orden ascendente esa
        // es la más reciente.
        $reservationsByPhone = Reservation::whereHas('event', fn ($q) => $q->where('slug', 'fermento'))
            ->orderBy('created_at')
            ->get()
            ->keyBy(fn (Reservation $reservation) => Customer::normalizePhone($reservation->customer_phone));

        $guests->each(function (FermentoGuest $guest) use ($reservationsByPhone) {
            $phone = Customer::normalizePhone($guest->phone);
            $guest->reservation = $phone ? $reservationsByPhone->get($phone) : null;
        });

        return view('reservas.invitados', [
            'guests' => $guests,
        ]);
    }

    /**
     * Alta manual de un invitado de Fermento (ej. se sumó alguien después de
     * la tanda inicial de invitaciones). El token del link personalizado se
     * genera solo, igual que los demás — ver FermentoGuest::booted().
     */
    public function storeGuest(Request $request): RedirectResponse
    {
        $event = Event::where('slug', 'fermento')->firstOrFail();

        $normalizedPhone = Customer::normalizePhone((string) $request->input('phone'));
        $request->merge(['phone' => $normalizedPhone]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'phone' => ['nullable', 'string', 'max:30'],
        ]);

        $guest = FermentoGuest::create([
            'event_id' => $event->id,
            'name' => $validated['name'],
            'phone' => $validated['phone'] ?? null,
            'is_test' => $request->boolean('is_test'),
        ]);

        return back()->with('status', "Invitado {$guest->name} agregado.");
    }

    /**
     * Edita a mano nombre/teléfono del invitado (varios de la primera tanda
     * se cargaron sin teléfono — hay que poder completarlo acá).
     */
    public function updateGuest(Request $request, FermentoGuest $guest): RedirectResponse
    {
        $normalizedPhone = Customer::normalizePhone((string) $request->input('phone'));
        $request->merge(['phone' => $normalizedPhone]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'phone' => ['nullable', 'string', 'max:30'],
        ]);

        $guest->update($validated);

        return back()->with('status', "Invitado {$guest->name} actualizado.");
    }

    /**
     * Marca el mensaje 1 (intriga) como enviado. Idempotente: si ya estaba
     * marcado, no lo pisa (evita perder la fecha real del primer envío).
     */
    public function markMensaje1(FermentoGuest $guest): RedirectResponse
    {
        if (! $guest->whatsapp_sent_at) {
            $guest->update(['whatsapp_sent_at' => now()]);
        }

        return back();
    }

    /**
     * Marca/desmarca que el invitado respondió con interés al mensaje 1 —
     * mismo patrón de checkbox-toggle que el VIP de clientes.blade.php.
     */
    public function toggleAceptado(FermentoGuest $guest): RedirectResponse
    {
        $guest->update([
            'interest_confirmed_at' => $guest->interest_confirmed_at ? null : now(),
        ]);

        return back();
    }

    /**
     * Marca el mensaje 2 (invitación completa) como enviado. Idempotente,
     * igual que markMensaje1().
     */
    public function markMensaje2(FermentoGuest $guest): RedirectResponse
    {
        if (! $guest->invite_sent_at) {
            $guest->update(['invite_sent_at' => now()]);
        }

        return back();
    }
}
