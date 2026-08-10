<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Catálogo de experiencias/eventos del universo CLAN.
     * Pensada para ser reutilizable por futuros proyectos (Sanca, Kuraka, Guiñapo, etc.),
     * no solo para Íntimo.
     */
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('project')->default('clan'); // clan | sanca | kuraka | guinapo | ...
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('tagline')->nullable(); // ej. "No es una cena. Es una conversación que solo ocurre una vez."
            $table->text('description')->nullable(); // texto largo del concepto
            $table->unsignedTinyInteger('courses')->nullable(); // ej. 8 tiempos
            $table->unsignedTinyInteger('party_size')->default(2); // pensado para cuántas personas (Íntimo = 2)
            $table->decimal('price', 10, 2); // precio objetivo por experiencia (ej. S/160 por pareja)
            $table->string('currency', 3)->default('PEN');
            $table->string('video_url')->nullable();
            $table->string('cover_image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
