<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\View\View;

class PaymentController extends Controller
{
    /**
     * Muestra el resumen de la reserva y el link de WhatsApp para coordinar
     * el pago de la seña.
     *
     * La confirmación del pago ya no la dispara el cliente desde aquí (ese
     * endpoint público confirmaba cualquier reserva sin verificar ningún
     * pago real) — ahora la hace el staff a mano desde el panel
     * /reservas/admin (ver ReservationAdminController::confirmPayment).
     */
    public function show(string $code): View
    {
        $reservation = Reservation::with(['event', 'schedule', 'table', 'payment'])
            ->where('code', $code)
            ->firstOrFail();

        return view('home.pago', [
            'reservation' => $reservation,
        ]);
    }

    public function confirmation(string $code): View
    {
        $reservation = Reservation::with(['event', 'schedule', 'table', 'payment'])
            ->where('code', $code)
            ->firstOrFail();

        return view('home.confirmacion', [
            'reservation' => $reservation,
        ]);
    }
}
