<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;

class IntimoSeeder extends Seeder
{
    /**
     * Crea (o actualiza) el evento Íntimo.
     *
     * A propósito NO genera turnos (EventSchedule): el evento se está
     * reprogramando y todavía no hay fecha nueva confirmada. Sin turnos,
     * la vista ya cae sola en su estado "no hay turnos disponibles por
     * ahora" — apenas se defina la fecha, se cargan los turnos reales
     * (a mano o con un seeder de turnos) y el formulario se activa solo.
     */
    public function run(): void
    {
        Event::updateOrCreate(
            ['slug' => 'intimo'],
            [
                'project' => 'clan',
                'name' => 'Íntimo',
                'tagline' => 'No es una cena. Es una conversación que solo ocurre una vez.',
                'description' => 'Íntimo es un ritual de 8 tiempos alrededor del fogón, diseñado para dos personas. '
                    . 'Cada plato es un capítulo de una conversación guiada por un libro con acertijos y preguntas '
                    . 'que se resuelven conforme llegan los platos. No importa si vienes con tu pareja, un amigo, '
                    . 'un hermano o tu madre: la experiencia está pensada para generar diálogo y memoria compartida.',
                'courses' => 8,
                'party_size' => 2,
                'price' => 160.00,
                'currency' => 'PEN',
                'video_url' => null,
                'cover_image' => null,
                'is_active' => true,
            ]
        );
    }
}
