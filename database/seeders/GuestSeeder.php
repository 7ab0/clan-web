<?php

namespace Database\Seeders;

use App\Models\Guest;
use Illuminate\Database\Seeder;

class GuestSeeder extends Seeder
{
    /**
     * Lista original de invitados, migrada desde
     * public/assets/showclinic/js/guests.js
     *
     * Los tres con "profession"/"compliment" reciben el pre-holder
     * personalizado (3 pantallas tipo historia) antes del sitio principal.
     */
    private const GUESTS = [
        ['code' => 'MDC7K2', 'name' => 'Maria del Carmen', 'profession' => 'Diseñadora', 'compliment' => 'Creas belleza. Hoy, es tu turno'],
        ['code' => 'GTV4P9', 'name' => 'Gustavo'],
        ['code' => 'MRC1L6', 'name' => 'Mauricio'],
        ['code' => 'DEE9X3', 'name' => 'Erick Espetia'],
        ['code' => 'EFB8H3', 'name' => 'Emily Franchesca', 'profession' => 'Bióloga', 'compliment' => 'Descubres la vida. Hoy, descubre tu mejor versión'],
        ['code' => 'KTM5R7', 'name' => 'Katherine', 'profession' => 'Marketing', 'compliment' => 'Conectas marcas con personas. Hoy, conecta contigo misma'],
    ];

    public function run(): void
    {
        foreach (self::GUESTS as $guest) {
            // No se toca "status" en el update: no queremos resetear a
            // "pendiente" la respuesta de un invitado que ya confirmó.
            // Al crear, la columna cae a su default ('pendiente') solita.
            Guest::updateOrCreate(
                ['code' => $guest['code']],
                [
                    'name' => $guest['name'],
                    'profession' => $guest['profession'] ?? null,
                    'compliment' => $guest['compliment'] ?? null,
                ]
            );
        }
    }
}
