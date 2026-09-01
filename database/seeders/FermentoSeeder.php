<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\EventSchedule;
use App\Models\EventTable;
use Illuminate\Database\Seeder;

class FermentoSeeder extends Seeder
{
    /**
     * Fermento: cena colaborativa CLAN x FORNO (pizza al fuego, maridaje de
     * vinos aparte), viernes 4, sábado 5 y domingo 6 de septiembre de 2026,
     * con 9 mesas propias por fecha. El 5 de septiembre está marcado AGOTADO
     * (is_active=false, ver más abajo) por decisión del staff.
     *
     * Las 9 mesas son individuales, cada una admite de 1 a 4 personas
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

        for ($n = 1; $n <= 9; $n++) {
            EventTable::updateOrCreate(
                ['event_id' => $event->id, 'table_number' => $n],
                ['capacity_min' => 1, 'capacity_max' => 4]
            );
        }

        // Config anterior tenía 12 mesas — borra las sobrantes (10-12) si
        // vienes de esa siembra.
        EventTable::where('event_id', $event->id)->where('table_number', '>', 9)->delete();

        // Fechas y hora fijas del evento (no calculadas desde "hoy"): viernes,
        // sábado y domingo, 7:00 pm. La hora anterior (20:00) fue un valor por
        // defecto mío al sembrar por primera vez, sin ninguna razón de negocio
        // detrás.
        //
        // 5 de septiembre: AGOTADA por decisión del staff (no por llenarse las
        // 9 mesas) — is_active=false la saca del selector público y bloquea
        // altas nuevas (públicas y manuales desde /reservas/admin), pero las
        // reservas ya hechas para esa fecha se siguen viendo y editando
        // normal en el panel admin (is_active no se consulta ahí).
        // 6 de septiembre: fecha nueva agregada por demanda, mismas mesas y
        // horario que las otras.
        $dates = [
            '2026-09-04' => true,
            '2026-09-05' => false,
            '2026-09-06' => true,
        ];
        $startTime = '19:00:00';

        // Limpia cualquier horario sembrado antes con otra fecha/hora (ronda
        // anterior usó "próximo viernes/sábado" calculado dinámicamente a las
        // 20:00, hoy quedó mal en ambos sentidos).
        EventSchedule::where('event_id', $event->id)
            ->where(function ($query) use ($dates, $startTime) {
                $query->whereNotIn('date', array_keys($dates))
                    ->orWhere('start_time', '!=', $startTime);
            })
            ->delete();

        foreach ($dates as $date => $isActive) {
            EventSchedule::updateOrCreate(
                [
                    'event_id' => $event->id,
                    'date' => $date,
                    'start_time' => $startTime,
                ],
                [
                    // Capacidad = número de mesas: cada reserva ocupa exactamente una.
                    'capacity' => 9,
                    'is_active' => $isActive,
                ]
            );
        }

        // Los invitados de Fermento (los 10 reales + Gustavo/Mauricio/Daniel +
        // el especial "Pruebas") no se siembran acá — ver el comando dedicado
        // `php artisan fermento:guests-seed`, que ya es la fuente de verdad
        // idempotente para ellos (por teléfono) y no debe duplicarse aquí.
    }
}
