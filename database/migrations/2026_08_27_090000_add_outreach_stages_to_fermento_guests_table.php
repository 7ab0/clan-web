<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Envío de WhatsApp por etapas: whatsapp_sent_at (ya existe) marca el
     * mensaje 1 (intriga); interest_confirmed_at marca que el invitado
     * respondió con interés ("aceptó"); invite_sent_at marca el mensaje 2
     * (invitación completa, con su link personalizado).
     */
    public function up(): void
    {
        Schema::table('fermento_guests', function (Blueprint $table) {
            $table->timestamp('interest_confirmed_at')->nullable()->after('whatsapp_sent_at');
            $table->timestamp('invite_sent_at')->nullable()->after('interest_confirmed_at');
        });
    }

    public function down(): void
    {
        Schema::table('fermento_guests', function (Blueprint $table) {
            $table->dropColumn(['interest_confirmed_at', 'invite_sent_at']);
        });
    }
};
