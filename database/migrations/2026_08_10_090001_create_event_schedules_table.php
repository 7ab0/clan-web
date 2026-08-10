<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Turnos disponibles de un evento: fecha + hora + cupo.
     * Cada fila es un "slot" reservable (ej. Íntimo del 25/07 a las 19:30, cupo 6 mesas).
     */
    public function up(): void
    {
        Schema::create('event_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->time('start_time');
            $table->unsignedSmallInteger('capacity')->default(1); // cuántas mesas/parejas caben en ese turno
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['event_id', 'date', 'start_time']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_schedules');
    }
};
