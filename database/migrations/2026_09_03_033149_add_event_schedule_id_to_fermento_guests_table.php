<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Fecha a la que está invitado el VIP (viernes 4 o domingo 6 — el sábado
     * 5 no aplica, está agotado). Nullable: los invitados de la tanda de
     * outreach anterior no tenían fecha asignada, y no es obligatorio
     * asignarla para poder seguir usando el resto del panel.
     */
    public function up(): void
    {
        Schema::table('fermento_guests', function (Blueprint $table) {
            $table->foreignId('event_schedule_id')->nullable()->after('event_id')->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('fermento_guests', function (Blueprint $table) {
            $table->dropConstrainedForeignId('event_schedule_id');
        });
    }
};
