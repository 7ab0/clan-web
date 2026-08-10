<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PaymentController extends Controller
{
    /**
     * Muestra el resumen de la reserva y el formulario de pago.
     */
    public function show(string $code): View
    {
        $reservation = Reservation::with(['event', 'schedule', 'payment'])
            ->where('code', $code)
            ->firstOrFail();

        return view('home.pago', [
            'reservation' => $reservation,
        ]);
    }

    /**
     * Procesa el pago.
     *
     * Hoy: simula una pasarela y marca el pago como pagado al instante.
     * Mañana: aquí se llama a Culqi/Mercado Pago/Stripe con el token del formulario,
     * y solo se marca "paid" si la pasarela confirma el cargo.
     */
    public function process(string $code): RedirectResponse
    {
        $reservation = Reservation::with('payment')->where('code', $code)->firstOrFail();

        if ($reservation->status === 'confirmed') {
            return redirect()->route('reservas.confirmacion', $reservation->code);
        }

        $payment = $reservation->payment;
        $payment->update([
            'status' => 'paid',
            'provider_reference' => 'SIM-' . strtoupper(uniqid()),
            'paid_at' => now(),
        ]);

        $reservation->update(['status' => 'confirmed']);

        return redirect()->route('reservas.confirmacion', $reservation->code);
    }

    public function confirmation(string $code): View
    {
        $reservation = Reservation::with(['event', 'schedule', 'payment'])
            ->where('code', $code)
            ->firstOrFail();

        return view('home.confirmacion', [
            'reservation' => $reservation,
        ]);
    }
}
