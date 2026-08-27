<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Reservation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Panel de solo lectura para revisar las reservas ya confirmadas — pensado
 * para compartir con el equipo de FORNO/MOLTO sin darles acceso al panel de
 * administración completo (editar, eliminar, confirmar pagos, invitados,
 * clientes). Contraseña y sesión propias, independientes de
 * ReservationAdminController — ver config('services.reservas.review_password').
 */
class ReservationReviewController extends Controller
{
    public function loginForm(Request $request): View|RedirectResponse
    {
        if ($request->session()->get('reservas_review_authenticated')) {
            return redirect()->route('reservas.review.index');
        }

        return view('reservas.review-login');
    }

    public function login(Request $request): RedirectResponse
    {
        $request->validate(['password' => ['required', 'string']]);

        $expected = (string) config('services.reservas.review_password');

        if ($expected === '' || ! hash_equals($expected, (string) $request->input('password'))) {
            return back()->withErrors(['password' => 'Contraseña incorrecta.']);
        }

        $request->session()->put('reservas_review_authenticated', true);
        $request->session()->regenerate();

        return redirect()->route('reservas.review.index');
    }

    public function logout(Request $request): RedirectResponse
    {
        $request->session()->forget('reservas_review_authenticated');

        return redirect()->route('reservas.review.login');
    }

    /**
     * Solo reservas confirmadas y reales (nunca is_test) — a un revisor
     * externo no le sirve ver pendientes ni reservas de prueba.
     */
    public function index(Request $request): View
    {
        $eventSlug = (string) $request->query('event', 'todos');

        $query = Reservation::with(['event', 'schedule', 'table'])
            ->where('status', 'confirmed')
            ->where('is_test', false);

        if ($eventSlug !== 'todos') {
            $query->whereHas('event', fn ($q) => $q->where('slug', $eventSlug));
        }

        $reservations = $query->orderBy('created_at', 'desc')->get();

        return view('reservas.review', [
            'reservations' => $reservations,
            'events' => Event::orderBy('name')->get(['slug', 'name']),
            'eventSlug' => $eventSlug,
        ]);
    }
}
