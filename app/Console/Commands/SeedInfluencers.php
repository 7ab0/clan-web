<?php

namespace App\Console\Commands;

use App\Models\Influencer;
use Illuminate\Console\Command;

class SeedInfluencers extends Command
{
    /**
     * Carga puntual de los 18 influencers reales invitados al pre-cóctel de
     * Fermento (hoy, 31/8/2026, 8:00 pm, mismo local de Fermento). Handles de
     * redes y seguidores quedan null a propósito — se completan a mano desde
     * el panel según se vayan confirmando. Idempotente por teléfono —
     * correrlo de nuevo no duplica a nadie ni pisa datos ya editados a mano
     * en /influencers/admin (solo crea los que todavía no existen).
     *
     * php artisan influencers:seed
     */
    protected $signature = 'influencers:seed';

    protected $description = 'Crea los Influencer reales del pre-cóctel de Fermento e imprime sus links';

    /** @var array<int, array{name: string, phone: string}> */
    private array $influencers = [
        ['name' => 'Sayu Arroyo', 'phone' => '51970829317'],
        ['name' => 'Farrah Sosa', 'phone' => '51986974208'],
        ['name' => 'Samantha Pinto', 'phone' => '51958228449'],
        ['name' => 'Andrea Guzmán', 'phone' => '51949763315'],
        ['name' => 'Saray', 'phone' => '51936113134'],
        ['name' => 'Renzo Ugarte', 'phone' => '51991684599'],
        ['name' => 'Maria Jose Guzmán', 'phone' => '51991685168'],
        ['name' => 'Jeriko', 'phone' => '51933946814'],
        ['name' => 'Valeria Valicha', 'phone' => '51930484007'],
        ['name' => 'Maria Elena Huaco', 'phone' => '51984766126'],
        ['name' => 'Piero del Castillo', 'phone' => '51958249933'],
        ['name' => 'Mafer Sánchez', 'phone' => '51941778212'],
        ['name' => 'Abraham Torres', 'phone' => '51936449409'],
        ['name' => 'Johan Laura', 'phone' => '51913002003'],
        ['name' => 'Luis Dávila', 'phone' => '51986768118'],
        ['name' => 'Zalma', 'phone' => '51992610094'],
        ['name' => 'Flavia Aza', 'phone' => '51916569651'],
        ['name' => 'Liz (cuchara maldita)', 'phone' => '51952362466'],
    ];

    public function handle(): int
    {
        foreach ($this->influencers as $data) {
            $influencer = Influencer::where('phone', $data['phone'])->first();

            if (! $influencer) {
                $influencer = Influencer::create([
                    'name' => $data['name'],
                    'phone' => $data['phone'],
                    'notes' => 'Puede traer +1 acompañante',
                    'is_test' => false,
                ]);
            }

            $this->line(str_pad($influencer->name, 24) . ' -> https://clan-rest.club/fermento/' . $influencer->token);
        }

        return self::SUCCESS;
    }
}
