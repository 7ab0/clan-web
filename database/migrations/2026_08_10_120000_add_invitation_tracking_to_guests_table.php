<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * "allowed_companions" viene de la lista original del cliente (cuántos
     * puede traer cada invitado). Es independiente de "plus_one"/
     * "companion_name", que es la respuesta RSVP real del invitado.
     */
    public function up(): void
    {
        Schema::table('guests', function (Blueprint $table) {
            $table->boolean('pre_invitation_sent')->default(false)->after('status');
            $table->boolean('invitation_sent')->default(false)->after('pre_invitation_sent');
            $table->unsignedInteger('allowed_companions')->default(0)->after('invitation_sent');
        });
    }

    public function down(): void
    {
        Schema::table('guests', function (Blueprint $table) {
            $table->dropColumn(['pre_invitation_sent', 'invitation_sent', 'allowed_companions']);
        });
    }
};
