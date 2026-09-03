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
     */
    public function up(): void
    {
        Schema::table('event_tables', function (Blueprint $table) {
            $table->dropUnique(['event_id', 'table_number']);
            $table->foreignId('event_schedule_id')->nullable()->after('event_id')->constrained()->nullOnDelete();
            $table->boolean('is_social')->default(false)->after('capacity_max');
            // Antes la mesa #3 era única por evento; ahora cada fecha tiene su
            // propio set de mesas, así que el número solo debe ser único
            // dentro de la misma fecha (event_schedule_id null = mesas
            // "viejas" sin fecha propia, de paso a ser migradas/borradas por
            // FermentoSeeder — no necesitan unicidad entre sí).
            $table->unique(['event_id', 'event_schedule_id', 'table_number']);
        });
    }

    public function down(): void
    {
        Schema::table('event_tables', function (Blueprint $table) {
            $table->dropUnique(['event_id', 'event_schedule_id', 'table_number']);
            $table->dropConstrainedForeignId('event_schedule_id');
            $table->dropColumn('is_social');
            $table->unique(['event_id', 'table_number']);
        });
    }
};
