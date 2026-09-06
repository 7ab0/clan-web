<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Las mesas pasan de pertenecer al evento (compartidas entre todas sus
     * fechas) a pertenecer a una fecha puntual (event_schedule_id) — hace
     * falta porque Fermento necesita capacidades distintas por mesa según la
     * fecha (ej. Mesa 1 = 1 persona el viernes, 8 el domingo). Nullable
     * porque el repoblado real de filas por fecha lo hace FermentoSeeder, no
     * esta migración. is_social marca la mesa comunitaria de una fecha,
     * donde varios grupos que reservan por separado comparten aforo.
     *
     * Orden de operaciones importa en MySQL (no en SQLite, donde esto
     * funcionaba en cualquier orden): el índice único viejo
     * (event_id, table_number) es el que sostiene la foreign key de
     * event_id. Si se suelta antes de que exista otro índice que también
     * arranque en event_id, MySQL lo rechaza con el error 1553 ("needed in
     * a foreign key constraint"). Por eso acá primero se agregan las
     * columnas, después se crea el índice único nuevo (que también arranca
     * en event_id y puede tomar la posta de la FK), y recién al final se
     * suelta el viejo — cada paso en su propio Schema::table() para
     * garantizar que sean statements separados y en ese orden exacto.
     */
    public function up(): void
    {
        Schema::table('event_tables', function (Blueprint $table) {
            $table->foreignId('event_schedule_id')->nullable()->after('event_id')->constrained()->nullOnDelete();
            $table->boolean('is_social')->default(false)->after('capacity_max');
        });

        Schema::table('event_tables', function (Blueprint $table) {
            // Antes la mesa #3 era única por evento; ahora cada fecha tiene su
            // propio set de mesas, así que el número solo debe ser único
            // dentro de la misma fecha (event_schedule_id null = mesas
            // "viejas" sin fecha propia, de paso a ser migradas/borradas por
            // FermentoSeeder — no necesitan unicidad entre sí).
            $table->unique(['event_id', 'event_schedule_id', 'table_number']);
        });

        Schema::table('event_tables', function (Blueprint $table) {
            $table->dropUnique(['event_id', 'table_number']);
        });
    }

    public function down(): void
    {
        // Mismo cuidado de orden que en up(), en espejo: primero se
        // re-crea el índice viejo (para que la FK tenga de dónde agarrarse),
        // recién después se suelta el nuevo.
        Schema::table('event_tables', function (Blueprint $table) {
            $table->unique(['event_id', 'table_number']);
        });

        Schema::table('event_tables', function (Blueprint $table) {
            $table->dropUnique(['event_id', 'event_schedule_id', 'table_number']);
        });

        Schema::table('event_tables', function (Blueprint $table) {
            $table->dropConstrainedForeignId('event_schedule_id');
            $table->dropColumn('is_social');
        });
    }
};
