<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Invitado/reserva de pruebas (ver FermentoSeeder::seedGuests +
     * ReservationController::store): el link "Pruebas" deja avanzar todo el
     * flujo real (reserva → pago → confirmación) pero la reserva queda
     * marcada is_test=true — no cuenta para el aforo de mesas (no le quita
     * cupo a nadie más) y no toca la base de clientes.
     */
    public function up(): void
    {
        Schema::table('fermento_guests', function (Blueprint $table) {
            $table->boolean('is_test')->default(false)->after('invite_sent_at');
        });

        Schema::table('reservations', function (Blueprint $table) {
            $table->boolean('is_test')->default(false)->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('fermento_guests', function (Blueprint $table) {
            $table->dropColumn('is_test');
        });

        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn('is_test');
        });
    }
};
