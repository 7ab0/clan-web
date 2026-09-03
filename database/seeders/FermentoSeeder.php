<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\EventSchedule;
use App\Models\EventTable;
use App\Models\Reservation;
use Illuminate\Database\Seeder;

class FermentoSeeder extends Seeder
{
    /**
     * Fermento: cena colaborativa CLAN x FORNO (pizza al fuego, maridaje de
     * vinos aparte), viernes 4, sábado 5 y domingo 6 de septiembre de 2026.
     * El 5 de septiembre está marcado AGOTADO (is_active=false) por decisión
     * del staff — mantiene su distribución original de 9 mesas de 1 a 4
     * personas, sin mesa social ni lista de espera, y no se toca.
     *
     * Viernes 4 y domingo 6 tienen cada uno su propia distribución de 9
     * mesas (capacidades distintas por mesa y por fecha — ver $tableLayouts)
     * más una mesa social (#10) donde varios grupos que reservan por
     * separado comparten aforo. Las mesas ya no son compartidas a nivel
     * evento: cada fecha tiene su propio set (event_schedule_id en
     * event_tables), porque las capacidades difieren entre fechas.
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

        // Fechas y hora fijas del evento (no calculadas desde "hoy"): viernes,
        // sábado y domingo, 7:00 pm.
        //
        // 5 de septiembre: AGOTADA por decisión del staff (no por llenarse las
        // 9 mesas) — is_active=false la saca del selector de reserva y
        // bloquea altas nuevas (públicas y manuales desde /reservas/admin),
        // pero las reservas ya hechas para esa fecha se siguen viendo y
        // editando normal en el panel admin.
        $dates = [
            '2026-09-04' => true,
            '2026-09-05' => false,
            '2026-09-06' => true,
        ];
        $startTime = '19:00:00';

        // Limpia cualquier horario sembrado antes con otra fecha/hora.
        EventSchedule::where('event_id', $event->id)
            ->where(function ($query) use ($dates, $startTime) {
                $query->whereNotIn('date', array_keys($dates))
                    ->orWhere('start_time', '!=', $startTime);
            })
            ->delete();

        $schedulesByDate = [];
        foreach ($dates as $date => $isActive) {
            $schedulesByDate[$date] = EventSchedule::updateOrCreate(
                [
                    'event_id' => $event->id,
                    'date' => $date,
                    'start_time' => $startTime,
                ],
                [
                    // Capacidad = número de mesas exclusivas; ya no gobierna
                    // la disponibilidad real (ver EventSchedule::hasFreeTable),
                    // pero se deja consistente por si algún reporte viejo la usa.
                    'capacity' => 9,
                    'is_active' => $isActive,
                ]
            );
        }

        // Distribución de mesas por fecha. null = mesa exclusiva "estándar"
        // (1 a 4 personas, distribución original, usada tal cual en el 5 de
        // septiembre porque esa fecha no se toca). Para el 4 y el 6 se
        // reemplaza por la distribución real del cliente, más una mesa
        // social (#10) exclusiva de esas dos fechas.
        $standardTable = ['capacity_min' => 1, 'capacity_max' => 4, 'is_social' => false];

        $tableLayouts = [
            '2026-09-04' => [
                1 => ['capacity_min' => 1, 'capacity_max' => 1, 'is_social' => false],
                2 => ['capacity_min' => 1, 'capacity_max' => 1, 'is_social' => false],
                3 => ['capacity_min' => 1, 'capacity_max' => 2, 'is_social' => false],
                4 => ['capacity_min' => 1, 'capacity_max' => 2, 'is_social' => false],
                5 => ['capacity_min' => 1, 'capacity_max' => 4, 'is_social' => false],
                6 => ['capacity_min' => 1, 'capacity_max' => 6, 'is_social' => false],
                7 => ['capacity_min' => 1, 'capacity_max' => 4, 'is_social' => false],
                8 => ['capacity_min' => 1, 'capacity_max' => 4, 'is_social' => false],
                9 => ['capacity_min' => 1, 'capacity_max' => 4, 'is_social' => false],
                // Mesa social: capacidad total acumulada entre varios grupos.
                // Ajustable — no viene especificada por el cliente, 10 es un
                // punto de partida razonable para una mesa comunitaria.
                10 => ['capacity_min' => 1, 'capacity_max' => 10, 'is_social' => true],
            ],
            '2026-09-05' => array_fill(1, 9, $standardTable),
            '2026-09-06' => [
                1 => ['capacity_min' => 1, 'capacity_max' => 8, 'is_social' => false],
                2 => ['capacity_min' => 1, 'capacity_max' => 2, 'is_social' => false],
                3 => ['capacity_min' => 1, 'capacity_max' => 2, 'is_social' => false],
                4 => ['capacity_min' => 1, 'capacity_max' => 4, 'is_social' => false],
                5 => ['capacity_min' => 1, 'capacity_max' => 4, 'is_social' => false],
                6 => ['capacity_min' => 1, 'capacity_max' => 2, 'is_social' => false],
                7 => ['capacity_min' => 1, 'capacity_max' => 2, 'is_social' => false],
                8 => ['capacity_min' => 1, 'capacity_max' => 4, 'is_social' => false],
                9 => ['capacity_min' => 1, 'capacity_max' => 4, 'is_social' => false],
                10 => ['capacity_min' => 1, 'capacity_max' => 10, 'is_social' => true],
            ],
        ];

        foreach ($tableLayouts as $date => $layout) {
            $schedule = $schedulesByDate[$date];

            foreach ($layout as $tableNumber => $spec) {
                EventTable::updateOrCreate(
                    [
                        'event_id' => $event->id,
                        'event_schedule_id' => $schedule->id,
                        'table_number' => $tableNumber,
                    ],
                    $spec
                );
            }
        }

        // Migra las reservas reales hechas antes de este cambio, cuando las
        // mesas todavía eran compartidas a nivel evento (event_schedule_id
        // null en event_tables) — las reapunta a la fila nueva de su misma
        // fecha y mismo número de mesa, sin tocar nada más de la reserva.
        // Idempotente: en la segunda corrida ya no quedan mesas viejas que
        // repuntar.
        $oldTables = EventTable::where('event_id', $event->id)
            ->whereNull('event_schedule_id')
            ->get()
            ->keyBy('id');

        if ($oldTables->isNotEmpty()) {
            Reservation::where('event_id', $event->id)
                ->whereIn('event_table_id', $oldTables->keys())
                ->get()
                ->each(function (Reservation $reservation) use ($oldTables) {
                    $oldTable = $oldTables->get($reservation->event_table_id);

                    $newTable = EventTable::where('event_id', $reservation->event_id)
                        ->where('event_schedule_id', $reservation->event_schedule_id)
                        ->where('table_number', $oldTable->table_number)
                        ->first();

                    if ($newTable) {
                        $reservation->update(['event_table_id' => $newTable->id]);
                    }
                });

            EventTable::destroy($oldTables->keys());
        }

        // Los invitados de Fermento (los 10 reales + Gustavo/Mauricio/Daniel +
        // el especial "Pruebas") no se siembran acá — ver el comando dedicado
        // `php artisan fermento:guests-seed`, que ya es la fuente de verdad
        // idempotente para ellos (por teléfono) y no debe duplicarse aquí.
    }
}
