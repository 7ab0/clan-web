<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Reemplaza el aforo fijo por un rango (capacity_min/capacity_max): una
     * mesa base de Fermento es para 2, pero se puede adaptar/combinar hasta 4.
     * party_size sigue acotado por capacity_max al reservar.
     */
    public function up(): void
    {
        Schema::table('event_tables', function (Blueprint $table) {
            $table->unsignedTinyInteger('capacity_min')->default(1)->after('table_number');
            $table->unsignedTinyInteger('capacity_max')->default(1)->after('capacity_min');
        });

        DB::table('event_tables')->select('id', 'capacity')->orderBy('id')->each(function ($row) {
            DB::table('event_tables')->where('id', $row->id)->update([
                'capacity_min' => $row->capacity,
                'capacity_max' => $row->capacity,
            ]);
        });

        Schema::table('event_tables', function (Blueprint $table) {
            $table->dropColumn('capacity');
        });
    }

    public function down(): void
    {
        Schema::table('event_tables', function (Blueprint $table) {
            $table->unsignedTinyInteger('capacity')->default(1)->after('table_number');
        });

        DB::table('event_tables')->select('id', 'capacity_max')->orderBy('id')->each(function ($row) {
            DB::table('event_tables')->where('id', $row->id)->update(['capacity' => $row->capacity_max]);
        });

        Schema::table('event_tables', function (Blueprint $table) {
            $table->dropColumn(['capacity_min', 'capacity_max']);
        });
    }
};
