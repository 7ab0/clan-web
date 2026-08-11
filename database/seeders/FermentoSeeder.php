<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\EventSchedule;
use App\Models\EventTable;
use Illuminate\Database\Seeder;

class FermentoSeeder extends Seeder
{
    /**
     * Fermento: cena colaborativa CLAN x FORNO (pizza al fuego + vinos),
     * viernes 4 y sábado 5 de septiembre de 2026, con 12 mesas propias por fecha.
     *
     * Las 12 mesas son base para 2 personas, adaptables/combinables hasta 4
     * (capacity_min/capacity_max), en vez de aforo fijo por mesa.
     */
    public function run(): void
    {
        $event = Event::updateOrCreate(
            ['slug' => 'fermento'],
            [
                'project' => 'clan',
                'name' => 'Fermento',
                'tagline' => 'CLAN x FORNO: una noche de masa madre, fuego de leña y vino.',
                'description' => 'Fermento es el encuentro entre CLAN y FORNO alrededor de dos procesos que '
                    . 'empiezan igual: tiempo, calor y paciencia. Una noche comunitaria bajo carpa, con pizzas '
                    . 'al horno de leña, platos de autor de CLAN y una selección de vinos pensada para acompañar '
                    . 'ambas cocinas en la misma mesa.',
                'courses' => null,
                'party_size' => 4,
                'price' => 85.00,
                'currency' => 'PEN',
                'video_url' => null,
                'cover_image' => null,
                'is_active' => true,
            ]
        );

        for ($n = 1; $n <= 12; $n++) {
            EventTable::updateOrCreate(
                ['event_id' => $event->id, 'table_number' => $n],
                ['capacity_min' => 2, 'capacity_max' => 4]
            );
        }

        // Fechas y hora fijas del evento (no calculadas desde "hoy"): viernes y
        // sábado, 7:00 pm. La hora anterior (20:00) fue un valor por defecto
        // mío al sembrar por primera vez, sin ninguna razón de negocio detrás.
        $dates = ['2026-09-04', '2026-09-05'];
        $startTime = '19:00:00';

        // Limpia cualquier horario sembrado antes con otra fecha/hora (ronda
        // anterior usó "próximo viernes/sábado" calculado dinámicamente a las
        // 20:00, hoy quedó mal en ambos sentidos).
        EventSchedule::where('event_id', $event->id)
            ->where(function ($query) use ($dates, $startTime) {
                $query->whereNotIn('date', $dates)
                    ->orWhere('start_time', '!=', $startTime);
            })
            ->delete();

        foreach ($dates as $date) {
            EventSchedule::updateOrCreate(
                [
                    'event_id' => $event->id,
                    'date' => $date,
                    'start_time' => $startTime,
                ],
                [
                    // Capacidad = número de mesas: cada reserva ocupa exactamente una.
                    'capacity' => 12,
                    'is_active' => true,
                ]
            );
        }
    }
}
