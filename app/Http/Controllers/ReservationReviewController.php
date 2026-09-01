<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventSchedule;
use App\Models\Reservation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

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
     * Solo reservas confirmadas y reales (nunca is_test) de Fermento — este
     * panel se comparte con FORNO/MOLTO como socios de Fermento, no tiene
     * sentido que vean reservas de Íntimo (otro evento de CLAN, sin
     * relación con ellos).
     */
    public function index(): View
    {
        $reservations = Reservation::with(['event', 'schedule', 'table'])
            ->whereHas('event', fn ($q) => $q->where('slug', 'fermento'))
            ->where('status', 'confirmed')
            ->where('is_test', false)
            ->orderBy('created_at', 'desc')
            ->get();

        $event = Event::where('slug', 'fermento')->firstOrFail();

        $schedules = EventSchedule::where('event_id', $event->id)
            ->orderBy('date')
            ->orderBy('start_time')
            ->get()
            ->map(function (EventSchedule $schedule) {
                $tables = $schedule->tablesWithAvailability();

                return [
                    'fecha' => $schedule->date->format('d/m/Y') . ' · ' . \Illuminate\Support\Str::of($schedule->start_time)->substr(0, 5),
                    'total' => $tables->count(),
                    'ocupadas' => $tables->where('is_taken', true)->count(),
                    'libres' => $tables->where('is_taken', false)->count(),
                    'is_active' => $schedule->is_active,
                ];
            });

        return view('reservas.review', [
            'reservations' => $reservations,
            'schedules' => $schedules,
        ]);
    }

    /**
     * CSV de las mismas reservas que ve este panel: solo Fermento,
     * confirmadas y reales (nunca is_test) — mismo filtro que index().
     */
    public function export(): StreamedResponse
    {
        $reservations = Reservation::with(['event', 'schedule', 'table', 'payment'])
            ->whereHas('event', fn ($q) => $q->where('slug', 'fermento'))
            ->where('status', 'confirmed')
            ->where('is_test', false)
            ->orderBy('created_at', 'desc')
            ->get();

        return $this->csvDownload(
            'reservas-fermento-confirmadas-' . now()->format('Y-m-d') . '.csv',
            Reservation::csvHeaders(),
            $reservations->map->toCsvRow()
        );
    }
}
