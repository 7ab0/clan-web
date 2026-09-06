<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Manifestación de interés cuando una fecha de Fermento se queda sin
     * mesas — no asigna mesa ni confirma nada, solo deja el dato para que el
     * encargado contacte manualmente si se libera un cupo.
     */
    public function up(): void
    {
        Schema::create('fermento_waitlist_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_schedule_id')->constrained()->cascadeOnDelete();
            $table->string('name', 120);
            $table->string('phone', 30);
            $table->unsignedTinyInteger('party_size');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fermento_waitlist_entries');
    }
};
