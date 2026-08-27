<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Invitados de Fermento, cada uno con un token único para su link
     * personalizado. Mismo esquema que intimo_guests, pero en tabla propia:
     * se decidió mantener cada evento con su propia tabla de invitados
     * (mismo criterio que ya separa Guest de ShowClinic de IntimoGuest).
     */
    public function up(): void
    {
        Schema::create('fermento_guests', function (Blueprint $table) {
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
        Schema::dropIfExists('fermento_guests');
    }
};
