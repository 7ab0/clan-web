<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Cierre manual de la lista de espera de una fecha (independiente de
     * is_active, que cierra la fecha del todo). El encargado la marca a mano
     * cuando decide que ya no quiere sumar más gente a la espera — no hay
     * regla automática de "cuántos son demasiados".
     */
    public function up(): void
    {
        Schema::table('event_schedules', function (Blueprint $table) {
            $table->boolean('waitlist_closed')->default(false)->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('event_schedules', function (Blueprint $table) {
            $table->dropColumn('waitlist_closed');
        });
    }
};
