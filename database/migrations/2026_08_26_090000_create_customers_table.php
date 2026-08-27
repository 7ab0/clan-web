<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Base de clientes simple, alimentada automáticamente por las reservas
     * de Fermento (por ahora — se extiende a otros eventos más adelante).
     * Campos pensados para mapear 1 a 1 con la "Base de Clientes Clan"
     * (artifact) el día que se retome esa integración: nombre, telefono,
     * email, marcas, frecuencia, vip, cumpleMes, cumpleDia, notas,
     * fechaAlta (= created_at acá).
     */
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            // Normalizado a "51XXXXXXXXX" — ver Customer::normalizePhone().
            // Es la clave de deduplicación: una reserva repetida con el
            // mismo teléfono actualiza al cliente en vez de duplicarlo.
            $table->string('phone')->unique();
            $table->string('email')->nullable();
            // Lista de marcas del grupo a las que pertenece este cliente
            // (ej. ["Molto"]). El staff puede agregar más a mano más
            // adelante; las reservas solo agregan, nunca quitan.
            $table->json('brands');
            // nueva | ocasional | frecuente
            $table->string('frequency')->default('nueva');
            $table->boolean('vip')->default(false);
            $table->unsignedTinyInteger('birth_month')->nullable();
            $table->unsignedTinyInteger('birth_day')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
