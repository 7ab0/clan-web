<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * El link personalizado del influencer ahora abre la landing normal de
     * Fermento (/fermento/{token}), igual que fermento_guests/intimo_guests
     * — mismo campo opened_at para que el staff vea en el panel si llegó a
     * abrirlo.
     */
    public function up(): void
    {
        Schema::table('influencers', function (Blueprint $table) {
            $table->timestamp('opened_at')->nullable()->after('token');
        });
    }

    public function down(): void
    {
        Schema::table('influencers', function (Blueprint $table) {
            $table->dropColumn('opened_at');
        });
    }
};
