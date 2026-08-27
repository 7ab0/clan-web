<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Models\FermentoGuest;
use Illuminate\Console\Command;

class SeedFermentoGuests extends Command
{
    /**
     * Carga puntual de la primera tanda de invitados reales de Fermento.
     * Idempotente por teléfono: correrlo de nuevo no duplica a nadie.
     *
     * php artisan fermento:guests-seed
     */
    protected $signature = 'fermento:guests-seed';

    protected $description = 'Crea los FermentoGuest de la primera tanda de invitados reales e imprime sus links';

    /** @var array<int, array{name: string, phone: string}> */
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
    ];

    public function handle(): int
    {
        $event = Event::where('slug', 'fermento')->first();

        if (! $event) {
            $this->error('No encontré el evento "fermento".');

            return self::FAILURE;
        }

        foreach ($this->guests as $data) {
            $guest = FermentoGuest::where('event_id', $event->id)
                ->where('phone', $data['phone'])
                ->first();

            if (! $guest) {
                $guest = FermentoGuest::create([
                    'event_id' => $event->id,
                    'name' => $data['name'],
                    'phone' => $data['phone'],
                ]);
            }

            $this->line(str_pad($guest->name, 20) . ' -> https://clan-rest.club/fermento/' . $guest->token);
        }

        return self::SUCCESS;
    }
}
