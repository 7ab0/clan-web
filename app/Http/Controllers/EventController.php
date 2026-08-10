<?php

namespace App\Http\Controllers;

use App\Models\Event;
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
}
