<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Invitados de un evento, cada uno con un token único para su link personalizado.
     * Reutilizable para futuros eventos del universo CLAN (no solo Íntimo).
     *
     * Nombrada "intimo_guests" (en vez de "guests") para no chocar con la tabla
     * "guests" que ya existe en este proyecto para el RSVP de ShowClinic — mismo
     * nombre, esquema completamente distinto.
     */
    public function up(): void
    {
        Schema::create('intimo_guests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('token', 12)->unique();
            $table->timestamp('opened_at')->nullable(); // primera vez que abrió su link
            $table->timestamp('whatsapp_sent_at')->nullable(); // cuándo se le envió el mensaje
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('intimo_guests');
    }
};
