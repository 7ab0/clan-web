<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Models\FermentoGuest;
use Illuminate\Console\Command;

class SeedFermentoGuests extends Command
{
    /**
     * Carga puntual de los invitados reales de Fermento: la primera tanda de
     * 10, la segunda tanda (Gustavo, Mauricio, Daniel), y el invitado
     * especial "Pruebas" (is_test=true, sin teléfono — su reserva no ocupa
     * mesa real ni entra a la base de clientes, ver
     * ReservationController::store). Idempotente: los que tienen teléfono se
     * matchean por teléfono, "Pruebas" (sin teléfono) se matchea por nombre —
     * correrlo de nuevo no duplica a nadie ni pisa datos existentes.
     *
     * php artisan fermento:guests-seed
     */
    protected $signature = 'fermento:guests-seed';

    protected $description = 'Crea los FermentoGuest reales de Fermento (+ el invitado "Pruebas") e imprime sus links';

    /** @var array<int, array{name: string, phone: ?string, is_test?: bool}> */
    private array $guests = [
        ['name' => 'Joanie', 'phone' => '51958335322'],
        ['name' => 'Rosa Ángela', 'phone' => '51953766163'],
        ['name' => 'Yessica', 'phone' => '51979716343'],
        ['name' => 'Yohanna', 'phone' => '51951858405'],
        ['name' => 'Jean Piero', 'phone' => '51941043036'],
        ['name' => 'Carlos', 'phone' => '51954010495'],
        ['name' => 'Álvaro', 'phone' => '51942755636'],
        ['name' => 'Lucía Rosado', 'phone' => '51953760338'],
        ['name' => 'David Asparrin', 'phone' => '51946715522'],
        ['name' => 'Giulianna', 'phone' => '51959422188'],
        ['name' => 'Gustavo', 'phone' => '51900570321'],
        ['name' => 'Mauricio', 'phone' => '51985313221'],
        ['name' => 'Daniel', 'phone' => '51954760397'],
        ['name' => 'Pruebas', 'phone' => null, 'is_test' => true],
    ];

    public function handle(): int
    {
        $event = Event::where('slug', 'fermento')->first();

        if (! $event) {
            $this->error('No encontré el evento "fermento".');

            return self::FAILURE;
        }

        foreach ($this->guests as $data) {
            // Sin teléfono (solo el caso de "Pruebas") no se puede matchear
            // por teléfono — se matchea por nombre en su lugar.
            $guest = $data['phone']
                ? FermentoGuest::where('event_id', $event->id)->where('phone', $data['phone'])->first()
                : FermentoGuest::where('event_id', $event->id)->where('name', $data['name'])->first();

            if (! $guest) {
                $guest = FermentoGuest::create([
                    'event_id' => $event->id,
                    'name' => $data['name'],
                    'phone' => $data['phone'],
                    'is_test' => $data['is_test'] ?? false,
                ]);
            }

            $tag = $guest->is_test ? '  [PRUEBA — no ocupa mesa real]' : '';
            $this->line(str_pad($guest->name, 20) . ' -> https://clan-rest.club/fermento/' . $guest->token . $tag);
        }

        return self::SUCCESS;
    }
}
