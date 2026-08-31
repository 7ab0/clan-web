<?php

namespace App\Http\Controllers;

use App\Models\Event;
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
     * la disponibilidad de las 9 mesas por horario para el picker de mesa.
     */
    public function fermento(?string $token = null): View
    {
        $event = Event::with('tables')
            ->where('slug', 'fermento')
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

        $schedules = $event->upcomingSchedules();

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
        // de 9 mesas apenas el visitante elige una fecha (sin ida y vuelta al server).
        $tablesBySchedule = $schedules->mapWithKeys(
            fn ($schedule) => [$schedule->id => $schedule->tablesWithAvailability()]
        );

        return view('home.fermento', [
            'event' => $event,
            'schedulesByDate' => $schedulesByDate,
            'tablesBySchedule' => $tablesBySchedule,
            'guest' => $guest,
        ]);
    }
}
