<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Mesas físicas de un evento (ej. Fermento: 12 mesas numeradas, cada una
     * con su propio aforo). Un evento sin filas aquí sigue reservando por
     * cupo simple (ej. Íntimo), como hasta ahora.
     */
    public function up(): void
    {
        Schema::create('event_tables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('table_number');
            $table->unsignedTinyInteger('capacity');
            $table->timestamps();

            $table->unique(['event_id', 'table_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_tables');
    }
};
