<?php

namespace App\Console\Commands;

use App\Models\Customer;
use App\Models\Event;
use App\Models\FermentoGuest;
use App\Models\Payment;
use App\Models\Reservation;
use Illuminate\Console\Command;

class ResetFermentoTestData extends Command
{
    /**
     * Reset puntual antes de lanzar Fermento en serio: borra las reservas
     * (y pagos) de prueba hechas mientras se armaba el flujo, vacía la base
     * de clientes (alimentada solo por esas reservas de prueba hasta ahora),
     * y resetea el avance de outreach (abrió el link / mensaje enviado /
     * aceptó) de los 10 invitados reales de la primera tanda — sin tocar sus
     * tokens/links personalizados, que ya se compartieron por WhatsApp.
     *
     * De paso vuelve a correr FermentoSeeder (9 mesas de 1 a 4 personas,
     * fechas/horarios) y fermento:guests-seed (invitados reales + Gustavo/
     * Mauricio/Daniel/Pruebas) — ambos idempotentes, así que este comando
     * deja todo listo en un solo paso. No toca Íntimo (otro evento, otras
     * reservas).
     *
     * php artisan fermento:reset
     * php artisan fermento:reset --force   (sin el prompt de confirmación,
     *                                        para correrlo no-interactivo
     *                                        desde Laravel Cloud Commands)
     */
    protected $signature = 'fermento:reset {--force : Omite la confirmación}';

    protected $description = 'Borra reservas/pagos/clientes de prueba de Fermento y resetea el avance de outreach de los 10 invitados reales (sus links no cambian)';

    /**
     * Tokens de la primera tanda de invitados reales (ver
     * SeedFermentoGuests) cuyo avance de outreach se resetea a cero.
     * Gustavo/Mauricio/Daniel/Pruebas no están acá porque son nuevos —
     * ya arrancan sin avance.
     */
    private array $realGuestTokens = [
        'fg0c6yba', 'i96ity4o', 'tmokiqlu', 'i5fmtjta', 'uwt243b5',
        'l8rwen84', 'eblfvtwd', 'kysdrs8r', 'fwlrqfbm', 'byojfpyy',
    ];

    public function handle(): int
    {
        $event = Event::where('slug', 'fermento')->first();

        if (! $event) {
            $this->error('No encontré el evento "fermento". Corre antes: php artisan db:seed --class=FermentoSeeder');

            return self::FAILURE;
        }

        $reservationCount = Reservation::where('event_id', $event->id)->count();
        $customerCount = Customer::count();

        if (! $this->option('force')) {
            $this->warn("Esto borra {$reservationCount} reserva(s) de Fermento y sus pagos, vacía los {$customerCount} cliente(s) de la base, y resetea el avance de outreach de los 10 invitados reales (sus links NO cambian).");

            if (! $this->confirm('¿Continuar?')) {
                $this->info('Cancelado, no se tocó nada.');

                return self::SUCCESS;
            }
        }

        $reservationIds = Reservation::where('event_id', $event->id)->pluck('id');
        Payment::whereIn('reservation_id', $reservationIds)->delete();
        Reservation::whereIn('id', $reservationIds)->delete();
        Customer::query()->delete();

        FermentoGuest::where('event_id', $event->id)
            ->whereIn('token', $this->realGuestTokens)
            ->update([
                'opened_at' => null,
                'whatsapp_sent_at' => null,
                'interest_confirmed_at' => null,
                'invite_sent_at' => null,
            ]);

        $this->call('db:seed', ['--class' => 'Database\\Seeders\\FermentoSeeder', '--force' => true]);
        $this->call('fermento:guests-seed');

        $this->info('Listo: reservas/pagos/clientes de prueba borrados, invitados reales reseteados con sus links intactos, mesas actualizadas a 9 (1 a 4 personas), y Gustavo/Mauricio/Daniel/Pruebas sembrados.');

        return self::SUCCESS;
    }
}
