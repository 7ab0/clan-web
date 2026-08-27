<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Event;
use App\Models\Reservation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

        return view('reservas.admin', [
            'reservations' => $reservations,
            'summary' => $summary,
            'events' => Event::orderBy('name')->get(['slug', 'name']),
            'eventSlug' => $eventSlug,
            'status' => $status,
            'sort' => $sort,
            'dir' => $dir,
        ]);
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
}
