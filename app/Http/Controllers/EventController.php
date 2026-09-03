<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventSchedule;
use App\Models\FermentoGuest;
use App\Models\Influencer;
use App\Models\IntimoGuest;
use Illuminate\View\View;

class EventController extends Controller
{
    /**
     * Landing del evento Íntimo: historia, menú y sistema de reservas.
     * Si llega un $token válido, se personaliza con el nombre del invitado.
     */
    public function intimo(?string $token = null): View
    {
        $event = Event::where('slug', 'intimo')
            ->where('is_active', true)
            ->firstOrFail();

        $guest = null;

        if ($token) {
            $guest = IntimoGuest::where('event_id', $event->id)
                ->where('token', $token)
                ->first();

            if ($guest && ! $guest->opened_at) {
                $guest->update(['opened_at' => now()]);
            }
        }

        $schedules = $event->upcomingSchedules();

        // Agrupamos los horarios por fecha para pintar el selector de fecha -> hora en el front,
        // con la etiqueta ya formateada en español (sin depender del locale del servidor).
        $dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
        $meses = [1 => 'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];

        $schedulesByDate = $schedules
            ->groupBy(fn ($schedule) => $schedule->date->toDateString())
            ->map(function ($group, $date) use ($dias, $meses) {
                $carbonDate = $group->first()->date;

                return [
                    'label' => $dias[(int) $carbonDate->format('w')] . ' ' . (int) $carbonDate->format('j') . ' de ' . $meses[(int) $carbonDate->format('n')],
                    'schedules' => $group,
                ];
            });

        return view('home.intimo', [
            'event' => $event,
            'schedulesByDate' => $schedulesByDate,
            'guest' => $guest,
        ]);
    }

    /**
     * Landing de Fermento (CLAN x FORNO): historia, cocina y reserva de mesa
     * por fecha. A diferencia de Íntimo, cada horario tiene mesas propias con
     * su propio aforo, así que además del selector de fecha se manda al front
     * la disponibilidad de las mesas por horario para el picker de mesa.
     *
     * A diferencia de Íntimo (que solo lista fechas con cupo, vía
     * Event::upcomingSchedules), acá se listan TODAS las fechas futuras del
     * evento, cerradas o agotadas incluidas — el selector las muestra
     * igual, pero deshabilitadas/con lista de espera según su estado (ver
     * $status en $schedulesByDate). Ocultar una fecha agotada en vez de
     * mostrarla como tal fue justamente el problema que pidió resolver el
     * cliente para el 5 de septiembre.
     */
    public function fermento(?string $token = null): View
    {
        $event = Event::where('slug', 'fermento')
            ->where('is_active', true)
            ->firstOrFail();

        $guest = null;

        if ($token) {
            $guest = FermentoGuest::where('event_id', $event->id)
                ->where('token', $token)
                ->first();

            // Los links de influencers (panel /influencers/admin) también
            // abren esta misma landing — no tienen tabla propia de invitados
            // "normales", así que si no es un FermentoGuest, probamos con
            // Influencer. La vista solo necesita ->first_name/->opened_at,
            // que ambos modelos exponen igual.
            if (! $guest) {
                $guest = Influencer::where('token', $token)->first();
            }

            if ($guest && ! $guest->opened_at) {
                $guest->update(['opened_at' => now()]);
            }
        }

        $schedules = $event->schedules()
            ->where('date', '>=', now()->toDateString())
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        $dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
        $meses = [1 => 'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];

        $schedulesByDate = $schedules
            ->groupBy(fn ($schedule) => $schedule->date->toDateString())
            ->map(function ($group, $date) use ($dias, $meses) {
                $carbonDate = $group->first()->date;

                return [
                    'label' => $dias[(int) $carbonDate->format('w')] . ' ' . (int) $carbonDate->format('j') . ' de ' . $meses[(int) $carbonDate->format('n')],
                    'schedules' => $group,
                ];
            });

        // Disponibilidad de mesas por horario, para que el front pinte el grid
        // apenas el visitante elige una fecha (sin ida y vuelta al server), y
        // estado por horario para decidir si se muestra el grid normal, la
        // lista de espera, o el aviso de "agotado":
        // - closed: is_active=false (el staff cerró la fecha del todo)
        // - open: queda alguna mesa (exclusiva libre o social con cupo)
        // - waitlist: sin mesas, pero la lista de espera sigue abierta
        // - full: sin mesas y lista de espera cerrada a mano
        $tablesBySchedule = $schedules->mapWithKeys(
            fn ($schedule) => [$schedule->id => $schedule->tablesWithAvailability()]
        );

        $scheduleStatus = $schedules->mapWithKeys(function (EventSchedule $schedule) {
            if (! $schedule->is_active) {
                $status = 'closed';
            } elseif ($schedule->has_free_table) {
                $status = 'open';
            } elseif (! $schedule->waitlist_closed) {
                $status = 'waitlist';
            } else {
                $status = 'full';
            }

            return [$schedule->id => $status];
        });

        // Aparte del status agregado de arriba: la mesa social puede estar
        // llena mientras la fecha sigue "open" (otras mesas normales con
        // cupo) — clic en una mesa social sin cupo manda a lista de espera
        // igual, salvo que el staff ya la haya cerrado para esa fecha (ver
        // fermento.blade.php).
        $scheduleWaitlistClosed = $schedules->mapWithKeys(
            fn (EventSchedule $schedule) => [$schedule->id => (bool) $schedule->waitlist_closed]
        );

        return view('home.fermento', [
            'event' => $event,
            'schedulesByDate' => $schedulesByDate,
            'tablesBySchedule' => $tablesBySchedule,
            'scheduleStatus' => $scheduleStatus,
            'scheduleWaitlistClosed' => $scheduleWaitlistClosed,
            'guest' => $guest,
        ]);
    }
}
