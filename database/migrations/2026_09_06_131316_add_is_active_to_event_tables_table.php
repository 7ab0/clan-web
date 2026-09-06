<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Permite al staff deshabilitar una mesa puntual sin tocar sus datos ni
     * sus reservas — usado hoy 6 de sept. para dejar SOLO la mesa
     * comunitaria disponible por un cambio de logística de último momento
     * (ver mesas.blade.php: nuevo botón Habilitar/Deshabilitar). Default
     * true para no afectar ninguna mesa existente de otras fechas/eventos.
     *
     * Además de agregar la columna, esta migración deja directamente
     * deshabilitadas las mesas exclusivas (no sociales) del 6 de septiembre
     * — es la mesa comunitaria la que arranca hoy siendo la única mesa
     * disponible en el flujo público. El staff puede reactivar cualquiera
     * desde /reservas/admin/mesas si hace falta.
     */
    public function up(): void
    {
        Schema::table('event_tables', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('is_social');
        });

        // Dos pasos en vez de un update con join: MySQL (producción) soporta
        // UPDATE...JOIN, pero SQLite (dev local) no lo traduce igual —
        // whereIn es portable en los dos motores.
        $scheduleIds = DB::table('event_schedules')
            ->where('date', '2026-09-06')
            ->pluck('id');

        DB::table('event_tables')
            ->whereIn('event_schedule_id', $scheduleIds)
            ->where('is_social', false)
            ->update(['is_active' => false]);
    }

    public function down(): void
    {
        Schema::table('event_tables', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
};
